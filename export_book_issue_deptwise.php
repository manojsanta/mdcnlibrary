<?php

// session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (isset($_GET['id']) && isset($_GET['subid'])) {
    $id = $_GET['id'];
    $sub = $_GET['subid'];
    $output = "";
    $sqlborrow = "SELECT user.contact,user.fullname,user.user_id,user.school_number,borrow_book.user_id,borrow_book.book_id,book.book_title,book.book_barcode,book.category_id,borrow_book.date_borrowed,borrow_book.due_date,count(book.book_barcode) as aa FROM borrow_book inner join book ON
                                borrow_book.book_id=book.book_id INNER JOIN user ON borrow_book.user_id=user.user_id
                                WHERE borrow_book.borrowed_status='borrowed' AND book.category_id='$id' group by borrow_book.book_id";
    $return_query = mysqli_query($con, $sqlborrow) or die(mysqli_error($con));
    $return_count = mysqli_num_rows($return_query);
    $sum = 0;
    $i = 1;
    $output .= '<table border="1" class="table">
                                <thead><tr>
                                <th colspan="7"><h4 class="text-center">'
        . $sub . '</h4></th>
                                </tr>
                                <tr>
                                <th>Sl.No</th>
                                <th>Book Barcode</th>
                                <th>Book Title</th>
                                <th>Issued To</th>
                                <th>Full Name</th>
                                <th>Contact No.</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                </tr></thead><tbody><tr>';
    while ($return_row = mysqli_fetch_array($return_query)) {
        $sum += $return_row['aa'];


        $output .=
            '<td>' . $i++ . '</td>
        <td>' . $return_row['book_barcode'] . '</td>
        <td>' . $return_row['book_title'] . '</td>
        <td>' . $return_row['school_number'] . '</td>
        <td>' . $return_row['fullname'] . '</td>
        <td>' . $return_row['contact'] . '</td>
        <td>' . date('d-m-Y h:m:s A', strtotime($return_row['date_borrowed'])) . '</td>
        <td>' . date('d-m-Y h:m:s A', strtotime($return_row['due_date'])) . '</td>
                                    
                                    
                                    </tr></tbody>';
    }

    $output .=  '<tfoot>
								<tr>
									<th colspan="6">Total No of Books</th>
									<th>' . $sum . '</th></tr></tfoot>';
    $output .= '</table>';
    $filename = $sub . "_Issued_" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $output;
}
