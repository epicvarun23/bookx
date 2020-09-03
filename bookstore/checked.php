<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include("databaseConnect.php");
    $sql="";
    $dashboardBookTitle = $_POST["bookTitle"];
    $dashboardBookIdNumber = $_POST["bookId"];
    $dashboardBookTitle = mysqli_real_escape_string($conn,$dashboardBookTitle);
    if(isset($_POST["checked"])){
        $sql = "UPDATE bookinfo SET sold=1 where book_id = $dashboardBookIdNumber";
    }else{
        $sql = "UPDATE bookinfo SET sold=0 where book_id = $dashboardBookIdNumber";
    }
    echo $sql;
    $result = mysqli_query($conn, $sql);
    mysqli_close($conn);
    header("Location: dashboard.php");
}
?>