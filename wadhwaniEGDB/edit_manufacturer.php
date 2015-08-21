<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/19/2015
 * Time: 8:53 PM
 */

$page_title = 'Edit a Manufacturer';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) {
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
    $id = $_POST['id'];
} else { // No valid ID, kill the script.
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

    if (empty($_POST['mname'])) {
        $errors[] = 'You forgot to enter the name of the Manufacturer.';
    } else {
        $mname = $_POST['mname'];
    }

    if (empty($_POST['mcity'])) {
        $errors[] = 'You forgot to enter the City.';
    } else {
        $mcity = $_POST['mcity'];
    }

    if (empty($_POST['mcountry'])) {
        $errors[] = 'You forgot to enter the Country.';
    } else {
        $mcountry = $_POST['mcountry'];
    }

    if (empty($errors)) { // If everything's OK.

        // Make the query.
        $mstate = $_POST['mstate'];
        $query = "UPDATE manufacturer SET m_name='$mname',m_headquarter = '$mcity', m_headquarterState='$mstate', m_headquarterCountry = '$mcountry' WHERE idManufacturer = $id";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.

            // Print a message.
            echo '<h1 id="mainhead">Edit a OS</h1>
				<p>The Manufacturer record has been edited.</p><p><br /><br /></p>';
        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The Manufacturer could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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

}

// Always show the form.

$query = "SELECT m_name, m_headquarter, m_headquarterState, m_headquarterCountry FROM manufacturer WHERE idManufacturer = $id ";
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    // Create the form.


    echo '<h2>Edit an Manufacturer</h2>

<form action="edit_manufacturer.php" method="post">';

    echo '
<p>Manufacturer Name:<input type="text" name="mname" size="20" maxlength="20" value=';
    if(isset($_POST['mname'])) {
        echo "'$_POST[mname]'";
    } else {
        echo "'$row[0]'";
    }
    echo '/> </p>';

echo '<p>Manufacturer City: <input type="text" name="mcity" size="20" maxlength="40" value=';
    if(isset($_POST['mcity'])) {
        echo "'$_POST[mcity]'";
    } else {
        echo "'$row[1]'";
    }
    echo '/> </p>';
echo '<p>Manufacturer State: <input type="text" name="mstate" size="20" maxlength="40" value=';
    if(isset($_POST['mstate'])) {
        echo "'$_POST[mstate]'";
    } else {
        echo "'$row[2]'";
    }
    echo '/> </p>';
echo '<p>Manufacturer Country: <input type="text" name="mcountry" size="20" maxlength="40" value=';
    if(isset($_POST['mcountry'])) {
        echo "'$_POST[mcountry]'";
    } else {
        echo "'$row[3]'";
    }
    echo '/> </p>';
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
	<p class="error">This page has been accessed in error. Not a valid manufacturer ID.</p><p><br /><br /></p>
	<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>
	';
}

mysqli_close($dbc); // Close the database connection.

