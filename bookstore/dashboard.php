<?php
include("html/top.html");
include("nav.php");
$book_title = "";
$book_subject = "";
$minimum_price = 0;
$maximum_price = 100;

include("databaseConnect.php");
$limit = 5;  // Number of entries to show in a page.
// Look for a GET variable page if not found default is 1.
if (isset($_GET["page"])) {
    $page_number  = $_GET["page"];
}
else {
    $page_number=1;
};

$offset = ($page_number-1) * $limit;
$user_id_number = $_SESSION["user_id_number"];
$sql = "SELECT * FROM bookinfo where user_id = $user_id_number";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["book_name"])){
        $book_title = $_POST["book_name"];
        $sql = $sql .  " AND book_title like '%" .$book_title . "%'";
    }
    $book_subject = $_POST["book_subject"];
    if ($book_subject != "selectSubject"){
        $sql = $sql .  " AND book_subject = '" . $book_subject . "'";
    }
    if (isset($_POST["minimum_price"])){
        $minimum_price = $_POST["minimum_price"];
        $sql = $sql .  " AND price > $minimum_price";
    }
    if (isset($_POST["maximum_price"])){
        $maximum_price = $_POST["maximum_price"];
        $sql = $sql .  " AND price < $maximum_price";
    }
}
$sql = $sql .  " LIMIT " . $limit . " OFFSET " . $offset;
$result = mysqli_query($conn, $sql);


$sql = "SELECT COUNT(*) FROM bookinfo";
$rs_result = mysqli_query($conn,$sql);
$row = mysqli_fetch_row($rs_result);
$total_records = $row[0];

$total_pages = ceil($total_records / $limit);
$pagLink = "";
$prev = $page_number - 1;
$nxt = $page_number + 1;
$pagLink .= "<li class=\"page-item\"><a class=\"page-link\" href='index.php?page=".$prev."'>Previous</a></li>";
for ($i=1; $i<=$total_pages; $i++) {
    if ($i==$page_number) {
        $pagLink .= "<li class=\"page-item active\"><a class=\"page-link\" href='index.php?page=".$i."'>$i</a></li>";
    }
    else  {
        $pagLink .= "<li class=\"page-item\"><a class=\"page-link\" href='index.php?page=".$i."'>$i</a></li>";
    }
};
$pagLink .= "<li class=\"page-item\"><a class=\"page-link\" href='index.php?page=".$nxt."'>Next</a></li>";
$sql = "SELECT * FROM table1 LIMIT $offset, $limit";
$rs_result = mysqli_query ($conn,$sql);

$table_output = "";
if(mysqli_num_rows($result) > 0){
while ($row = mysqli_fetch_assoc($result)) { 
    
    $checked="";
    if($row["sold"] == 1){
        $checked = "checked=\"checked\"";
    } else {
        $checked = " ";
    }
    $title = $row["book_title"];
    $book_id_number = $row["book_id"];
    
    $table_output .= "
    <table class=\"displayTable\">
    <tr>
        <td class=\"displayTableImage\">
            <img src=\" imgUploads/".$row["book_image_name"]." \" style=\"width: 150px;\">
        </td>
         <td valign=\"top\" class=\"displayTableData dashboardDisplay\">
             <table>
                 <tr>
                     <td class=\"displayTableData dashboardDisplay\">
                        <h5>
                        ".$row["book_title"]."
                         </h5>
                    </td>
                </tr>
                <tr>
                    <td class=\"displayTableData dashboardDisplay\">
                        <h6>$".$row["price"]."</h6>
                    </td>
                </tr>
                <tr>
                    <td class=\"displayTableData dashboardDisplay\">
                        <h6>Subject - ".$row["book_subject"]."</h6>
                    </td>
                </tr>
                <tr>
                    <td class=\"displayTableData dashboardDisplay\" id=\"descriptionTableData\">
                        <p><i>".$row["book_desc"]." </i></p><br>
                    </td>
                </tr>
                <tr>
                    <td valign=\"top\" class=\"displayTableData soldRemover\">
                        Sold: 
                        <form action=\"checked.php\" method=\"post\">
                            <input type=\"text\" name=\"bookTitle\"value=\"$title\" hidden>
                            <input type=\"text\" name=\"bookId\"value=\"$book_id_number\" hidden>
                            <input type=\"checkbox\" name=\"checked\" $checked><br><button type=\"submit\">UPDATE</button>
                        </form>
                    </td>
                </tr>
             </table>
        </td>
    </tr>
</table>
<hr width=\"95%\">";
}}
include("html/home.html");
?>