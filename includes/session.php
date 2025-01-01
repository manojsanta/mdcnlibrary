<?php
@session_start();
include('includes/dbconnection.php');
if (!isset($_SESSION['sid'])) {
    header('location:index.php');
}
$id_session = $_SESSION['sid'];
$user_query  = mysqli_query($con, "select * from admin where admin_id = '$id_session'") or die(mysqli_error($con));
$user_row = mysqli_fetch_array($user_query);
// $admin_type  = $user_row['admin_type'];
$_SESSION['uname'] = $user_row['admin_type'];;
