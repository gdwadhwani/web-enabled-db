<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/15/2015
 * Time: 11:10 PM
 */


$page_title = 'Add Operating System';

include ('connect.php');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    $errors = array(); // Initialize error array.
    
    if (empty($_POST['os_name'])) {
        $errors[] = 'You forgot to enter the OS name.';
    } else {
        $os_name = $_POST['os_name'];
    }

    if (empty($_POST['version'])) {
        $errors[] = 'You forgot to enter the latest Version.';
    } else {
        $version = $_POST['version'];
    }

    if (empty($_POST['reldate'])) {
        $errors[] = 'You forgot to enter the Release Date.';
    }
    else {
        $reldate = $_POST['reldate'];
    }

    if (empty($errors)) { // If everything's okay.

        // Add the OS to the database.

        // Make the query.
        $free = $_POST['free'];
        $query = "INSERT INTO operating_system (o_name, o_latestversion, o_releasedate, o_free) VALUES ('$os_name', '$version', '$reldate', '$free')";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ($result) { // If it ran OK.
            // Print a message.
            echo '<h1 id="mainhead">Success!</h1>
		          <p>You have added to the OS table:</p>';

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
			      <p class="error">The Operating System could not be added due to a system error. </p>';
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
            echo '<p>
                    <a href="index.php">Home Page</a>
                    <a href="add_os.php">Add Operating System</a>
                  </p>';
            exit();
        }

    } else { // Report the errors.

        echo '<h1 id="mainhead">Error!</h1>
		      <p class="error">The following error(s) occurred:<br />';
        foreach ($errors as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';

    } // End of if (empty($errors)) IF.
mysqli_close($dbc); // Close the database connection.

} // End of the main Submit conditional.
?>


<h2>Add Operating System</h2>
<form action="add_os.php" method="post">
    <p>OS Name: <input type="text" name="os_name" size="15" maxlength="15" value="<?php if (isset($_POST['os_name'])) echo $_POST['os_name']; ?>" /></p>
    <p>Latest Version: <input type="text" name="version" size="15" maxlength="15" value="<?php if (isset($_POST['version'])) echo $_POST['version']; ?>"/></p>
    <p>Release Date: <input type="date" name="reldate" value="<?php if (isset($_POST['reldate'])) echo $_POST['reldate']; ?>"/></p>
    <p>OS Type Free/Paid: <select name="free">
            <?php
            if (isset($_POST['free'])) {
                if ($_POST['free'] == 1) {
                    echo  '<option value="1" selected = "selected" > FREE </option>';
                    echo  '<option value="0"> PAID </option>';
                }
                else{
                    echo '<option value="0" selected = "selected" > PAID </option>';
                    echo '<option value="1"> FREE </option>';
                }
            }
            else {
                echo '<option value="1" selected = "selected" > FREE </option>';
                echo '<option value="0"> PAID </option>';
            }
            ?>
    </select></p>
    <p><input type="submit" name="submit" value="Add OS" /></p>
    <p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>
    <input type="hidden" name="submitted" value="TRUE" />
</form>
