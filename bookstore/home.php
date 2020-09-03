<?php
include("html/top.html");
include("homeScreenNav.php");
$book_title = "";
$book_subject = "";
$minimum_price = 0;
$maximum_price = 100;
$year = "";

$ap_option = "<option value=\"AP\">AP</option>";
$sat_option = "<option value=\"SAT\">SAT</option>";
$act_option = "<option value=\"ACT\">ACT</option>";
$other_option = "<option value=\"OTHER\">Other</option>";

$barrons = "<option value=\"Barron\">Barron's</option>";
$princetonReview = "<option value=\"Princeton Review\">Princeton Review</option>";
$kaplan = " <option value=\"Kaplan\">Kaplan</option>";
$otherPublisher = "<option value=\"OTHER\">Other</option>";

$new = "<option value=\"New\">New</option>";
$slightlyUsed = " <option value=\"Slightly Used\">Slightly Used</option>";
$used = "<option value=\"Used\">Used</option>";
$bad = "<option value=\"Bad\">Bad</option>";

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

$sql = "SELECT * FROM bookinfo where sold = 0";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["searchSmallScreen"])){
        $searchValue = $_POST["searchSmallScreen"];
        $sql = "SELECT * from bookinfo where  book_title like '%" .$searchValue . "%' or  book_publisher like '%" .$searchValue . "%' or  book_subject like '%" .$searchValue . "%' ";
    }
    if (isset($_POST["book_name"])){
        $book_title = $_POST["book_name"];
        $sql = $sql .  " AND book_title like '%" .$book_title . "%'";
    }
    if(isset($_POST["publishers"])){
        $book_publisher = $_POST["publishers"];
        if ($book_publisher != "select"){
            if($book_publisher == "Barron"){
                $barrons = "<option value=\"Barron\" selected =\"selected\">Barron's</option>";
            } else if($book_publisher == "Princeton Review"){
                $princetonReview = "<option value=\"Princeton Review\" selected =\"selected\">Princeton Review</option>";
            }else if($book_publisher == "Kaplan"){
                $kaplan = " <option value=\"Kaplan\" selected =\"selected\">Kaplan</option>";
            }else if($book_publisher == "OTHER"){
                $otherPublisher = "<option value=\"OTHER\" selected =\"selected\">Other</option>";
            }
            $sql = $sql .  " AND book_publisher = '" . $book_publisher . "'";
        }
    }
    if(isset($_POST["book_subject"])){
        $book_subject = $_POST["book_subject"];
        if ($book_subject != "selectSubject"){
            if($book_subject == "AP"){
                $ap_option = "<option value=\"AP\" selected=\"selected\">AP</option>";
            } else if($book_subject == "SAT"){
                $sat_option = "<option value=\"SAT\" selected=\"selected\">SAT</option>";
            }else if($book_subject == "ACT"){
                $act_option = "<option value=\"ACT\" selected=\"selected\">ACT</option>";
            }else if($book_subject == "OTHER"){
                $other_option = "<option value=\"OTHER\" selected=\"selected\">Other</option>";
            }
            $sql = $sql .  " AND book_subject = '" . $book_subject . "'";
        }
    }

    if(isset($_POST["condition"])){
        $book_condition = $_POST["condition"];
        if ($book_publisher != "select"){
            if($book_condition == "New"){
                $new = "<option value=\"New\" selected=\"selected\">New</option>";
            } else if($book_condition == "Slightly Used"){
                $slightlyUsed = " <option value=\"Slightly Used\" selected=\"selected\">Slightly Used</option>";
            }else if($book_condition == "Used"){
                $used = "<option value=\"Used\" selected=\"selected\">Used</option>";
            }else if($book_condition == "Bad"){
                $bad = "<option value=\"Bad\" selected=\"selected\">Bad</option>";        }
            $sql = $sql .  " AND book_desc = '" . $book_condition . "'";
        }
    }

    if (isset($_POST["minimum_price"])){
        $minimum_price = $_POST["minimum_price"];
        $sql = $sql .  " AND price >= $minimum_price";
    }
    if (isset($_POST["maximum_price"])){
        $maximum_price = $_POST["maximum_price"];
        $sql = $sql .  " AND price <= $maximum_price";
    }

    if(!empty($_POST["book_year"])){
        $year = $_POST["book_year"];
        $sql = $sql. " AND book_year >= $year";
    }
}



    
$sql = $sql .  " ORDER BY price asc LIMIT " . $limit . " OFFSET " . $offset;
$result = mysqli_query($conn, $sql);

$sql = "SELECT COUNT(*) FROM bookinfo WHERE sold = 0";
$rs_result = mysqli_query($conn,$sql);
$row = mysqli_fetch_row($rs_result);
$total_records = $row[0];
$total_pages = ceil($total_records / $limit);
$pagLink = "<li class=\"page-item\">";
$prev = $page_number - 1;
$nxt = $page_number + 1;

if($page_number != 1){
    $pagLink .= "<a class=\"page-link\" href='index.php?page=".$prev."'>Previous</a></li>";
}
for ($i=1; $i<=$total_pages; $i++) {
    if ($i==$page_number) {
        $pagLink .= "<li class=\"page-item active\"><a class=\"page-link\" href='index.php?page=".$i."'>$i</a></li>";
    }
    else  {
        $pagLink .= "<li class=\"page-item\"><a class=\"page-link\" href='index.php?page=".$i."'>$i</a></li>";
    }
};
if($page_number != $total_pages){
$pagLink .= "<li class=\"page-item\"><a class=\"page-link\" href='index.php?page=".$nxt."'>Next</a></li>";
}
$sql = "SELECT * FROM bookinfo LIMIT $offset, $limit";
$rs_result = mysqli_query ($conn,$sql);
$table_output="";
$email="";
if(mysqli_num_rows($result) > 0){
while ($row = mysqli_fetch_assoc($result)) { 
    $seller_id=$row["user_id"];
    $emailSearchSql = "SELECT * FROM userinfo where user_id = $seller_id";
    $email_search_result = mysqli_query($conn, $emailSearchSql);
    if(mysqli_num_rows($email_search_result) > 0){
        while ($email_row = mysqli_fetch_assoc($email_search_result)) {
            $email = $email_row["email"];
        }
    }

    $bookName = $row["book_title"];
    $book_id = $row["book_id"];
    $price= $row["price"];
    
    if($price <= 0){
        $price = "Free";
    }else{
        $price = "$" . $price;
    }
    $table_output .= "
    <table class=\"displayTable\">
    <tr>
        <td class=\"displayTableImage\">
            <img src=\" imgUploads/".$row["book_image_name"]." \" style=\"width: 150px;\">
        </td>
         <td valign=\"top\" class=\"displayTableData\">
             <table>
                 <tr>
                     <td class=\"displayTableData\">
                     <p class=\"bookTitleHomePage\">
                        ".$bookName."
                         </p>
                    </td>
                </tr>
                <tr>
                    <td class=\"displayTableData\">
                        <p class=\"priceHomePage\">".$price."</p>
                    </td>
                </tr>
                <tr>
                    <td class=\"displayTableData\">
                    <p class=\"bookSubjectHomePage\">Subject: ".$row["book_subject"]." <br> Publisher: ".$row["book_publisher"]." (".$row["book_year"].")</p>
                    </td>
                </tr>
                <tr>
                    <td class=\"displayTableData\" id=\"descriptionTableData\">
                        <p><i>".$row["book_desc"]." </i></p>
                    </td>
                </tr>
                <tr>
                    <td class=\"displayTableData form-group\">
                        <form action=\"mail.php\" method=\"post\">
                            <button class=\"homeTableButton btn btn-primary\" type=\"submit\">Contact Seller</button>
                            <input type=\"text\" name=\"email_of_seller\" value=\"$email\" hidden>
                            <input type=\"text\" name=\"book_number\" value=\"$book_id\" hidden>
                            <input type=\"text\" name=\"book_name\" value=\"$bookName\" hidden>
                            <input type=\"text\" name=\"id_number\" value=\"$seller_id\" hidden>
                        </form>
                    </td>
                </tr>
             </table>
        </td>
    </tr>
</table>
<hr style=\"height:2px;border-width:0;color:gray;background-color:gray; opacity:35%\" width=\"95%\">";
}}
$table_output .= "<tr>
</table>";




####################################################################################################################################################################################
#FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER FILTER
####################################################################################################################################################################################






include("html/home.html");
?>