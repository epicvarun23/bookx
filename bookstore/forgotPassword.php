<?php
include("html/top.html");
include("nav.php");
include("databaseConnect.php");
$username = "";
$password = "";
$email = "";
$buyer = "epicvarun23@gmail.com";
$headers ="";
$error="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = $_POST["email"];
    $sql = "select * from userinfo where email = '" . $email . "'";
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) { 
                $username = $row["user_name"];
                $password = $row["password"];
            }
        $subject = "BookX Password";
        $message = "Hello, to this email we have attached your BookX login information. If you didn't request this password request please ignore this message \r\nUsername: " . $username . " \r\nPassword: " . $password;
        mail($email, $subject, $message, $headers);
        header("Location: reset.php");
        }else{
            $error = "Email not registered. Please re-enter email or register <a href=\"register.php\">here</a>";
        }
}
include("html/forgotPassword.html");

?>