<?php
session_start();
require_once "db_connect.php";
require_once "upload_file.php";

if (isset($_SESSION["adm"])) {
    header("Location: dashboard.php");
}

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: regester.php");
}

$sql = "SELECT * FROM users WHERE id = {$_SESSION["user"]}";    //query about the user who loged in
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);


$sqlProducts = "SELECT * FROM products";
$resultProducts = mysqli_query($connect, $sqlProducts);

$layout = "";

if (mysqli_num_rows($resultProducts) > 0) {
    while ($rowProduct = mysqli_fetch_assoc(($resultProducts))) {

        $layout .= "
      
        <div class='card p-0 ' style='width: 300px; 'height = 70px'>
        <img src='photos/{$rowProduct["Img"]}' class='card-img-top' alt='...'>
        <div class='card-body'>
            <h5 class='card-title'>{$rowProduct["Name"]}</h5>
            <p class='card-text'> {$rowProduct["Description"]}</p>
            <p class='card-text'> {$rowProduct["Material"]}</p>
             <p class='card-text'> {$rowProduct["Price"]} €</p> 
             <div class='d-flex justify-content-center'><a href='products/details.php?x={$rowProduct["Id"]}' class='btn btn-warning'>Details</a></div>
        </div>
        </div>";
    }
} else {
    $layout .= "No results found!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar" style="background-color: white;">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a><img src="photos/<?= $row["picture"] ?>" class="img-fluid rounded-start" alt="..." width="50px"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="products.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" name="logout" href="logout.php?logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </nav>

    <h2 class="d-flex justify-content-center m-5">Hello <?= $row["first_name"] ?>!</h2>


    <div class='container mt-5'>

        <div class="d-flex justify-content-between row row-lg-3 row-md-2 row-xs-1 p-2 g-col-6 p-2 g-col-6">

            <?= $layout ?>
        </div>
    </div>

    <?php include 'inc/footer.php' ?>