<?php

// session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (isset($_GET['id']) && isset($_GET['subid'])) {
    $id = $_GET['id'];
    $sub = $_GET['subid'];
    $sql="SELECT borrow_book.*,user.user_id,user.school_number,user.fullname,book.book_id,book.book_title,book.book_barcode,
                                    datediff(CURDATE(),borrow_book.due_date) AS days FROM borrow_book inner JOIN user ON borrow_book.user_id=user.user_id inner JOIN book ON borrow_book.book_id=
                                    book.book_id WHERE DATEDIFF(CURDATE(),borrow_book.due_date)>'$id' AND borrow_book.borrowed_status='borrowed'";
    $output = "";
    $return_query = mysqli_query($con,$sql ) or die(mysqli_error($con));
    $return_count = mysqli_num_rows($return_query);
    $sum = 0;
    $i = 0;
    $output .= '<table border="1" class="table">
                                <thead>
                                <tr>
                                <th>Sl. No</th>
                                        <th>Roll No</th>
                                        <th>Full Name</th>
                                        <th>Book No</th>
                                        <th>Book Title</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Vintage</th>
                                </tr></thead><tbody><tr>';
    while ($return_row = mysqli_fetch_array($return_query)) {
        $sum += $return_row['aa'];
        $output .=
            '<td>' . ++$i . '</td>
                                    <td>' . $return_row['school_number'] . '</td>
                                    <td>' . $return_row['fullname'] . '</td>
                                    <td>' . $return_row['book_barcode'] . '</td>
                                    <td>' . htmlspecialchars($return_row['book_title']) . '</td>
                                    <td>'.date('d-m-Y h:i:s A', strtotime($return_row['date_borrowed'])).'</td>
                                        <td>'. date('d-m-Y h:i:s A', strtotime($return_row['due_date'])).'</td>
                                        <td>'.$return_row['days'].'</td>
                                    </tr></tbody>';
    }

    // $output .=  '<tfoot>
	// 							<tr>
	// 								<th colspan="4">Total No of Books</th>
	// 								<th>' . $sum . '</th></tr></tfoot>';
    $output .= '</table>';
    // mb_convert_encoding($output, 'UCS-2LE', 'UTF-8');
    $filename = "BookIsuedVintage" . "__Arrear__" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel;charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo  chr(255).chr(254).iconv("UTF-8", "UTF-16LE//IGNORE",  $output . "\n"); // importfor ODIA Font export not working

    exit();
}
