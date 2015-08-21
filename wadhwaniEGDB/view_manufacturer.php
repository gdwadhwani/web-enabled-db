<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/19/2015
 * Time: 7:45 PM
 */

$page_title = 'View Manufacturer';

// Page header.
echo '<h1 id="mainhead">Manufacturers currently in the Database:</h1>';

include ('connect.php');

// Number of records to show per page:
$display = 5;

// Determine how many pages there are.
if (isset($_GET['np'])) { // Already been determined.
    $num_pages = $_GET['np'];
} else { // Need to determine.

    // Count the number of records
    $query = "select COUNT(*) from manufacturer";
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
$link1 = "{$_SERVER['PHP_SELF']}?sort=m_d";
$link2 = "{$_SERVER['PHP_SELF']}?sort=mc_a";
$link3 = "{$_SERVER['PHP_SELF']}?sort=ms_a";
$link4 = "{$_SERVER['PHP_SELF']}?sort=mco_a";

// Determine the sorting order.
if (isset($_GET['sort'])) {

    // Use existing sorting order.
    switch ($_GET['sort']) {
        case 'm_a':
            $order_by = 'm_name ASC';
            $link1 = "{$_SERVER['PHP_SELF']}?sort=m_d";
            break;
        case 'm_d':
            $order_by = 'm_name DESC';
            $link1 = "{$_SERVER['PHP_SELF']}?sort=m_a";
            break;
        case 'mc_a':
            $order_by = 'm_headquarter ASC';
            $link2 = "{$_SERVER['PHP_SELF']}?sort=mc_d";
            break;
        case 'mc_d':
            $order_by = 'm_headquarter DESC';
            $link2 = "{$_SERVER['PHP_SELF']}?sort=mc_a";
            break;
        case 'ms_a':
            $order_by = 'm_headquarterState ASC';
            $link3 = "{$_SERVER['PHP_SELF']}?sort=ms_d";
            break;
        case 'ms_d':
            $order_by = 'm_headquarterState DESC';
            $link3 = "{$_SERVER['PHP_SELF']}?sort=ms_a";
            break;
        case 'mco_a':
            $order_by = 'm_headquarterCountry ASC';
            $link3 = "{$_SERVER['PHP_SELF']}?sort=mco_d";
            break;
        case 'mco_d':
            $order_by = 'm_headquarterState DESC';
            $link3 = "{$_SERVER['PHP_SELF']}?sort=mco_a";
            break;
        default:
            $order_by = 'm_name ASC';
            break;
    }

    // $sort will be appended to the pagination links.
    $sort = $_GET['sort'];

} else { // Use the default sorting order.
    $order_by = 'm_name ASC';
    $sort = 'm_a';
}

// Make the query.
    $query = "select idManufacturer, m_name, m_headquarter, m_headquarterState, m_headquarterCountry from manufacturer ORDER BY $order_by LIMIT $start, $display";
$result = @mysqli_query ($dbc, $query); // Run the query.

// Table header.
echo "Ordered by $order_by";
echo '<table align="center" cellspacing="0" cellpadding="5" border="True">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b><a href="' . $link1 . '">Manufacturer Name </a></b></td>
	<td align="left"><b><a href="' . $link2 . '">Manufacturer HeadQuarter City</b></td>
	<td align="left"><b><a href="' . $link3 . '">Manufacturer HeadQuarter State</a></b></td>
	<td align="left"><b><a href="' . $link4 . '">Manufacturer HeadQuarter Country</a></b></td>
	<td align="left"><b>Products From This Manufacturer</b></td>
</tr>
';


// Fetch and print all the records.
$bg = '#eeeeee'; // Set the background color.
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
    $bg = ($bg=='#eeeeee' ? '#ffffff' : '#eeeeee'); // Switch the background color.
    echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_manufacturer.php?id=' . $row['idManufacturer'] . '">Edit</a></td>
		<td align="left">' . $row['m_name'] . '</td>
		<td align="left">' . $row['m_headquarter'] . '</td>
		<td align="left">' . $row['m_headquarterState'] . '</td>
        <td align="left">' . $row['m_headquarterCountry'] . '</td>';
    echo '
		<td align="left"><a href="product_manufacturer.php?id=' . $row['idManufacturer'] . '">View Products - Manufacturer</a></td>

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
        echo '<a href="view_manufacturer.php?s=0&np=' . $num_pages . '&sort=' . $sort .'">First</a> ';
        echo '<a href="view_manufacturer.php?s=' . ($start - $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Previous</a> ';
    }

    // Make all the numbered pages.
    for ($i = 1; $i <= $num_pages; $i++) {
        if ($i != $current_page) {
            echo '<a href="view_manufacturer.php?s=' . (($display * ($i - 1))) . '&np=' . $num_pages . '&sort=' . $sort .'">' . $i . '</a> ';
        } else {
            echo $i . ' ';
        }
    }

    // If it's not the last page, make a Last button and a Next button.
    if ($current_page != $num_pages) {
        echo '<a href="view_manufacturer.php?s=' . ($start + $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Next</a> ';
        echo '<a href="view_manufacturer.php?s=' . (($num_pages-1) * $display) . '&np=' . $num_pages . '&sort=' . $sort .'">Last</a>';
    }

    echo '</p>';
} // End of links section.
echo '<p>
    <a href="index.php">Home Page</a>
    <a href="add_manufacturer.php">Add Manufacturer</a>
</p>';

?>


