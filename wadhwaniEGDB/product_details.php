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
    } else {
        $noos = "NO OS";
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
    foreach($osname as $osoutput) {
        echo $osoutput . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
echo '</b></p>';

} else { // Not a valid movie ID.
    echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Product ID.</p><p><br /><br /></p>';
}
mysqli_close($dbc); // Close the database connection.

?>