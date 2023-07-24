<?php

session_start();
require_once "db_connect.php";
require_once "upload_file.php";
require_once "inc/navbar.php";

$id = $_GET["id"]; //value of id from url 
$sql = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

$backBtn = "products.php";

if (isset($_SESSION["adm"])) {
    $backBtn = "daschboard.php";
}

if (isset($_POST["update"])) {
    $first_name = ($_POST["first_name"]);
    $last_name = ($_POST["last_name"]);
    $email = ($_POST["email"]);
    $password = ($_POST["password"]);
    $date_of_birth = ($_POST["date_of_birth"]);
    $picture = ($_FILES["picture"]);
    //checking if picture already excists 
    if ($_FILES["picture"]["error"] == 0) {
        if ($row["picture"] != "profile.png") {
            unlink("pictures/$row[picture]");
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