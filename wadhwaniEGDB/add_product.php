<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/15/2015
 * Time: 10:56 PM
 */

$page_title = 'Add Product';

include ('connect.php');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    $errors = array(); // Initialize error array.

    if (empty($_POST['pname'])) {
        $errors[] = 'You forgot to enter the name of the product.';
    } else {
        $pname = $_POST['pname'];
    }

    if (empty($_POST['price']) || !is_numeric($_POST['price'])) {
        $errors[] = 'You forgot to enter the price of the product or it is not numeric.';
    } else {
        $price = $_POST['price'];

    }

    if (empty($_POST['pst'])) {
        $errors[] = 'You forgot to enter the product subtype.';
    } else {
        $pst = $_POST['pst'];
    }


    if (empty($_POST['manufacturerid'])) {
        $errors[] = 'You forgot to enter the Manufacturer of the Product.';
    } else {
        $manufacturerid = $_POST['manufacturerid'];
    }

    if (empty($_POST['os'])) {
        $errors[] = 'You forgot to enter OS of the Product. If the product has No Operating System Select "NO OS" from the multiselect';
    } else {
        $os = $_POST['os'];
    }
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0 && ($_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/png")) {

        //    Temporary file name stored on the server
        $tmpName = $_FILES['image']['tmp_name'];
// Read the file
        $fp = fopen($tmpName, 'r');
        $data = fread($fp, filesize($tmpName));
        $data = addslashes($data);
        fclose($fp);
    } else {
        $errors[] = 'Incorrect File type for Image. Only Jpeg and Png Supported';
        $data = '';
    }

    if (empty($errors)) { // If everything's okay.

        // Make the query.
        if (!empty($_POST['releasedate'])) {
            $releasedate = $_POST['releasedate'];
            $query = "INSERT INTO products (p_name, p_releasedate, p_price, idManufacturer, idProduct_Subtype, p_image) VALUES ('$pname', '$releasedate', '$price', '$manufacturerid', '$pst' , '$data')";
        } else {
            $query = "INSERT INTO products (p_name, p_price, idManufacturer, idProduct_Subtype, p_image) VALUES ('$pname', '$price', '$manufacturerid', '$pst' , '$data')";
        }

        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ($result) { // If it ran OK.
            $product_id = mysqli_insert_id($dbc); // Retrieve the id number of the newly added record
            foreach($os as $ostype){
                if ($ostype != "NO OS") {
                    $query = "INSERT INTO products_has_operating_system (idOperating_System,idProducts) VALUES ('$ostype', '$product_id')";
                    $result = @mysqli_query ($dbc, $query); // Run the query.
                }
            }
            if($result){
                echo '<h1 id="mainhead">Success!</h1>
		              <p>Product Addded: <b>'.$pname.'</b></p>
                      <p>Check the below form for the additional details</p>';

            } else {
                echo '<h1 id="mainhead">System Error</h1>
			          <p class="error">The Product could not be added due to a system error.</p>';
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                echo '<p>
                      <a href="index.php">Home Page</a>
                      <a href="add_product.php">Add Product</a>
                      </p>';
                exit();
            }

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
			      <p class="error">The Product could not be added due to a system error.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
            echo '<p>
                  <a href="index.php">Home Page</a>
                  <a href="add_product.php">Add Product</a>
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
<?php
if (isset($_POST['manufacturerid'])) {
    $this_manufacturer_id=$_POST['manufacturerid'];
} else {
    $this_manufacturer_id=0;
}
if (isset($_POST['pst'])) {
    $this_pst_id=$_POST['pst'];
} else {
    $this_pst_id=0;
}
if (isset($_POST['os'])) {
    $this_os_id = $_POST['os'];
} else {
    $this_os_id = 0;
}
?>
<h2>Add Product</h2>
<form action="add_product.php" method="post" enctype="multipart/form-data">
    <p>Product Name: <input type="text" name="pname" size="35" maxlength="35" value="<?php if (isset($_POST['pname'])) echo $_POST['pname']; ?>" /></p>
    <p>Price: <input type="text" name="price" size="10" maxlength="10" value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>"  /> </p>
    <p>Release Date: <input type="date" name="releasedate" value="<?php if (isset($_POST['releasedate'])) echo $_POST['releasedate']; ?>"  /> </p>
    <p>Product Subtype: <select name="pst">
            <?php
            if (isset($_POST['pst'])){
                include('connect.php');
            }
            // Build the query
            $query = "SELECT idProduct_Subtype, s_name FROM product_subtype ORDER BY s_name ASC";
            $result_ps = @mysqli_query ($dbc, $query);
            while ($row = mysqli_fetch_array($result_ps, MYSQL_ASSOC))
            {
                if ($row['idProduct_Subtype'] == $this_pst_id) {
                    echo '<option value="'.$row['idProduct_Subtype'].'" selected="selected">'.$row['s_name'].'</option>';
                }
                else {
                    echo'<option value="'.$row['idProduct_Subtype'].'">'.$row['s_name'].'</option>';}

            }
            ?>
        </select>&nbsp;&nbsp;&nbsp;<a href="add_product_subtype.php">Add a new Product SubType</a>
    </p>
    <p>Manufacturer: <select name="manufacturerid">
            <?php
            // Build the query
            $query = "SELECT idManufacturer,m_name FROM manufacturer ORDER BY m_name ASC";
            $result_manufacturer = @mysqli_query ($dbc, $query);
            while ($row = mysqli_fetch_array($result_manufacturer, MYSQL_ASSOC))
            {
                if ($row['idManufacturer'] == $this_manufacturer_id) {
                    echo '<option value="'.$row['idManufacturer'].'" selected="selected">'.$row['m_name'].' </option>';
                }
                else {
                    echo'<option value="'.$row['idManufacturer'].'">'.$row['m_name'].'</option>';}

            }
            ?>
        </select>&nbsp;&nbsp;&nbsp;<a href="add_manufacturer.php">Add a new Manufacturer</a>
    </p>
    <p>Operating System: <select multiple="multiple" name="os[]">
            <?php
            $query = "SELECT idOperating_System, o_name FROM operating_system ORDER BY o_name ASC";
            $result_os = @mysqli_query ($dbc, $query);

            while ($row = mysqli_fetch_array($result_os, MYSQL_ASSOC)) {
                if (in_array($row['idOperating_System'], $this_os_id)) {
                    echo '<option value="' . $row['idOperating_System'] . '" selected="selected">' . $row['o_name'] . '</option>';
                } else {
                    echo '<option value="' . $row['idOperating_System'] . '">' . $row['o_name'] . '</option>';
                }
            }
            if($this_os_id == 0 or $this_os_id[0] == "NO OS"){
                echo '<option value="NO OS" selected = "selected">NO OS</option>';
            }
            else {
                echo '<option value="NO OS">NO OS</option>';
            }
            ?>
        </select>&nbsp;&nbsp;&nbsp;<a href="add_os.php">Add a new Operating System</a>
    </p>
    <p>Product Image: <input name="image" accept="image/*" type="file"></p>
    <p><input type="submit" name="submit" value="Add Product" /></p>
    <input type="hidden" name="submitted" value="TRUE" />

  <p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
  </p>

</form>
