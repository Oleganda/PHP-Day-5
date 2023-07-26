<?php include '../inc/navbar-2.php' ?>
<?php require_once "../db_connect.php";
require_once "../upload_file.php";

session_start();

if (isset($_SESSION["user"])) {
    header("Location: ../products.php");
}

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: ../login.php");
}

$result = mysqli_query($connect, "SELECT * FROM suppliers");

$options = "";

while ($row = mysqli_fetch_assoc($result)) {
    $options .= "<option value='{$row["supplierId"]}'>{$row["sup_name"]}</option>";
}

if (isset($_POST["add"])) {
    $supplier = isset($_POST["supplier"]) ? $_POST["supplier"] : null;

    $Name = $_POST["Name"];
    $Description = $_POST["Description"];
    $Material = $_POST["Material"];
    $Price = $_POST["Price"];
    $Img = uploadFile($_FILES["Img"], "product");

    $sql = "INSERT INTO `products`(`Name`, `Material`, `Price`, `Img`, `Description`, `fk_supplierId`) VALUES ('$Name','$Material',$Price,'$Img[0]','$Description', $supplier)";


    if (mysqli_query($connect, $sql)) {
        echo "<div class=alert alert-info role=alert>
 Your product has been added!
</div>";
        header("refresh: 3 url = index.php");
    } else {

        "<div class='alert alert-danger' role='alert'> A simple danger alert—check it out!
</div>";
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nice Bags</title>
</head>

<body>
    <div class='container mt-5'>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Name" name="Name">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Picture</label>
                <input type="file" class="form-control" id="exampleFormControlInput1" placeholder="Upload picture here" name="Img">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Material</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="It is made from...." name="Material">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="Description"></textarea>
                <label for="exampleFormControlInput1" class="form-label">Price</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="It costs....." name="Price">
            </div>
            <label for="exampleFormControlTextarea1" class="form-label">Supplier</label>
            <div class="mb-3">
                <select name="supplier" class="form-control">
                    <option value="null">Please select a Supplier</option>
                    <?= $options ?>
                </select>
            </div>
            <div class="d-flex justify-content-center m-5 "><button name="add" type="submit" class="btn btn-warning">Add Product</button></div>

        </form>
    </div>
</body>

</html>

<?php include '../inc/footer.php' ?>