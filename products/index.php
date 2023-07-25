<?php include '../inc/navbar-2.php' ?>
<?php require_once "../db_connect.php";
require_once "../upload_file.php";

session_start();



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
             <div class='d-flex justify-content-center'><a href='details.php?x={$row["Id"]}' class='btn btn-warning'>Details</a> <a href='update.php?x={$row["Id"]}' class='btn btn-warning ms-1'>Update Info</a></div>
        </div>
    </div>";
    }
} else {
    $layout .= "<div class ='m-3 d-flex justify-content-center' >You don't have any items. Let's add your first product!</div>";
}

?>


<div class="d-flex justify-content-around row row-lg-3 row-md-2 row-xs-1 p-2 g-col-6 p-2 g-col-6">
    <div class="d-flex justify-content-center"><a href="create_product.php" class="btn btn-warning m-5">Add New Product</a></div>
    <?= $layout ?>
</div>

<?php include '../inc/footer.php' ?>