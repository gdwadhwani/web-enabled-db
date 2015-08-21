<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/16/2015
 * Time: 11:01 PM
 */

$page_title = 'Product Details';

if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_products.php
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
    $id = $_POST['id'];
} else { // No valid ID, kill the script.
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
    echo '<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>';
    exit();
}

include ('connect.php');

// Connect to the db.
// Always show the form.

// Retrieve the product's information.
$query = "select p.p_name, p.p_releasedate, p.p_price, p.idManufacturer, p.idProduct_Subtype, m.m_name, ps.s_name, ps.idProduct_Type from products as p, manufacturer as m, product_subtype as ps where p.idManufacturer = m.idManufacturer AND p.idProduct_Subtype = ps.idProduct_Subtype AND p.idProducts = $id ";
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) {

    // Get the product's information.
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    $name = $row[0];
    $releasedate = $row[1];
    $price = $row[2];
    $this_manufacturer_id=$row[3];
    $this_subtype_id=$row[4];
    $m_name = $row[5];
    $ps_name = $row[6];
    $this_type_id = $row[7];
    // Create the form.


    $query = "select p_type FROM product_type WHERE idProduct_Type = $this_type_id";
    $result = @mysqli_query ($dbc, $query); // Run the query.
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    $pt_name = $row[0];

    $os = [];
    $query = "select * FROM products_has_operating_system WHERE idProducts = $id";
    $result = @mysqli_query ($dbc, $query); // Run the query.
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)){
            array_push($os, $row['idOperating_System']);
        }
    }
    if (!empty($os)) {
        $osname = [];
        foreach($os as $osid){
            $query = "select o_name FROM operating_system WHERE idOperating_System = $osid";
            $result = @mysqli_query ($dbc, $query); // Run the query.
            $row = mysqli_fetch_array($result, MYSQL_ASSOC);
            array_push($osname, $row['o_name']);
        }
    } else {
        $osname = ["No Operating System Defined"];
    }

    if (empty($releasedate)) {
        $releasedate = "No Release Date Set";
    }


    echo '<h2>Product Details</h2>
<p>Name: <b>'. $name .'</b></p>
<p>Release Date: <b>' . $releasedate . '</b></p>
<p>Price: <b>'. $price . '</b></p>
<p>Manufacturer: <b>' . $m_name . '</b> </p>
<p>Product Type: <b>' . $pt_name . '</b> </p>
<p>Product Subtype: <b>' . $ps_name . '</b></p>
<p>Operating Systems: <b>';
    $num = 0;
    foreach($osname as $osoutput) {
        if($osoutput == "No Operating System Defined") {
            echo $osoutput . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        } else {
            echo ($num+1). ")". $osoutput . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            $num++;
        }

    }
echo '</b></p>';
    if (!empty($os)) {
        echo '<div style="float: left;"><table align="left" cellspacing="0" cellpadding="5" border="True" style="display: block">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b>Operating System </b></td>
	<td align="left"><b>Latest Version</b></td>
	<td align="left"><b>Release Date</b></td>
	<td align="left"><b>Price(Free/Paid)</b></td>
	<td align="left"><b>Product Using OS</b></td>
</tr>';
        $bg = '#eeeeee';
        foreach($osname as $osoutput) {

            $query = "select idOperating_System, o_latestversion, o_releasedate, o_free from operating_system WHERE o_name = '$osoutput'";
            $result = @mysqli_query ($dbc, $query); // Run the query.
            $row = mysqli_fetch_array($result, MYSQL_ASSOC);
            $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
            echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_os.php?id=' . $row['idOperating_System'] . '">Edit</a></td>
		<td align="left"><a href="delete_os.php?idos=' . $row['idOperating_System'] . '&id=' . $id .'">Delete OS - This Product</a></td>
		<td align="left">' . $osoutput . '</td>
		<td align="left">' . $row['o_latestversion'] . '</td>
		<td align="left">' . $row['o_releasedate'] . '</td>';
            if($row['o_free'] == 0) {
                echo '<td align="left"> PAID </td>';
            }
            else {
                echo '<td align="left"> FREE </td>';
            }
            echo '
		<td align="left"><a href="product_os.php?id=' . $row['idOperating_System'] . '">View Products - OS</a></td>

	</tr>
	';
        }
        echo '</table>
            <p>
            </br>
            <a href="index.php">Home Page</a>
            <a href="view_products.php">View All Products</a>
            </p>
        </div></br>';
    } else{
        echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
        </p>';
    }

} else { // Not a valid movie ID.
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Product ID.</p><p><br /><br /></p>';
    echo '<p>
        <a href="index.php">Home Page</a>
        <a href="view_products.php">View All Products</a>
    </p>';
}
mysqli_close($dbc); // Close the database connection.

?>