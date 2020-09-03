<?php
$html_code = "<li class=\"navigationText\" id=\"dropdownMenuButton\"><a href=\"login.php\">Login</a></li>";
$html_code_small_screen = "<a class=\"dropdown-item\" href=\"login.php\">Login</a>";
$menuText = "Menu";
if(isset($_SESSION["website_user_name"])){
    $welcome_text = "Welcome " . $_SESSION["first_name"];
    $html_code = "<li class=\"navigationText\">
    <a class=\"navigationText\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
    $welcome_text
    <i class=\"fa fa-caret-down\"></i> 
    </a>
    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
      <a class=\"dropdown-item\" href=\"dashboard.php\">Dashboard</a>
      <a class=\"dropdown-item\" href=\"logout.php\">Log Out</a>
    </div>
  </li>
  ";

  $html_code_small_screen = "<a class=\"dropdown-item\" href=\"logout.php\">Log Out</a> <a class=\"dropdown-item\" href=\"dashboard.php\">Dashboard</a>";

  $menuText = "Welcome " . $_SESSION["first_name"];
}

$bookNameMiddle = "
<form class=\"filterFormSmall form-group\" style=\"margin-top: 5px\" action=\"home.php\" method=\"post\">
    <input type=\"text\" name=\"searchSmallScreen\" placeholder=\"Search Books\">
    <input type=\"submit\" value=\"Search\"  class=\"btn btn-primary\">
</form>
<h2 style=\"text-align: center; width: wrap-content;\">
  Books
</h2>


";

include("html/nav.html");
?>