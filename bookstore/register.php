<?php
include("html/top.html");
include("nav.php");
$fname = "";
$lname = "";
$electronicMailAddress = "";
$websiteNameOfUser = "";
$passwordAuthentication = "";
$confirmPassword = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("databaseConnect.php");
    $fname = $_POST["fname"];
    $fname = mysqli_real_escape_string($conn,$fname);

    $lname = $_POST["lname"];
    $lname = mysqli_real_escape_string($conn,$lname);

    $electronicMailAddress = $_POST["email"];
    $electronicMailAddress = mysqli_real_escape_string($conn,$electronicMailAddress);

    $websiteNameOfUser = $_POST["username"];
    $websiteNameOfUser = mysqli_real_escape_string($conn,$websiteNameOfUser);

    $passwordAuthentication = $_POST["pass"];
    $passwordAuthentication = mysqli_real_escape_string($conn,$passwordAuthentication);
    $confirmPassword = $_POST["confirmPass"];
    $confirmPassword = mysqli_real_escape_string($conn,$confirmPassword);

    if ($confirmPassword === $passwordAuthentication)
    {
        $sql1 = "SELECT * FROM userinfo WHERE user_name = '" . $websiteNameOfUser . "'";
        $result = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result) > 0) {
            $error = "Username is already taken by another user either sign in or use a different username";
        }else {
            $sql2 = "SELECT * FROM userinfo WHERE email = '" . $electronicMailAddress . "'";
            $result2 = mysqli_query($conn, $sql2);
            echo "Num of Rows" . mysqli_num_rows($result2);
            if (mysqli_num_rows($result2) > 0) {
                $error = "Email is already in use. Please log in instead of registering";
            }else{
                $sql = "INSERT INTO `userinfo`(`user_name`, `password`, `email`, `first_name`, `last_name`) VALUES ('" . $websiteNameOfUser . "','" . $passwordAuthentication . "','" . $electronicMailAddress . "','" . $fname . "','" . $lname . "')";
                if (mysqli_query($conn, $sql)) {
                    $_SESSION["first_name"] = stripslashes($fname);
                    $_SESSION["website_user_name"] = $websiteNameOfUser;
                    $_SESSION["email_name"] = $electronicMailAddress;
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
                $sql = "SELECT * FROM userinfo WHERE user_name = '" . $websiteNameOfUser . "'";
                $result = mysqli_query($conn,$sql);
                foreach ($result as $v) {
                    $_SESSION["user_id_number"] = $v['user_id'];
                    echo "USER ID NUMBER" . $_SESSION["user_id_number"];
                }

                mysqli_close($conn);
                header("Location: index.php");
            }
        }
    }else{
        $error ="Passwords Do Not Match. Please Try Again";
    }
}
if($error != '')
{
    echo "<p style='text-align:center;' >" . $error . "</p>";

}
include("html/register.html");
?>