<?php
session_start();
require_once "db_connect.php";
require_once "upload_file.php";

if (isset($_SESSION["user"])) {
    header("Location: ../products.php");
}

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: regester.php");
}


$sql = "SELECT * FROM users WHERE id = {$_SESSION["adm"]}";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

$sqlUsers = "SELECT * FROM users WHERE status != 'adm'";
$resultUsers = mysqli_query($connect, $sqlUsers);
$layout = "";

if (mysqli_num_rows($resultUsers)) {
    while ($userRow = mysqli_fetch_assoc($resultUsers)) {
        $layout .= "<div class='card mb-3' style='max-width: 540px;'>
  <div class='row g-0'>
    <div class='col-md-4'>
      <img src='photos/{$userRow["picture"]}' class='img-fluid rounded-start' alt='...'>
    </div>
    <div class='col-md-8'>
      <div class='card-body'>
        <h5 class='card-title'><?={$userRow["first_name"]} {$userRow["last_name"]}?></h5>
        <p class='card-text'>{$userRow["email"]}</p>
        <p class='card-text'>{$userRow["date_of_birth"]}</p>
        <a href='update.php?id={$userRow["id"]}' class='btn btn-light'>Update</a> 
        <p class='card-text'><small class='text-body-secondary'>Last updated 3 mins ago</small></p>
      </div>
    </div>
  </div>
</div>";
    }
} else {
    $layout .= "No resulta found";
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
                        <a class="nav-link active" aria-current="page" href="products/index.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a href="update.php?id=<?= $row['id'] ?>" class="nav-link">Edit</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" name="logout" href="logout.php?logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</nav>
<h2 class="d-flex justify-content-center m-5">Hello, <?= $row["first_name"] . " " . $row["last_name"] ?>!</h2>
<div class="containre">
    <div class="row row-cols-lf-3 row-cols-md-2 row-cols-sm-1 row-cols-xs-1">
        <?= $layout ?>;
    </div>
</div>


</html>

<?= require_once "inc/footer.php"; ?>