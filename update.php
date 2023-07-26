<?php

session_start();
require_once "db_connect.php";
require_once "upload_file.php";

$id = $_GET["id"]; //value of id from url 
$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

$backBtn = "products.php";

if (isset($_SESSION["adm"])) {
    $backBtn = "dashboard.php";
}

if (isset($_POST["update"])) {
    $first_name = ($_POST["first_name"]);
    $last_name = ($_POST["last_name"]);
    $email = ($_POST["email"]);
    // $password = ($_POST["password"]);
    $date_of_birth = ($_POST["date_of_birth"]);
    $picture = uploadFile($_FILES["picture"]);
    //checking if picture already excists 
    if ($_FILES["picture"]["error"] == 0) {
        if ($row["picture"] != "profile.png") {
            unlink("photos/$row[picture]");
        }
        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', picture = '$picture[0]', date_of_birth = '$date_of_birth', email = '$email' WHERE id = {$id}";
    } else {
        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', date_of_birth = '$date_of_birth', email = '$email' WHERE id = {$id}";
    }

    if (mysqli_query($connect, $sql)) {
        echo  "<div class='alert alert-success' role='alert'>
       Profile has been updated {$picture[1]}                            
     </div>";
        header("refresh: 2; url=$backBtn");
    } else {
        echo   "<div class='alert alert-danger' role='alert'>
       Error was found {$picture[1]}
     </div>";
    }
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
                <a class="navbar-brand" href="dashboard.php">Home</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">

                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Products List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Users List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" name="logout" href="../logout.php?logout">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </nav>

    <div class='container mt-5'>


        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">First Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Name" name="first_name" value="<?= $row["first_name"] ?>">

            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Name" id="last_name" name="last_name" value="<?= $row["last_name"] ?>">

            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Picture</label>
                <input type="file" class="form-control" id="exampleFormControlInput1" placeholder="Upload your photo here" id="picture" name="picture">

            </div>
            <div class=" mb-3">
                <label for="exampleFormControlInput1" class="form-label">E-mail</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Your email" name="email" value="<?= $row["email"] ?>">

            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="exampleFormControlInput1" placeholder="yyyy-mm-dd" id="date_of_birth" name="date_of_birth" value="<?= $row["date_of_birth"] ?>">

            </div>

            <div class="d-flex justify-content-center m-5 "><button name="update" type="update" class="btn btn-light">Update</button> <a href="products.php" name="back " type="back" class="btn btn-light">Back</a>
            </div>
        </form>



        <?= require_once "inc/footer.php"; ?>