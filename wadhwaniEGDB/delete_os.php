<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/20/2015
 * Time: 9:52 PM
 */

// This page deletes a OS for a Particular Product.
// This page is accessed through product_details.php.

$page_title = 'Delete a Product';

if ( (isset($_GET['id']))  && (is_numeric($_GET['id'])) && (isset($_GET['idos'])) && (isset($_GET['idos'])) )  {
    $id = $_GET['id'];
    $idos = $_GET['idos'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) && (isset($_POST['idos'])) && (is_numeric($_POST['idos']))  ) { // Form has been submitted.
    $id = $_POST['id'];
    $idos = $_POST['idos'];
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
        $query = "select o_name from operating_system WHERE idOperating_System = $idos";
        $result = @mysqli_query ($dbc, $query); // Run the query.

        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_array ($result, MYSQL_NUM);

            $oname = $row[0];

            $query = "DELETE FROM products_has_operating_system WHERE idProducts=$id AND idOperating_System = $idos";
            $result_del = @mysqli_query ($dbc, $query); // Run the query.
            if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
                // Create the result page.
                echo '<h1 id="mainhead">Success!</h1>
		              <p>The OS <b>'.$oname.'</b> has been deleted for this particular product.</p><p><br /><br /></p>';
                echo '<p>
                      <a href="index.php">Home Page</a>
                      <a href="view_products.php">View All Products</a>
                      </p>';
            } else { // Did not run OK.
                echo '<h1 id="mainhead">System Error</h1>
			          <p class="error">The os could not be deleted due to a system error.</p>'; // Public message.
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                echo '<p>
                      <a href="index.php">Home Page</a>
                      <a href="view_products.php">View All Products</a>
                      </p>';
            }
        }

        else {
            echo '<h1 id="mainhead">Page Error</h1>
		          <p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
            echo '<p>
                  <a href="index.php">Home Page</a>
                  <a href="view_products.php">View All Products</a>
                  </p>';
        } //End of else.

    } // End of $_POST['sure'] == 'Yes' if().

    else {
        echo '<h1 id="mainhead">Delete a OS for this product</h1>';

        $query = "select o_name from operating_system WHERE idOperating_System = $idos";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_array ($result, MYSQL_NUM);
            $oname = $row[0];

            // Create the result page.
            echo '<p>The <b>'.$row[0].'</b> OS has NOT been deleted for this particular product.</p><p><br /><br /></p>';
            echo '<p>
                  <a href="index.php">Home Page</a>
                  <a href="view_products.php">View All Products</a>
                  </p>';
        } else {
            echo '<h1 id="mainhead">Page Error</h1>
		         <p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
            echo '<p>
                  <a href="index.php">Home Page</a>
                  <a href="view_products.php">View All Products</a>
                  </p>';
        }


    }

} // End of main submit conditional.

else { // Show the form.

    $query = "select o_name, o_latestversion, o_releasedate, o_free from operating_system WHERE idOperating_System = $idos";
    $result = @mysqli_query ($dbc, $query); // Run the query.


    if (mysqli_num_rows($result) == 1) {
        echo '<form action="delete_os.php" method="post">';
        echo '<h2>OS Details</h2>';
        echo '<table align="left" cellspacing="0" cellpadding="5" border="True">
<tr>
	<td align="left"><b>Edit</b></td>
	<td align="left"><b>Operating System </b></td>
	<td align="left"><b>Latest Version</b></td>
	<td align="left"><b>Release Date</b></td>
	<td align="left"><b>Price(Free/Paid)</a></b></td>
</tr>
';
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);

// Fetch and print all the records.
        $bg = '#eeeeee'; // Set the background color.
        echo '<tr bgcolor="' . $bg . '">
		<td align="left"><a href="edit_os.php?id=' . $idos . '">Edit</a></td>
		<td align="left">' . $row['o_name'] . '</td>
		<td align="left">' . $row['o_latestversion'] . '</td>
		<td align="left">' . $row['o_releasedate'] . '</td>';
            if($row['o_free'] == 0) {
                echo '<td align="left"> PAID </td>';
            }
            else {
                echo '<td align="left"> FREE </td>';
            }

	echo'</tr>
	</table>';

	echo '</br></br></br></br><p>Are you sure you want to delete this OS from this Product?<br />
	<input type="radio" name="sure" value="Yes" /> Yes
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	<input type="hidden" name="idos" value="' . $idos . '" />
	<a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a></p>
	</form>';
    }
    else {
        echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Product ID.</p><p><br /><br /></p>';
        echo '<p>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
</p>';
    }
}
mysqli_close($dbc); // Close the database connection.
