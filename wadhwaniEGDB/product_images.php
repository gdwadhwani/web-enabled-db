<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 8/19/2015
 * Time: 5:12 AM
 */
include('connect.php');
$id = $_GET['id'];
$query = "select p_image from products where idProducts= $id";
    $result = @mysqli_query ($dbc, $query); // Run the query.
    $imageData = mysqli_fetch_array($result, MYSQLI_ASSOC);
if($imageData['p_image'] != NULL){
    header("Content-type: image/jpeg");
    echo $imageData['p_image'];
}
else {
    print "fck";
    echo "No Image to Display";
}

?>

