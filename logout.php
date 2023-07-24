<?php

session_start();
require_once "db_connect.php";
require_once "upload_file.php";
require_once "inc/navbar.php";

if (isset($_GET["logout"])) {
    unset($_SESSION["user"]);
    unset($_SESSION["adm"]);

    session_unset();
    session_destroy();

    header("Location: login.php");
}
