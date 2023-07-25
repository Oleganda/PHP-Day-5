<?php include '../inc/navbar-2.php' ?>
<?php require_once "../db_connect.php";
require_once "../upload_file.php";

session_start();

// if (isset($_SESSION["user"])) {
//     header("Location: update.php");
// }

if (!isset($_SESSION["user"]) && !isset($_SESSION["adm"])) {
    header("Location: ../regester.php");
}

$id = $_GET["x"];
$sql = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($connect, $sql);
$row = mysqli_fetch_assoc($result);

$resultSuppliers = mysqli_query($connect, "SELECT * FROM suppliers");
$options = "";
while ($supplierRow = mysqli_fetch_assoc($resultSuppliers)) {
    if ($row["fk_supplierId"] == $supplierRow["supplierId"]) {
        $options = "<option selected value= '{$supplierRow["supplierID"]}'> {$supplierRow["sup_name"]} </option>";
    } else {

        $options = "<option value= '{$supplierRow["supplierID"]}'> {$supplierRow["sup_name"]} </option>";
    }
}


if (isset($_POST["update"])) {
    $Name = $_POST["Name"];
    $Description = $_POST["Description"];
    $supplier = $_POST["supplier"];
    $Material = $_POST["Material"];
    $Price = $_POST["Price"];
    $Img = uploadFile($_FILES["Img"], "product");

    if ($_FILES["Img"]["error"] == 0) {
        if ($row["Img"] != "dummy.jpg") {
            unlink("photos/$row[Img]");
        }

        $sql = "UPDATE `products` SET `Name`='$Name',`Material`='$Material',`Price`= `$Price`,`Img`='$Img[0]',`Description`='$Description', `fk_supplierId`='$supplier' WHERE id = $id";
    } else {
        $sql = "UPDATE `products` SET `Name`='$Name', `Description`='$Description', `Price`= $Price, `fk_supplierId`='$supplier' WHERE id = $id";
    }
    if (mysqli_query($connect, $sql)) {
        echo "<div class='alert alert-success' role='alert'>
        product has been updated, {$Img[1]}</div>";
        header("refresh:3; url=index.php");
    } else {
        echo "<div class='alert alert-danger' role='alert'>
        error found, {$Img[1]}
      </div>";
    }
}





?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bags</title>
</head>

<body>
    <div class='container mt-5'>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Name" name="Name" value="<?= $row["Name"] ?>">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Picture</label>
                <input type="file" class="form-control" id="exampleFormControlInput1" placeholder="Upload picture here" name="Img">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Material</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="It is made from...." name="Material" value="<?= $row["Material"] ?>">
            </div>
            <div class=" mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                <input class="form-control" id="exampleFormControlTextarea1" rows="3" name="Description" value="<?= $row['Description'] ?>">
                <label for=" exampleFormControlInput1" class="form-label">Price</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="It costs....." name="Price" value="<?= $row["Price"] ?>">
            </div>
            <div class="mb-3">
                <select name="supplier" class="form-control">
                    <option value="null">Please select a Supplier</option>
                    <?= $options ?>
                </select>
            </div>
            <div class=" d-flex justify-content-center m-5 "><button name="update" type="submit" class="btn btn-warning">Update</button>
            </div>



        </form>
    </div>
</body>

</html>