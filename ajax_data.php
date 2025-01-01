<?php
// include 'include/session.php';	
include('includes/dbconnection.php');
/*if(isset($_POST['id1'])){
	$id = $_POST['id1'];
	//$output='';
$sql = "SELECT * from s_module where cid='$id' and sem='1'";
	$query = $conn->query($sql);
	$checked_arr = array();
	 while($row = $query->fetch_array()){
			$checked_arr = explode(",",$row['cmodule']);
				foreach ($checked_arr as $page) {
					echo "</br><input type='checkbox' name='products[]' value='".$page."' checked>".$page;
				}
		}

}*/
if (isset($_POST["booktype_id"])) {
	$booktype = !empty($_POST['booktype_id']) ? $_POST['booktype_id'] : '';

	$course_id = !empty($_POST['cat_id']) ? $_POST['cat_id'] : '';
	$result_explode = explode('|', $course_id);
	$cat_code = $result_explode[0];
	$cat_name = $result_explode[1];
	// $cat_name=!empty($_POST['cat_name'])?$_POST['cat_name']:'';
	/* DO YOUR QUERY HERE AND GET THE OUTPUT YOU WANT */
	$result = mysqli_query($con, "SELECT * FROM book where category_id = '$cat_code' and booktype='$booktype'");
	$rows = mysqli_num_rows($result);
	$barcode = $booktype . $cat_name . str_pad((($rows) + 1), 4, '0', STR_PAD_LEFT);
	$accession = mysqli_query($con, "SELECT * FROM book where booktype='$booktype'");
	$rowacc = mysqli_num_rows($accession);
	$accessionno = "MDCN" . $cat_name . $booktype . str_pad((($rowacc) + 1), 4, '0', STR_PAD_LEFT);
	$data = array(
		"barcode" => $barcode,
		//"result" => $count
		"accession" => $accessionno
		//  "fee"  => $rowd['cfee']
	);
	echo json_encode($data);
}
if (isset($_POST["sub_id"])) {
	$subid = !empty($_POST['sub_id']) ? $_POST['sub_id'] : '';
	$admyear_id = !empty($_POST['admyear_id']) ? $_POST['admyear_id'] : '';
	// $result_explode = explode('|', $course_id);
	// $cat_code= $result_explode[0];
	// $cat_name= $result_explode[1];
	// $cat_name=!empty($_POST['cat_name'])?$_POST['cat_name']:'';
	/* DO YOUR QUERY HERE AND GET THE OUTPUT YOU WANT */
	$resultuser = mysqli_query($con, "SELECT * FROM user");
	$yy = substr($admyear_id, -2);
	$endyear = $admyear_id + 3;
	$rowsuser = mysqli_num_rows($resultuser);
	$libno = "LC" . $subid . $yy . str_pad((($rowsuser) + 1), 4, '0', STR_PAD_LEFT);
	// $accession = mysqli_query($con,"SELECT * FROM book where booktype='$booktype'");
	// $rowacc = mysqli_num_rows($accession);
	// $accessionno="MDCN".$cat_name.$booktype.str_pad((($rowacc)+1),4,'0',STR_PAD_LEFT);
	$data1 = array(
		"libcardno" => $libno,
		"endyr" => $endyear
		//"result" => $count
		// "accession"=> $accessionno
		//  "fee"  => $rowd['cfee']
	);
	//  echo $subid;
	echo json_encode($data1);
}
if (isset($_POST["tsub_id"])) {
	$tsubid = !empty($_POST['tsub_id']) ? $_POST['tsub_id'] : '';
	$tadmyear_id = !empty($_POST['tadmyear_id']) ? $_POST['tadmyear_id'] : '';
	// $result_explode = explode('|', $course_id);
	// $cat_code= $result_explode[0];
	// $cat_name= $result_explode[1];
	// $cat_name=!empty($_POST['cat_name'])?$_POST['cat_name']:'';
	/* DO YOUR QUERY HERE AND GET THE OUTPUT YOU WANT */
	$tresultuser = mysqli_query($con, "SELECT * FROM user where type='Teacher'");
	$yy = substr($tadmyear_id, -2);
	$endyear = $tadmyear_id + 3;
	$rowsuser = mysqli_num_rows($tresultuser);
	$libno = "TLC" . $tsubid . $yy . str_pad((($rowsuser) + 1), 4, '0', STR_PAD_LEFT);
	$rollno = "T" . str_pad((($rowsuser) + 1), 3, '0', STR_PAD_LEFT);
	// $accession = mysqli_query($con,"SELECT * FROM book where booktype='$booktype'");
	// $rowacc = mysqli_num_rows($accession);
	// $accessionno="MDCN".$cat_name.$booktype.str_pad((($rowacc)+1),4,'0',STR_PAD_LEFT);
	$data2 = array(
		"libcardno" => $libno,
		"rollno" => $rollno,
		"endyr" => $endyear
		//"result" => $count
		// "accession"=> $accessionno
		//  "fee"  => $rowd['cfee']
	);
	//  echo $subid;
	echo json_encode($data2);
}
// if(isset($_POST['id2'])){
// 	$id = $_POST['id2'];
// 	$eid = $_POST['eid'];
// 	//$output='';
// $sql = "SELECT * from s_formfillup";
// //$sql = "SELECT * from s_trans";
// $query = $conn->prepare($sql);
// $query->execute();
// $query->store_result();
// $count = $query->num_rows();
// $code="CE";
// $rollno= $eid."CE".date("y").str_pad(($count+1),5,"0",STR_PAD_LEFT);
// 	 // $query = $conn->query($sql);
// 		//$rowd = $query->fetch_assoc();
// 		//$roll=rand(100000,999999);
// 		//$rollno.="CE";
// 		echo $rollno;

// //echo $enrollid;
// }
