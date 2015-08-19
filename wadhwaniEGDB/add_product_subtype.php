<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/15/2015
 * Time: 11:28 PM
 */

$page_title = 'Add Operating System';

include ('connect.php');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    $errors = array(); // Initialize error array.

    if (empty($_POST['st_name'])) {
        $errors[] = 'You forgot to enter the SubType Name.';
    } else {
        $st_name = $_POST['st_name'];
    }

    if (empty($_POST['type'])) {
        $errors[] = 'You forgot to select the Type.';
    } else {
        $type = $_POST['type'];
    }

    if (empty($errors)) { // If everything's okay.

        // Make the query.
        $query = "INSERT INTO product_subtype (s_name, idProduct_Type) VALUES ('$st_name', '$type')";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ($result) { // If it ran OK.

            // Print a message.
            echo '<h1 id="mainhead">Success!</h1>
		<p>You have added to the Product SubType table:</p>';

            echo "<table>
		<tr><td>Sub Type Name:</td><td>{$st_name}</td></tr>";

            exit();

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The genre could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
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
<h2>Add Product Subtype</h2>
<form action="add_product_subtype.php" method="post">
    <p>Sub Type Name: <input type="text" name="st_name" size="15" maxlength="15" value="<?php if (isset($_POST['st_name'])) echo $_POST['st_name']; ?>" /></p>
    <p>Product Type: <select name = 'type'>
    <?php
    include ('connect.php');
    $query = "select idProduct_Type, p_type from product_type";
    $result = @mysqli_query ($dbc, $query);
    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
    {
        if($row['idProduct_Type'] == $_POST['type'])
        {
            echo'<option
            value="'.$row['idProduct_Type'].'"
            selected = "selected">'.$row['p_type'].'
            </option>';
        }
        else
        {
            echo'<option
            value="'.$row['idProduct_Type'].'">'.$row['p_type'].'
        </option>';
        }
    }
    echo "</select>";
    ?>
    </p>
    <p><input type="submit" name="submit" value="Add SubType" /></p>
    <input type="hidden" name="submitted" value="TRUE" />
</form>
