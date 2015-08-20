<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/15/2015
 * Time: 11:19 PM
 */

$page_title = 'Add Manufacturer';

include ('connect.php');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    $errors = array(); // Initialize error array.

    if (empty($_POST['m_name'])) {
        $errors[] = 'You forgot to enter the Manufacturer name.';
    } else {
        $m_name = $_POST['m_name'];
    }

    if (empty($_POST['city'])) {
        $errors[] = 'You forgot to enter HQ City.';
    } else {
        $city = $_POST['city'];
    }

    if (empty($_POST['country'])) {
        $errors[] = 'You forgot to enter the HQ Country.';
    }
    else {
        $country = $_POST['country'];
    }

    if (empty($errors)) { // If everything's okay.

        // Make the query.
        if($_POST['state'] == "") {
            $query = "INSERT INTO manufacturer (m_name, m_headquarter, m_headquarterCountry) VALUES ('$m_name', '$city', '$country')";
        } else {
            $state = $_POST['state'];
            $query = "INSERT INTO manufacturer (m_name, m_headquarter, m_headquarterState, m_headquarterCountry) VALUES ('$m_name', '$city', '$state', '$country')";
        }
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ($result) { // If it ran OK.

            // Print a message.
            echo '<h1 id="mainhead">Success!</h1>
		<p>You have added to the Manufacturer table:</p>';

            echo "<table>
		<tr><td>Manufacturer Name:</td><td>{$m_name}</td></tr></table>";
            echo '<p>
                    <a href="index.php">Home Page</a>
                    <a href="view_products.php">View All Products</a>
                    </p>';
            exit();

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The genre could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
            echo '<p>
                    <a href="index.php">Home Page</a>
                    <a href="add_manufacturer.php">Add Manufacturer</a>
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
<h2>Add Manufacturer</h2>
<form action="add_manufacturer.php" method="post">
    <p>Manufacturer Name: <input type="text" name="m_name" size="15" maxlength="15" value="<?php if (isset($_POST['m_name'])) echo $_POST['m_name']; ?>" /></p>
    <p>HeadQuarter City: <input type="text" name="city" size="15" maxlength="15" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>"/></p>
    <p>HeadQuarter State: <input type="text" name="state" size="15" maxlength="15" value="<?php if (isset($_POST['state'])) echo $_POST['state']; ?>"/></p>
    <p>HeadQuarter Country: <input type="text" name="country" size="15" maxlength="15" value="<?php if (isset($_POST['country'])) echo $_POST['country']; ?>"/></p>
    <p><input type="submit" name="submit" value="Add Manufacturer" /></p>
    <input type="hidden" name="submitted" value="TRUE" />
    <p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>
</form>
