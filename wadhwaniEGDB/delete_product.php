<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/16/2015
 * Time: 11:02 PM
 */

// This page deletes a movie.
// This page is accessed through view_movies.php.

$page_title = 'Delete a Product';

// Check for a valid movie ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_movies.php
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

include ('connect.php'); // Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

    if ($_POST['sure'] == 'Yes') { // Delete them.

        // Make the query.
        $query = "select p.p_name, p.p_releasedate, p.p_price, m.m_name, ps.s_name from products as p, manufacturer as m, product_subtype as ps where p.idManufacturer = m.idManufacturer AND p.idProduct_Subtype = ps.idProduct_Subtype AND p.idProducts = $id";
        $result = @mysqli_query ($dbc, $query); // Run the query.

        if (mysqli_num_rows($result) == 1) { // Valid movie ID, show the result.

            // Get the movie information.
            $row = mysqli_fetch_array ($result, MYSQL_NUM);

            $pname = $row[0];
            $preleasedate = $row[1];
            $pprice = $row[2];
            $pm_name = $row[3];
            $pps_name = $row[4];

            $query = "DELETE FROM products WHERE idProducts=$id";
            $result_del = @mysqli_query ($dbc, $query); // Run the query.
            if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.


                // Get the movie information.
//                $row = mysqli_fetch_array ($result, MYSQL_NUM);

                // Create the result page.
                echo '<h1 id="mainhead">Delete a Movie</h1>
		<p>The movie <b>'.$pname.'</b> from year <b>'.$pprice.'</b> has been deleted.</p><p><br /><br /></p>';
                echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
</p>';
            } else { // Did not run OK.
                echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The movie could not be deleted due to a system error.</p>'; // Public message.
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
</p>';
            }


        }

        else { // Not a valid movie ID.
            echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
            echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
</p>';
        } //End of else.

    } // End of $_POST['sure'] == 'Yes' if().
    else { // Wasn't sure about deleting the movie.
        echo '<h1 id="mainhead">Delete a Movie</h1>';

        $query = "select p.p_name, p.p_releasedate, p.p_price, m.m_name, ps.s_name from products as p, manufacturer as m, product_subtype as ps where p.idManufacturer = m.idManufacturer AND p.idProduct_Subtype = ps.idProduct_Subtype AND p.idProducts = $id";
        $result = @mysqli_query ($dbc, $query); // Run the query.

        if (mysqli_num_rows($result) == 1) { // Valid movie ID, show the result.

            // Get the movie information
            $row = mysqli_fetch_array ($result, MYSQL_NUM);
            $pname = $row[0];
            $preleasedate = $row[1];
            $pprice = $row[2];
            $pm_name = $row[3];
            $pps_name = $row[4];

            // Create the result page.
            echo'
		<p>The movie <b>'.$row[0].'</b> from year <b>'.$row[2].'</b> has NOT been deleted.</p><p><br /><br /></p>';
            echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
</p>';
        } else { // Not a valid movie ID.
            echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
            echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Produts</a>
</p>';
        }


    } // End of wasn?t sure else().

} // End of main submit conditional.

else { // Show the form.

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

        echo '<form action="delete_product.php" method="post">';
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
        echo '<p>

	<p>Are you sure you want to delete this product?<br />
	<input type="radio" name="sure" value="Yes" /> Yes
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	<a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a></p>
	</form>';
} //End of valid movie ID if().
    else { // Not a valid movie ID.
        echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Product ID.</p><p><br /><br /></p>';
        echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
</p>';
    }
}
mysqli_close($dbc); // Close the database connection.
?>