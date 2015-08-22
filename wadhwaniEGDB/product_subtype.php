<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/15/2015
 * Time: 11:27 PM
 */

$page_title = 'Products for this Sub Type';

// Page header.
echo '<h1 id="mainhead">Products of this SubType in the Database:</h1>';
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
    <a href="view_product_subtype.php">View All Product Subtype</a>
</p>';
    exit();
}
include ('connect.php');

// Number of records to show per page:
$display = 5;

// Determine how many pages there are.
if (isset($_GET['np'])) { // Already been determined.
    $num_pages = $_GET['np'];
} else { // Need to determine.

    // Count the number of records
    $query = "select COUNT(*) FROM products WHERE idProduct_Subtype = $id";
    $result = @mysqli_query ($dbc, $query);
    $row = mysqli_fetch_array ($result, MYSQL_NUM);
    $num_records = $row[0];
    // Calculate the number of pages.
    if ($num_records > $display) { // More than 1 page.
        $num_pages = ceil ($num_records/$display);
    } else {
        $num_pages = 1;
    }

} // End of np IF.


// Determine where in the database to start returning results.
if (isset($_GET['s'])) {
    $start = $_GET['s'];
} else {
    $start = 0;
}

// Default column links.
$link1 = "{$_SERVER['PHP_SELF']}?id=$id&sort=p_d";
$link2 = "{$_SERVER['PHP_SELF']}?sort=rd_a&id=$id";
$link3 = "{$_SERVER['PHP_SELF']}?sort=pr_a&id=$id";
$link4 = "{$_SERVER['PHP_SELF']}?sort=m_a&id=$id";
$link5 = "{$_SERVER['PHP_SELF']}?sort=s_a&id=$id";

// Determine the sorting order.
if (isset($_GET['sort'])) {

    // Use existing sorting order.
    switch ($_GET['sort']) {
        case 'p_a':
            $order_by = 'p_name ASC';
            $link1 = "{$_SERVER['PHP_SELF']}?sort=p_d&id=$id";
            break;
        case 'p_d':
            $order_by = 'p_name DESC';
            $link1 = "{$_SERVER['PHP_SELF']}?sort=p_a&id=$id";
            break;
        case 'rd_a':
            $order_by = 'p_releasedate ASC';
            $link2 = "{$_SERVER['PHP_SELF']}?sort=rd_d&id=$id";
            break;
        case 'rd_d':
            $order_by = 'p_releasedate DESC';
            $link2 = "{$_SERVER['PHP_SELF']}?sort=rd_a&id=$id";
            break;
        case 'pr_a':
            $order_by = 'p_price ASC';
            $link3 = "{$_SERVER['PHP_SELF']}?sort=pr_d&id=$id";
            break;
        case 'pr_d':
            $order_by = 'p_price DESC';
            $link3 = "{$_SERVER['PHP_SELF']}?sort=pr_a&id=$id";
            break;
        case 'm_a':
            $order_by = 'm_name ASC';
            $link4 = "{$_SERVER['PHP_SELF']}?sort=m_d&id=$id";
            break;
        case 'm_d':
            $order_by = 'm_name DESC';
            $link4 = "{$_SERVER['PHP_SELF']}?sort=m_a&id=$id";
            break;
        case 's_a':
            $order_by = 's_name ASC';
            $link5 = "{$_SERVER['PHP_SELF']}?sort=s_d&id=$id";
            break;
        case 's_d':
            $order_by = 's_name DESC';
            $link5 = "{$_SERVER['PHP_SELF']}?sort=s_a&id=$id";
            break;
        default:
            $order_by = 'p_name ASC';
            break;
    }

    // $sort will be appended to the pagination links.
    $sort = $_GET['sort'];

} else { // Use the default sorting order.
    $order_by = 'p_name ASC';
    $sort = 'p_a';
}

// Make the query.
$query = "select p.idProducts, p.p_name, p.p_releasedate, p.p_price, m.m_name, ps.s_name from products as p, manufacturer as m, product_subtype as ps where p.idManufacturer = m.idManufacturer AND p.idProduct_Subtype = ps.idProduct_Subtype AND p.idProduct_Subtype = '$id' ORDER BY $order_by LIMIT $start, $display";
$result = @mysqli_query ($dbc, $query); // Run the query.
if (mysqli_num_rows($result) > 1) {
    echo "Ordered by $order_by";
    echo '<table align="center" cellspacing="0" cellpadding="5" border="True">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Delete</b></td>
	<td align="left"><b><a href="' . $link1 . '">Product </a></b></td>
	<td align="left"><b><a href="' . $link2 . '">Release Date</a></b></td>
	<td align="left"><b><a href="' . $link3 . '">Price in $</a></b></td>
	<td align="left"><b><a href="' . $link4 . '">Manufacturer</a></b></td>
	<td align="left"><b><a href="' . $link5 . '">Subtype</a></b></td>
	<td align="left"><b>Details</b></td>
	<td align="left"><b>Images</b></td>
</tr>
';


// Fetch and print all the records.
    $bg = '#eeeeee'; // Set the background color.
    while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
        $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
        echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_product.php?id=' . $row['idProducts'] . '">Edit</a></td>
		<td align="left"><a href="delete_product.php?id=' . $row['idProducts'] . '">Delete</a></td>
		<td align="left">' . $row['p_name'] . '</td>
		<td align="left">' . $row['p_releasedate'] . '</td>
		<td align="left">' . $row['p_price'] . '</td>
		<td align="left">' . $row['m_name'] . '</td>
		<td align="left">' . $row['s_name'] . '</td>
		<td align="left"><a href="product_details.php?id=' . $row['idProducts'] . '">View Details</a></td>
        <td align="left"><a href="product_images.php?id=' . $row['idProducts'] . '" target="_blank">View Image</a></td>
	</tr>
	';
    }

    echo '</table>';

    mysqli_free_result ($result); // Free up the resources.

    mysqli_close($dbc); // Close the database connection.
} else {
    echo '<h2>No Products are listed for this SubType</h2>';
}


// Make the links to other pages, if necessary.
if ($num_pages > 1) {

    echo '<br /><p>';
    // Determine what page the script is on.
    $current_page = ($start/$display) + 1;

    // If it's not the first page, make a First button and a Previous button.
    if ($current_page != 1) {
        echo '<a href="product_subtype.php?s=0&np=' . $num_pages . '&sort=' . $sort . '&id=' .$id .'">First</a> ';
        echo '<a href="product_subtype.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'&id=' .$id .'">Previous</a> ';
    }

    // Make all the numbered pages.
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="product_subtype.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort .'&id=' .$id .'">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    }

    // If it's not the last page, make a Last button and a Next button.
    if ($current_page != $num_pages) {
        echo '<a href="product_subtype.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort .'&id=' .$id .'">Next</a> ';
        echo '<a href="product_subtype.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&sort=' . $sort .'&id=' .$id .'">Last</a>';
    }

    echo '</p>';

} // End of links section.
echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
    <a href="view_product_subtype.php">View All Product Subtype</a>
</p>';
?>


