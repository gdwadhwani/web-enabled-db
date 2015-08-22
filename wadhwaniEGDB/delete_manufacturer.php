<?php

$page_title = 'Delete a Manufacturer';

if ( (isset($_GET['id']))  && (is_numeric($_GET['id']))  )  {
    $id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id']))  ) { // Form has been submitted.
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
        $query = "select * from manufacturer WHERE idManufacturer = $id";
        $result = @mysqli_query ($dbc, $query); // Run the query.

        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_array ($result, MYSQL_NUM);

            $mname = $row[1];

            $query = "DELETE FROM manufacturer WHERE idManufacturer=$id";
            $result_del = @mysqli_query ($dbc, $query); // Run the query.
            if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
                // Create the result page.
                echo '<h1 id="mainhead">Success!</h1>
		              <p>The Manufacturer <b>'.$mname.'</b> has been deleted</p><p><br /></p>';
                echo '<p>
                      <a href="index.php">Home Page</a>
                      <a href="view_products.php">View All Products</a>
                      <a href="view_manufacturer.php">View All Manufacturer</a>
                      </p>';
            } else { // Did not run OK.
                echo '<h1 id="mainhead">System Error</h1>
			          <p class="error">The manufacturer could not be deleted due to a system error.</p>'; // Public message.
                echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                echo '<p>
                      <a href="index.php">Home Page</a>
                      <a href="view_products.php">View All Products</a>
                      <a href="view_manufacturer.php">View All Manufacturer</a>
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
        echo '<h1 id="mainhead">Delete a manufacturer</h1>';

        $query = "select * from manufacturer WHERE idManufacturer = $id";
        $result = @mysqli_query ($dbc, $query); // Run the query.
        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_array ($result, MYSQL_NUM);
            $mname = $row[1];

            // Create the result page.
            echo '<p>Manufacturer <b>'.$row[1].'</b>  has NOT been deleted </p><p><br /></p>';
            echo '<p>
                  <a href="index.php">Home Page</a>
                  <a href="view_products.php">View All Products</a>
                  <a href="view_manufacturer.php">View All Manufacturer</a>
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

    $query = "select * from manufacturer WHERE idManufacturer = $id";
    $result = @mysqli_query ($dbc, $query); // Run the query.

    if (mysqli_num_rows($result) == 1) {
        echo '<form action="delete_manufacturer.php" method="post">';
        $row = mysqli_fetch_array($result, MYSQL_ASSOC);
        echo '<h2>Delete Manufacturer:';
        echo $row['m_name'];
        echo '</h2>';

        echo '</br><p>Are you sure you want to delete this Manufacturer ';
        echo '
	<input type="radio" name="sure" value="Yes" /> Yes
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	<a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
    <a href="view_manufacturer.php">View All Manufacturers</a>
	</form>';
    }
    else {
        echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Manufacturer ID.</p><p><br /><br /></p>';
        echo '<>
    <a href="index.php">Home Page</a>
    <a href="view_products.php">View All Products</a>
    <a href="view_manufacturer.php">View All Manufacturers</a>
</p>';
    }
}
mysqli_close($dbc); // Close the database connection.