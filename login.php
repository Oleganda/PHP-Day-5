<?php
session_start(); // start the session and use super global variable. In suv we keep info about user and admin 
//it prevents from loginin in seeral time. 
require_once "db_connect.php";
require_once "upload_file.php";
require_once "inc/navbar.php";

if (isset($_SESSION["adm"])) {
    header("Location: dashboard.php");
}

if (isset($_SESSION["user"])) {
    header("Location: login.php");
}

$email = $passwordError = $emailError = "";
$error = false;

function cleanInput($input)
{
    $data = trim($input);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);

    return $data;
}

if (isset($_POST["login"])) {
    $email = cleanInput($_POST["email"]);
    $password = $_POST["password"];

    //validation of password 
    if (empty($password)) {
        $error = true;
        $passwordError = "Please, enter your password";
    } elseif (strlen($password) < 5) {
        $error = true;
        $passwordError = "Password must be longer than 5 characters";
    }


    if (!$error) {
        $password = hash("sha256", $password);
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";   //write a query and took all info about the user
        $result = mysqli_query($connect, $sql);                                           //run query
        $row = mysqli_fetch_assoc($result);                                                //fetch data


        if (mysqli_num_rows($result) == 1) {
            if ($row["status"] == "user")  //to check who is it user or admin
            {
                $_SESSION["user"] = $row["id"];         //if the user, save id there  
                header("Location:products.php");
            } else {
                $_SESSION["adm"] = $row["id"];
                header("Location: dashboard.php");
            }
        } else {
            echo   "<div class='alert alert-danger'>
               <p>Something went wrong, please try again later ...</p>
           </div>";
        }
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
    <form method="POST" enctype="multipart/form-data">
        <div class=" mb-3">
            <label for="exampleFormControlInput1" class="form-label">E-mail</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Your email" name="email" value="<?= $email ?>">
            <span class=" text-danger"> <?= $emailError ?> </span>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="Create a new password" name="password">
            <span class="text-danger"> <?= $passwordError ?> </span>
        </div>
        <div class="d-flex justify-content-center m-5 "><button name="login" type="submit" class="btn btn-light">Log in</button></div>
        <div class="d-flex justify-content-center m-2 ">If you dont have an account</div>
        <div class="d-flex justify-content-center m-4"><a class="btn btn-light" href="regester.php">Regester Now</a></div>
    </form>



    <?= require_once "inc/footer.php"; ?>