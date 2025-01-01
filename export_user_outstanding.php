<?php

// session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT user.libcardno,user.user_id,user.school_number,user.fullname,user.subject,user.contact, SUM(IF(borrow_book.borrowed_status ='borrowed', 1, 0)) AS post_count 
    FROM user LEFT JOIN borrow_book ON borrow_book.user_id = user.user_id WHERE user.status='Active' GROUP BY 1";
}
$output = "";
$return_query = mysqli_query($con, $sql) or die(mysqli_error($con));
$return_count = mysqli_num_rows($return_query);
$sum = 0;
$i = 0;
$output .= '<table border="1" class="table">
                                <thead>
                                <tr>
                                <th>Sl. No</th>
                                <th>Roll No</th>
                                        <th>Member Full Name</th>
                                        <th>Subject</th>
                                        <th>Mobile No.</th>
                                        <th>Lib Card No</th>
                                        <th>Outstanding</th>
                                </tr></thead><tbody><tr>';
while ($return_row = mysqli_fetch_array($return_query)) {
    // $sum += $return_row['aa'];
    $output .=
        '<td>' . ++$i . '</td>
                                    <td>' . $return_row['school_number'] . '</td>
                                    <td>' . $return_row['fullname'] . '</td>
                                    <td>' . $return_row['subject'] . '</td>
                                    <td>' . $return_row['contact'] . '</td>
                                    <td>' . $return_row['libcardno'] . '</td>
                                    <td>' . $return_row['post_count'] . '</td>
                                    </tr></tbody>';
}
// $output .=  '<tfoot>
// 							<tr>
// 								<th colspan="4">Total No of Books</th>
// 								<th>' . $sum . '</th></tr></tfoot>';
$output .= '</table>';
// mb_convert_encoding($output, 'UCS-2LE', 'UTF-8');
// echo $output;
$filename = "Patron" . "_Oustanding_" . date('Ymd') . ".xls";
header("Content-Type: application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
// echo $output;
echo  chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE",  $output . "\n"); // importfor ODIA Font export not working
exit();
