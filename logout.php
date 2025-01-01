
<?php
session_start();
include("includes/dbconnection.php");
date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
$ldate = date('d-m-Y h:i:s A', time());
// $email=$_SESSION['email'];
$username = $_SESSION['login'];
$sql = "UPDATE userlog_admin  SET logout=:ldate WHERE username = '$username ' ORDER BY id DESC LIMIT 1";
$query = $dbh->prepare($sql);
$query->bindParam(':ldate', $ldate, PDO::PARAM_STR);
$query->execute();
$_SESSION['errmsg'] = "You have successfully logout";
unset($_SESSION['cpmsaid']);
session_destroy(); // destroy session
header("location:index.php");
?>