<?php
//https://pepipost.com/tutorials/send-an-email-via-gmail-smtp-server-using-php/
include("html/top.html");
$seller = "";
$subject = "Test mail";
$message = "";
$buyer = "epicvarun23@gmail.com";
$headers ="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $idNumber = $_POST["id_number"];
    $bookName = $_POST["book_name"];
    $bookId = $_POST["book_number"];
    if(isset($_SESSION["email_name"])){
        $headers = "Request for : " . $bookName;
        $message = "Hello! This is an automated message from BookX about your book," . $bookName . " up for sale on our website. " . $_SESSION["first_name"] . " would like to purchase the book from you. Please contact him at " .  $_SESSION["email_name"] . ". Make sure to update the status of your book in the case that you agree not to sell to him or her.";
        $seller = $_POST["email_of_seller"];
        mail($seller, $subject, $message, $headers);
        $soldUpdatorSQL = "UPDATE bookinfo SET sold=1 WHERE user_id = $idNumber AND book_id = $bookId";
        include("databaseConnect.php");
        $result = mysqli_query($conn, $soldUpdatorSQL);
        //header("Location: buyThank.php");
    }
    else{
        echo "email :" . $_SESSION["email_name"];
        header("Location: login.php");
    }
}
?>