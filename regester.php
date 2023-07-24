<?php require_once "db_connect.php";
require_once "upload_file.php";
require_once "inc/navbar.php";

session_start();
if (isset($_SESSION["adm"])) {
    header("Location: dashboard.php");
}

if (isset($_SESSION["user"])) {
    header("Location: login.php");
}

$error = false; //if error == true, user/admin put not correct info

function cleanInput($input)
{
    $data = trim($input); //remove extra spaces, tabs, newlines out of the string. ex: user put extra spaces in name line
    $data = strip_tags($data); //removing HTML tags from the string, dont use if need original string
    $data = htmlspecialchars($data); //convertingHTML special charachters. ex: öüä 

    return $data;
}

$first_name = $last_name = $email = $date_of_birth = "";  //define variables and set them to empty string
$first_nameError = $last_nameError = $emailError = $date_of_birthError = $passwordError = ""; // in case of error, will be empty string


if (isset($_POST["submit"])) {                            //when button subit is clicked, we apply function to values, password could not 
    $first_name = cleanInput($_POST["first_name"]);      //be used with cleanInput function because user could use special characters in it
    $last_name = cleanInput($_POST["last_name"]);
    $email = cleanInput($_POST["email"]);
    $password = ($_POST["password"]);
    $date_of_birth = cleanInput($_POST["date_of_birth"]);
    $picture = uploadFile($_FILES["picture"]);

    //validate if first name is correct 
    if (empty($first_name)) {       //if it is empty, we have to ask to put a value 
        $error == true;
        $first_nameError = "Please, enter your first name";
    } elseif (strlen($first_name) < 2) {     //if first name is too short 
        $error == true;
        $first_nameError = "First name can not be shorter than 2 characters";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $first_name))    //to check if name contains special characters, which we dont want to accept
    {
        $error == true;
        $first_nameError = "First name must contain only letters and spaces";
    }

    //validate if last name is correct 

    if (empty($last_name)) {
        $error == true;
        $last_nameError = "Please, enter your last name";
    } elseif (strlen($last_name) < 2) {
        $error == true;
        $last_nameError = "Last name can not be shorter than 2 characters";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $last_name)) {
        $error == true;
        $last_nameError = "Last name must contain only letters and spaces";
    }

    //validation of the date_of_birth

    if (empty($date_of_birth)) {     //it cannot be empty
        $error = true;
        $dateError = "Date of birth can't be empty!";
    }
    //validation of email

    if (!filter_var($email, FILTER_VALIDATE_EMAIL))             // if the provided text is not a format of an email, error will be true 
    {
        $error = true;
        $emailError = "Please enter a valid email address";
    } else {
        $query = "SELECT email FROM users WHERE email='$email'";     // check if email is already exists in the database
        $result = mysqli_query($connect, $query);                   //connect to db
        if (mysqli_num_rows($result) != 0) {                        //if result is not equal 0, means that email excists 
            $error = true;
            $emailError = "Email is already in use. Try to log in";
        }
    }

    //validation of password 
    if (empty($password)) {
        $error = true;
        $passwordError = "Please, enter your password";
    } elseif (strlen($password) < 5) {
        $error = true;
        $passwordError = "Password must be longer than 5 characters";
    }

    if (!$error)  //if there is no errors, we have to send it to the db, but hash password for security
    {
        $password = hash("sha256", $password);
        $sql = "INSERT INTO users (first_name, last_name, password, email, date_of_birth, picture) VALUES ('$first_name','$last_name', '$password', '$email', '$date_of_birth', '$picture[0]')";

        $result = mysqli_query($connect, $sql);

        if ($result) {
            echo   "<div class='alert alert-success'>
               <p>New account has been created, $picture[1]</p>
           </div>";
        } else {
            echo   "<div class='alert alert-danger'>
               <p>Something went wrong, please try again later ...</p>
           </div>";
        }
    }
}
?>


<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">First Name</label>
        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Name" name="first_name" value="<?= $first_name ?>">
        <span class="text-danger"> <?= $first_nameError ?> </span>
    </div>
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Last Name</label>
        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Name" name="last_name" value="<?= $last_name ?>">
        <span class=" text-danger"> <?= $last_nameError ?> </span>
    </div>
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Picture</label>
        <input type="file" class="form-control" id="exampleFormControlInput1" placeholder="Upload your photo here" name="picture" value="<?= $picture ?>">

    </div>
    <div class=" mb-3">
        <label for="exampleFormControlInput1" class="form-label">E-mail</label>
        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Your email" name="email" value="<?= $email ?>">
        <span class=" text-danger"> <?= $emailError ?> </span>
    </div>
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="exampleFormControlInput1" placeholder="yyyy-mm-dd" name="date_of_birth" value="<?= $date_of_birth ?>">
        <span class=" text-danger"> <?= $date_of_birthError ?> </span>
    </div>
    <div class="mb-3">
        <label for="exampleFormControlInput1" class="form-label">Password</label>
        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Create a new password" name="password">
        <span class="text-danger"> <?= $passwordError ?> </span>
    </div>
    <div class="d-flex justify-content-center m-5 "><button name="submit" type="submit" class="btn btn-light">Submit</button></div>
</form>



<?= require_once "inc/footer.php"; ?>