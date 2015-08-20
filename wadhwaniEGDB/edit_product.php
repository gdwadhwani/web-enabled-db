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
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
    exit();
}

include ('connect.php');

// Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    $errors = array(); // Initialize error array.

    // Check for a title.
    if (empty($_POST['pname'])) {
        $errors[] = 'You forgot to enter the name of the Product.';
    } else {
        $pname = $_POST['pname'];
    }

    // Check for a genre ID.
    if (empty($_POST['price'])) {
        $errors[] = 'You forgot to enter the price of the Product.';
    } else {
        $price = $_POST['price'];
    }

    // Check for length.
    if (empty($_POST['manufacturer_id'])) {
        $errors[] = 'You forgot to enter the Manufacturer Id for the Product.';
    } else {
        $manufacturer_id = $_POST['manufacturer_id'];
    }

    // Check for year.
    if (empty($_POST['subtype_id'])) {
        $errors[] = 'You forgot to enter the SubType for the Product.';
    } else {
        $subtype_id = $_POST['subtype_id'];
    }


    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {

        //    Temporary file name stored on the server
        echo "test";
        $tmpName = $_FILES['image']['tmp_name'];
        echo $tmpName;
// Read the file
        $fp = fopen($tmpName, 'r');
        $data = fread($fp, filesize($tmpName));
        $data = addslashes($data);
        fclose($fp);
    } else {
        $data = '';
    }

    if (empty($errors)) { // If everything's OK.

        $releasedate = $_POST['releasedate'];
        // Make the query.
        $query = "UPDATE products SET p_name='$pname', p_releasedate='$releasedate', p_price='$price', idManufacturer='$manufacturer_id', idProduct_Subtype='$subtype_id', p_image = '$data' WHERE idProducts = $id";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.

            // Print a message.
            echo '<h1 id="mainhead">Edit a Product</h1>
				<p>The product record has been edited.</p><p><br /><br /></p>';

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The movie could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
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
$query = "select p.p_name, p.p_releasedate, p.p_price, p.idManufacturer, p.idProduct_Subtype, m.m_name, ps.s_name from products as p, manufacturer as m, product_subtype as ps where p.idManufacturer = m.idManufacturer AND p.idProduct_Subtype = ps.idProduct_Subtype AND p.idProducts = $id ";
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) { // Valid movie ID, show the form.

    // Get the movie's information.
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    $this_manufacturer_id=$row[3];
    $this_subtype_id=$row[4];
    // Create the form.


    echo '<h2>Edit a Movie</h2>

<form enctype="multipart/form-data" action="edit_product.php" method="post">';

    $query = "SELECT p_name, p_releasedate, p_price FROM products WHERE idProducts = $id ";
    $result = @mysqli_query ($dbc, $query); // Run the query.
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    echo '
<p>Name: <input type="text" name="pname" size="20" maxlength="40" value="' . $row[0] . '"  /> </p>
<p>Release Date: <input type="date" name="releasedate" size="20" maxlength="40" value="' . $row[1] . '"  /> </p>
<p>Price: <input type="text" name="price" size="20" maxlength="40" value="' . $row[2] . '"  /> </p>';

// Build the query for director drop-down
    echo '<p>Manufacturer: <select name="manufacturer_id">';
    $query = "SELECT idManufacturer, m_name FROM manufacturer";
    $result = @mysqli_query ($dbc, $query);

    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
    {
        if ($row['idManufacturer'] == $this_manufacturer_id)
        {
            echo '<option value="'.$row['idManufacturer'].'" selected="selected">' . 	$row['m_name'] .  '</option>';
        }
        else
        {
            echo '<option value="'.$row['idManufacturer'].'">'. $row['m_name'] . '</option>';
        }
    }
    echo '</select> </p>';

    echo '<p>Product SubType: <select name="subtype_id">';

    // Build the query for genre drop-down
    $query = "SELECT idProduct_Subtype, s_name FROM product_subtype";
    $result = @mysqli_query ($dbc, $query);

    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
    {
        if ($row['idProduct_Subtype'] == $this_subtype_id)
        {
            echo '<option value="'.$row['idProduct_Subtype'].'" 	selected="selected">'.$row['s_name'].'</option>';
        }
        else
        {
            echo '<option 	value="'.$row['idProduct_Subtype'].'">'.$row['s_name'].'</option>';
        }
    }
    echo '</select> </p>';


echo '<p><input name="image" accept="image/jpeg" type="file"></p>';

echo '<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Submit" /></p>
<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
</p>

</form>
';

} else { // Not a valid movie ID.
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid movie ID.</p><p><br /><br /></p>';
}
mysqli_close($dbc); // Close the database connection.

?>