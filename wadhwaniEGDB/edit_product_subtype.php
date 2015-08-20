<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/16/2015
 * Time: 11:01 PM
 */

$page_title = 'Edit a Product';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_products.php
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
    $id = $_POST['id'];
} else { // No valid ID, kill the script.
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p><p>
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

    // Check for a title.
    if (empty($_POST['pst'])) {
        $errors[] = 'You forgot to enter the name of the Product SubType.';
    } else {
        $pst = $_POST['pst'];
    }

    // Check for a genre ID.
    if (empty($_POST['pt'])) {
        $errors[] = 'You forgot to enter the Product Type.';
    } else {
        $pt = $_POST['pt'];
    }


    if (empty($errors)) { // If everything's OK.

        // Make the query.
        $query = "UPDATE product_subtype SET s_name='$pst', idProduct_Type='$pt' WHERE idProduct_Subtype = $id";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.

            // Print a message.
            echo '<h1 id="mainhead">Edit a Product SubType</h1>
				<p>The Product SubType record has been edited.</p><p><br /><br /></p><p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>';
            exit();

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The Product Subtype could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p><p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>';// Debugging message.
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

// Retrieve the movies's information.
$query = "select s_name, idProduct_Type from product_subtype WHERE idProduct_Subtype = $id ";
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) { // Valid movie ID, show the form.

    // Get the movie's information.
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    $this_type_id=$row[1];
    // Create the form.


    echo '<h2>Edit a Product SubType</h2>

<form action="edit_product_subtype.php" method="post">';

//    $query = "SELECT p_name, p_releasedate, p_price FROM products WHERE idProducts = $id ";
//    $result = @mysqli_query ($dbc, $query); // Run the query.
//    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    echo '
<p>Product SubType: <input type="text" name="pst" size="20" maxlength="40" value=';
    if(isset($_POST['pst'])) {
        echo "'$_POST[pst]'";
    } else {
        echo "'$row[0]'";
    }
    echo '/> </p>';

// Build the query for director drop-down
    echo '<p>Product Type: <select name="pt">';
    $query = "SELECT idProduct_Type, p_type FROM product_type";
    $result = @mysqli_query ($dbc, $query);
    if (isset($_POST['pt'])){
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
        {
            if ($row['idProduct_Type'] == $_POST['pt'])
            {
                echo '<option value="'.$row['idProduct_Type'].'" selected="selected">' . 	$row['p_type'] .  '</option>';
            }
            else
            {
                echo '<option value="'.$row['idProduct_Type'].'">'. $row['p_type'] . '</option>';
            }
        }
    } else {
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
        {
            if ($row['idProduct_Type'] == $this_type_id)
            {
                echo '<option value="'.$row['idProduct_Type'].'" selected="selected">' . 	$row['p_type'] .  '</option>';
            }
            else
            {
                echo '<option value="'.$row['idProduct_Type'].'">'. $row['p_type'] . '</option>';
            }
        }
    }

    echo '</select> </p>';

    echo '<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Submit" /></p>
</form>
';

} else { // Not a valid movie ID.
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid movie ID.</p><p><br /><br /></p>
	<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>';
}
mysqli_close($dbc); // Close the database connection.

?>