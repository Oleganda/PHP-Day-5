<?php require_once "../db_connect.php";
require_once "../upload_file.php";

session_start();

if (isset($_SESSION["user"])) {
    header("Location: ../products.php");
}

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: ../regester.php");
}

$sql = "SELECT * FROM `products`";
$result = mysqli_query($connect, $sql);
$layout = "";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $layout .= " <div class='card p-0' style='width: 300px; 'height = 70px'>
        <img src='../photos/{$row["Img"]}' class='card-img-top' alt='...'>
        <div class='card-body'>
            <h5 class='card-title'>{$row["Name"]}</h5>
            <p class='card-text'> {$row["Description"]}</p>
            <p class='card-text'> {$row["Material"]}</p>
             <p class='card-text'> {$row["Price"]} €</p> 
             <div class='d-flex justify-content-center'><a href='details.php?x={$row["Id"]}' class='btn btn-warning'>Details</a> <a href='update.php?x={$row["Id"]}' class='btn btn-warning ms-1'>Update</a> <a href='delete.php?x={$row["Id"]}' class='btn btn-warning ms-1'>Delete</a></div>
        </div>
    </div>";
    }
} else {
    $layout .= "<div class ='m-3 d-flex justify-content-center' >You don't have any items. Let's add your first product!</div>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar " style="background-color: white;">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="../login.php">Log in</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link" href="../products.php">Users List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" name="logout" href="../logout.php?logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </nav>
    <!-- form -->
    <div class='container mt-5'>

        <div class="d-flex justify-content-around row row-lg-3 row-md-2 row-xs-1 p-2 g-col-6 p-2 g-col-6">
            <div class="d-flex justify-content-center"><a href="create_product.php" class="btn btn-warning m-5">Add New Product</a></div>
            <?= $layout ?>
        </div>

        <?php include '../inc/footer.php' ?>