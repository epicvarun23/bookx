<?php
$html_code = "<li class=\"navigationText\" id=\"dropdownMenuButton\"><a href=\"login.php\">Login</a></li>";
$html_code_small_screen = "<a class=\"dropdown-item\" href=\"login.php\">Login</a>";
$menuText = "Menu";
$bookNameMiddle="";
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

include("html/nav.html");
?>
