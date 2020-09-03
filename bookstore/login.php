<?php
include("html/top.html");
include("nav.php");
$user_name = "";
$pword = "";
$usernameError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    print_r($_POST) ;
    include("databaseConnect.php");
    $user_name = $_POST["username"];
    $user_name = mysqli_real_escape_string($conn,$user_name);

    $pword = $_POST["pass"];
    $pword = mysqli_real_escape_string($conn,$pword);

    $sql1 = "SELECT * FROM userinfo WHERE user_name = '" . $user_name . "' AND password = '" . $pword . "'";
    echo $sql1;
    $result = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result) == 0) {
        $usernameError = "Try Again either Username or Password is Incorrect";
        mysqli_close($conn);
    }else{
        session_start();
        $_SESSION = array();
        $_SESSION["website_user_name"] = $user_name;
       foreach ($result as $v) {
            $_SESSION["first_name"] = stripslashes($v['first_name']);
            $_SESSION["user_id_number"] = $v['user_id'];
            $_SESSION["email_name"] = $v['email'];
            echo "Email " .$v['email'] . "    |         " . $_SESSION["email_name"];
        }
        mysqli_close($conn);
        echo $_SESSION["user_id_number"];
        header("Location: index.php");
    }
}
include("html/login.html");


?>