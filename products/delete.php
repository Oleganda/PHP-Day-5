<?php
require_once "../db_connect.php";

$id = $_GET["x"];    //take an if from URL

$sql = "DELETE FROM products WHERE id = $id";   //query to delete a record
if (mysqli_query($connect, $sql))             //if connection works

{
    header("location: index.php");           //it redirects to the main page and item will be deleted 

} else {
    echo "Error";                               // it shows Error, if something doesnt work
}
