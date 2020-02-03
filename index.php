<?php
// error_reporting(0);
//date_default_timezone_set('Asia/Jakarta');
include("system/function.php");
include("shared/header.php");
isset($_GET['page']) ? $get_page=$_GET['page'] : $get_page="home"; 
switch($get_page)
{
    case"home";
	include"pages/dashboard.php";
	break;
}
include("shared/footer.php");