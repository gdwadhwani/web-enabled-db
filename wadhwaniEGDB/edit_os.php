<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/16/2015
 * Time: 11:01 PM
 */

$page_title = 'Edit an Operating System';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_os.php
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
    $id = $_POST['id'];
} else {
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>
	<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
</p>';
    exit();
}

include ('connect.php');

// Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    $errors = array(); // Initialize error array.

    if (empty($_POST['oname'])) {
        $errors[] = 'You forgot to enter the name of the Operating System.';
    } else {
        $oname = $_POST['oname'];
    }

    if (empty($_POST['version'])) {
        $errors[] = 'You forgot to enter the version of the OS.';
    } else {
        $version = $_POST['version'];
    }

    if (empty($_POST['releasedate'])) {
        $errors[] = 'You forgot to enter the Release date of the product.';
    } else {
        $releasedate = $_POST['releasedate'];
    }

    if (empty($errors)) { // If everything's OK.

        // Make the query.
        $price = $_POST['price'];
        $query = "UPDATE operating_system SET o_name='$oname',o_latestversion = '$version', o_releasedate='$releasedate', o_free = '$price' WHERE idOperating_System = $id";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.

            // Print a message.
            echo '<h1 id="mainhead">Edit a OS</h1>
				<p>The OS record has been edited.</p><p><br /><br /></p>';

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The OS could not be edited due to a system error.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>
            <p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
</p>'; // Debugging message.
            exit();
        }

    } // End of if (empty($errors)) IF.

    else { // Report the errors.

        echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        } // End of foreach
        echo '</p><p>Please try again.</p><p><br /></p>';

    }  // End of report errors else()

} // End of submit conditional.

// Always show the form.

$query = "SELECT o_name, o_latestversion, o_releasedate, o_free FROM operating_system WHERE idOperating_System = $id ";
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    // Create the form.


    echo '<h2>Edit an Operating System</h2>

<form action="edit_os.php" method="post">';

    echo '
<p>Name: <input type="text" name="oname" size="20" maxlength="40" value=';
    if(isset($_POST['oname'])) {
        echo "'$_POST[oname]'";
    } else {
        echo "'$row[0]'";
    }
    echo '/> </p>';
echo '<p>Latest Version: <input type="text" name="version" size="20" maxlength="40" value=';
    if(isset($_POST['version'])) {
        echo "'$_POST[version]'";
    } else {
        echo "'$row[1]'";
    }
    echo '/> </p>';
echo '<p>Release Date: <input type="date" name="releasedate" size="20" maxlength="40" value=';
    if(isset($_POST['releasedate'])) {
        echo "'$_POST[releasedate]'";
    } else {
        echo "'$row[2]'";
    }
    echo '/> </p>';

    echo '<p>OS Type: <select name="price">';
    if(isset($_POST['price'])) {
        if ($_POST['price'] == 1) {
            echo  '<option value="1" selected = "selected" > FREE </option>';
            echo  '<option value="0"> PAID </option>';
        }
        else{
            echo '<option value="0" selected = "selected" > PAID </option>';
            echo '<option value="1"> FREE </option>';
        }
    } else{
        if ($row[3] == 1) {
            echo  '<option value="1" selected = "selected" > FREE </option>';
            echo  '<option value="0"> PAID </option>';
        }
        else{
            echo '<option value="0" selected = "selected" > PAID </option>';
            echo '<option value="1"> FREE </option>';
        }
    }

    echo '</select> </p>';

    echo '<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Submit" /></p>
<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
</p>
</form>
';

} else {
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid movie ID.</p><p><br /><br /></p>
	<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>';
}

mysqli_close($dbc); // Close the database connection.

?>