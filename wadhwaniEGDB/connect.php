<?php
/**
 * Created by PhpStorm.
 * User: Gaurav
 * Date: 7/25/2015
 * Time: 11:25 PM
 */

DEFINE ('DB_USER', 'root');
DEFINE ('DB_PASSWORD', 'gaurav7890');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'electronics_db');

$dbc = @mysqli_connect (DB_HOST, DB_USER, DB_PASSWORD,
    DB_NAME) OR die ('Could not connect to MySQL: ' .
    mysqli_connect_error() );