<?php
include("html/top.html");
include("nav.php");
if(isset($_SESSION["first_name"])){
$bookTitle = "";
$bookSubject = "";
$grade9 = "";
$grade10 = "";
$grade11 = "";
$grade12 = "";
$gradeOther = "";
$image_book_title = "";
$grade9Checked="";
$year = "";

$price = "";
$error="";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    include("databaseConnect.php");
    $bookTitle = htmlspecialchars($_POST['bookTitle']);
    $image_book_title = $_POST["bookTitle"];
    $user_id_number = $_SESSION["user_id_number"];
    $condition = $_POST["condition"];
    $price = $_POST["price"];
    $year = $_POST["year"];
    $bookPublishers = $_POST["publishers"];
    if($_POST["subjects"] == "select"){
        $error = "Select A Subject";
    }else{
    $bookTitle = mysqli_real_escape_string($conn,$bookTitle);
    $bookSubject = htmlspecialchars($_POST["subjects"]);

    if(isset($_POST["bookPic"])){
        $fileName = $image_book_title . basename($_FILES["bookPic"]["name"]);
        $targetDir = "imgUploads/";
        $target_file = $targetDir . $fileName;
        $fileName = mysqli_real_escape_string($conn,$fileName);
        move_uploaded_file($_FILES["bookPic"]["tmp_name"], $target_file);
    }else{
        $fileName = "noimg.jpg";
    }

    $sql = "INSERT INTO bookinfo(book_title, book_subject, price, book_desc, user_id, book_upload_date, book_image_name, book_publisher, book_year)
VALUES ('" . $bookTitle . "', '" . $bookSubject . "',$price,'" . $condition . "',$user_id_number, '" . date("Y-m-d") . "', '" . $fileName . "', '" . $bookPublishers . "', $year)";

   

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    echo "Connection Closed";
    header("Location: thanks.php");
}
}
include("html/sell.html");
}else{
    header("Location: login.php");
}
?>