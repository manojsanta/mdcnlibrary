<?php

// session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (isset($_GET['id']) && isset($_GET['subid'])) {
    $id = $_GET['id'];
    $sub = $_GET['subid'];
    $sql = "SELECT book.*,category.classname as subject from book inner join category on  book.category_id=category.category_id  order by book_id asc";
    $output = "";
    $return_query = mysqli_query($con, $sql) or die(mysqli_error($con));
    $return_count = mysqli_num_rows($return_query);
    $sum = 0;
    $i = 0;
    $output .= '<table border="1" class="table">
                                <thead>
                                <tr>
                                <th>Sl. No</th>
                                       <th>Book Title</th>
                                        <th>Subject</th>
                                        <th>Author</th>
                                        <th>Publisher</th>
                                        <th>Price</th>
                                        <th>ISBN/ISSN</th>
                                        <th>Book Barcode No</th>
                                        <th>Book Accession No</th>
                                        <th>Book Category</th>
                                        <th>Accession Date</th>
                                        <th>Invoice No</th>
                                        <th>Invoice  Date</th>
                                        <th>Status</th>
                                        <th>No. of Copies Available</th>
                                        <th>Remark</th>
                                </tr></thead><tbody><tr>';
    while ($return_row = mysqli_fetch_array($return_query)) {
        $sum += $return_row['aa'];
        $val = ($return_row['booktype'] == 'T') ? 'Purchased' : 'Specimen';
        $output .=
            '<td>' . ++$i . '</td>
                                    <td>' . $return_row['book_title'] . '</td>
                                    <td>' . $return_row['subject'] . '</td>
                                    <td>' . $return_row['author'] . "," . $return_row['author_2'] . "," . $return_row['author_3'] . "," . $return_row['author_4'] . "," . $return_row['author_5'] . '</td>
                                    <td>' . htmlspecialchars($return_row['publisher_name']) . '</td>
                                    <td>' . htmlspecialchars($return_row['price']) . '</td>
                                    <td>' . htmlspecialchars($return_row['isbn']) . '</td>
                                    <td>' . htmlspecialchars($return_row['book_barcode']) . '</td>
                                    <td>' . htmlspecialchars($return_row['bookaccno']) . '</td>
                                    <td>' . $val . '</td>
                                    <td>' . date('d-m-Y', strtotime($return_row['accdate'])) . '</td>
                                    <td>' . htmlspecialchars($return_row['invno']) . '</td>
                                        <td>' . date('d-m-Y', strtotime($return_row['invdate'])) . '</td>
                                        <td>' . $return_row['status'] . '</td>
                                        <td>' . $return_row['book_copies'] . '</td>
                                        <td>' . $return_row['remarks'] . '</td>
                                    </tr></tbody>';
    }

    // $output .=  '<tfoot>
    // 							<tr>
    // 								<th colspan="4">Total No of Books</th>
    // 								<th>' . $sum . '</th></tr></tfoot>';
    $output .= '</table>';
    // mb_convert_encoding($output, 'UCS-2LE', 'UTF-8');
    $filename = "BOOK" . "__STOCK__" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel;charset=utf-8");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo  chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE",  $output . "\n"); // importfor ODIA Font export not working
    echo $output;
    exit();
}
