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

    if (empty($_POST['pname'])) {
        $errors[] = 'You forgot to enter the name of the Product.';
    } else {
        $pname = $_POST['pname'];
    }

    if (empty($_POST['price']) || !is_numeric($_POST['price'])) {
        $errors[] = 'You forgot to enter the price of the Product. or it is not numeric';
    } else {
        $price = $_POST['price'];
    }

    if (empty($_POST['manufacturer_id'])) {
        $errors[] = 'You forgot to enter the Manufacturer Id for the Product.';
    } else {
        $manufacturer_id = $_POST['manufacturer_id'];
    }

    if (empty($_POST['subtype_id'])) {
        $errors[] = 'You forgot to enter the SubType for the Product.';
    } else {
        $subtype_id = $_POST['subtype_id'];
    }

    if (empty($_POST['os'])) {
        $errors[] = 'You forgot to enter OS of the Product. If the product has No Operating System Select "NO OS" from the multiselect';
    } else {
        $osupdate = $_POST['os'];
    }

    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {

        if($_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/png") {
            //    Temporary file name stored on the server
        $tmpName = $_FILES['image']['tmp_name'];
        // Read the file
        $fp = fopen($tmpName, 'r');
        $data = fread($fp, filesize($tmpName));
        $data = addslashes($data);
        fclose($fp);
    } else {
            $errors[] = 'Incorrect File type for Image. Only Jpeg and Png Supported';
        }
    } else {

        $data = '';
    }
    if (empty($errors)) { // If everything's OK.

        if (!empty($_POST['releasedate'])) {
            $releasedate = $_POST['releasedate'];
            $query = "UPDATE products SET p_name='$pname', p_releasedate='$releasedate', p_price='$price', idManufacturer='$manufacturer_id', idProduct_Subtype='$subtype_id', p_image = '$data' WHERE idProducts = $id";
        } else {
            $query = "UPDATE products SET p_name='$pname', p_price='$price', idManufacturer='$manufacturer_id', idProduct_Subtype='$subtype_id', p_image = '$data', p_releasedate = NULL WHERE idProducts = $id";
        }
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.
            $querydelete = "DELETE FROM products_has_operating_system WHERE idProducts=$id";
            $resultdelete = @mysqli_query ($dbc, $querydelete); // Run the query.
            foreach($osupdate as $osadd){
                if($osadd != "NO OS"){
                    $query = "INSERT INTO products_has_operating_system (idOperating_System,idProducts) VALUES ('$osadd', '$id')";
                    $result = @mysqli_query ($dbc, $query); // Run the query.
                }
            }
            // Print a message.
            echo '<p><b>The product record has been edited.</b></p><p><br/></p>';

        } else { // If it did not run OK.
            echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The product could not be edited due to a system error.</p>'; // Public message.
            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>
            <p>
            <a href="index.php">Home Page</a>
            <a href="view_products.php">View All Products</a>
            <a href = "product_details.php?id=' . $id . '">View Product Details</a>
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

$query = "select p.p_name, p.p_releasedate, p.p_price, p.idManufacturer, p.idProduct_Subtype, m.m_name, ps.s_name from products as p, manufacturer as m, product_subtype as ps where p.idManufacturer = m.idManufacturer AND p.idProduct_Subtype = ps.idProduct_Subtype AND p.idProducts = $id ";
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) {

    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    $this_manufacturer_id=$row[3];
    $this_subtype_id=$row[4];
    // Create the form.


    echo '<h2>Edit a Product</h2>

<form enctype="multipart/form-data" action="edit_product.php" method="post">';

    $query = "SELECT p_name, p_releasedate, p_price FROM products WHERE idProducts = $id ";
    $result = @mysqli_query ($dbc, $query); // Run the query.
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    echo '
<p>Product Name: <input type="text" name="pname" size="35" maxlength="35" value=';
    if(isset($_POST['pname'])) {
        echo "'$_POST[pname]'";
    } else {
        echo "'$row[0]'";
    }
    echo '/> </p>';

echo '<p>Release Date: <input type="date" name="releasedate" value=';
    if(isset($_POST['releasedate'])) {
        echo "'$_POST[releasedate]'";
    } else {
        echo "'$row[1]'";
    }
    echo '/> </p>';

echo '<p>Price: <input type="text" name="price" size="10" maxlength="10" value=';
    if(isset($_POST['price'])) {
        echo "'$_POST[price]'";
    } else {
        echo "'$row[2]'";
    }
    echo '/> </p>';

    echo '<p>Manufacturer: <select name="manufacturer_id">';
    $query = "SELECT idManufacturer, m_name FROM manufacturer";
    $result = @mysqli_query ($dbc, $query);

    if (isset($_POST['manufacturer_id'])){
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
        {
            if ($row['idManufacturer'] == $_POST['manufacturer_id'])
            {
                echo '<option value="'.$row['idManufacturer'].'" selected="selected">' . 	$row['m_name'] .  '</option>';
            }
            else
            {
                echo '<option value="'.$row['idManufacturer'].'">'. $row['m_name'] . '</option>';
            }
        }

    } else {
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
    }

    echo '</select> </p>';

    echo '<p>Product SubType: <select name="subtype_id">';

    $query = "SELECT idProduct_Subtype, s_name FROM product_subtype";
    $result = @mysqli_query ($dbc, $query);
    if (isset($_POST['manufacturer_id'])){
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
        {
            if ($row['idProduct_Subtype'] == $_POST['subtype_id'])
            {
                echo '<option value="'.$row['idProduct_Subtype'].'" 	selected="selected">'.$row['s_name'].'</option>';
            }
            else
            {
                echo '<option 	value="'.$row['idProduct_Subtype'].'">'.$row['s_name'].'</option>';
            }
        }
    } else {
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
    }

    echo '</select> </p>';

echo '<p>Operating System: <select multiple="multiple" name="os[]">';
            $query = "SELECT idOperating_System, o_name FROM operating_system ORDER BY o_name ASC";
            $result_os = @mysqli_query ($dbc, $query);
            if (isset($_POST['os'])){
                $os = $_POST['os'];
                while ($row = mysqli_fetch_array($result_os, MYSQL_ASSOC)) {
                    if (in_array($row['idOperating_System'], $os)) {
                        echo '<option value="' . $row['idOperating_System'] . '" selected="selected">' . $row['o_name'] . '</option>';
                    } else {
                        echo '<option value="' . $row['idOperating_System'] . '">' . $row['o_name'] . '</option>';
                    }

                }
                if(in_array("NO OS",$os)){
                    echo '<option value="NO OS" selected = "selected">NO OS</option>';
                }
                else {
                    echo '<option value="NO OS">NO OS</option>';
                }
            }
            else {
                $query2 = "SELECT idOperating_System FROM products_has_operating_system WHERE idProducts = $id";
                $result2 = @mysqli_query ($dbc, $query2);
                $os = [];
                while ($rowos = mysqli_fetch_array($result2, MYSQL_ASSOC)) {
                    array_push($os, $rowos['idOperating_System']);
                }
                while ($row = mysqli_fetch_array($result_os, MYSQL_ASSOC)) {
                    if (in_array($row['idOperating_System'], $os)) {
                        echo '<option value="' . $row['idOperating_System'] . '" selected="selected">' . $row['o_name'] . '</option>';
                    } else {
                        echo '<option value="' . $row['idOperating_System'] . '">' . $row['o_name'] . '</option>';
                    }
                }
                if(empty($os)){
                    echo '<option value="NO OS" selected = "selected">NO OS</option>';
                }
                else {
                    echo '<option value="NO OS">NO OS</option>';
                }
            }

echo'</select>&nbsp;&nbsp;&nbsp;<a href="add_os.php">Add a new Operating System</a>
</p>';


echo '<p>Product Image: <input name="image" accept="image/*" type="file"></p>';

echo '<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Submit" /></p>
<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
        <a href = "product_details.php?id=' . $id . '">View Product Details</a>
</p>

</form>
';

} else {
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Product ID.</p><p><br /><br /></p>
	<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>';
}
mysqli_close($dbc); // Close the database connection.

?>