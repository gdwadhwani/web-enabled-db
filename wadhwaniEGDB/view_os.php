<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/15/2015
 * Time: 11:10 PM
 */

$page_title = 'View Operating Systems';

// Page header.
echo '<h1 id="mainhead">Operating Systems currently in the Database:</h1>';

include ('connect.php');

// Number of records to show per page:
$display = 5;

// Determine how many pages there are.
if (isset($_GET['np'])) { // Already been determined.
    $num_pages = $_GET['np'];
} else { // Need to determine.

    // Count the number of records
    $query = "select COUNT(*) from operating_system";
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
$link1 = "{$_SERVER['PHP_SELF']}?sort=n_d";
$link2 = "{$_SERVER['PHP_SELF']}?sort=rd_a";
$link3 = "{$_SERVER['PHP_SELF']}?sort=pr_a";

// Determine the sorting order.
if (isset($_GET['sort'])) {

    // Use existing sorting order.
    switch ($_GET['sort']) {
        case 'n_a':
            $order_by = 'o_name ASC';
            $link1 = "{$_SERVER['PHP_SELF']}?sort=n_d";
            break;
        case 'n_d':
            $order_by = 'o_name DESC';
            $link1 = "{$_SERVER['PHP_SELF']}?sort=n_a";
            break;
        case 'rd_a':
            $order_by = 'o_releasedate ASC';
            $link2 = "{$_SERVER['PHP_SELF']}?sort=rd_d";
            break;
        case 'rd_d':
            $order_by = 'o_releasedate DESC';
            $link2 = "{$_SERVER['PHP_SELF']}?sort=rd_a";
            break;
        case 'pr_a':
            $order_by = 'o_free ASC';
            $link3 = "{$_SERVER['PHP_SELF']}?sort=pr_d";
            break;
        case 'pr_d':
            $order_by = 'o_free DESC';
            $link3 = "{$_SERVER['PHP_SELF']}?sort=pr_a";
            break;
        default:
            $order_by = 'o_name ASC';
            break;
    }

    // $sort will be appended to the pagination links.
    $sort = $_GET['sort'];

} else { // Use the default sorting order.
    $order_by = 'o_name ASC';
    $sort = 'n_a';
}

// Make the query.
$query = "select idOperating_System, o_name, o_latestversion, o_releasedate, o_free from operating_system ORDER BY $order_by LIMIT $start, $display";
$result = @mysqli_query ($dbc, $query); // Run the query.

// Table header.
echo "Ordered by $order_by";
echo '<table align="center" cellspacing="0" cellpadding="5" border="True">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b><a href="' . $link1 . '">Operating System </a></b></td>
	<td align="left"><b>Latest Version</b></td>
	<td align="left"><b><a href="' . $link2 . '">Release Date</a></b></td>
	<td align="left"><b><a href="' . $link3 . '">Price(Free/Paid)</a></b></td>
	<td align="left"><b>Product Using OS</b></td>
</tr>
';


// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
    echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_os.php?id=' . $row['idOperating_System'] . '">Edit</a></td>
		<td align="left">' . $row['o_name'] . '</td>
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

echo '</table>';

mysqli_free_result ($result); // Free up the resources.

mysqli_close($dbc); // Close the database connection.

// Make the links to other pages, if necessary.
if ($num_pages > 1) {

    echo '<br /><p>';
    // Determine what page the script is on.
    $current_page = ($start/$display) + 1;

    // If it's not the first page, make a First button and a Previous button.
    if ($current_page != 1) {
        echo '<a href="view_os.php?s=0&np=' . $num_pages . '&sort=' . $sort .'">First</a> ';
        echo '<a href="view_os.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
    }

    // Make all the numbered pages.
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="view_os.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort .'">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    }

    // If it's not the last page, make a Last button and a Next button.
    if ($current_page != $num_pages) {
        echo '<a href="view_os.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Next</a> ';
        echo '<a href="view_os.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Last</a>';
    }

    echo '</p>';

} // End of links section.
echo '<p>
    <a href="index.php">Home Page</a>
    <a href="add_os.php">Add OS</a>
    <a href="view_products.php">View All Products</a>
    </p>';

?>


