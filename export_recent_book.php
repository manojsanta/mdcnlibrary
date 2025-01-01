<?php

// session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT temp_book.*,book.* from temp_book inner join book on temp_book.book_barcode=book.book_barcode";
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
                                <th>Barcode</th>
                                        <th>Title</th>
                                        <th>ISBN</th>
                                        <th>Author/s</th>
                                        <th>Publisher</th>
                                        <th>Subject</th>
                                        <th>Accession No</th>
                                        <th>Added on</th>
                                </tr></thead><tbody><tr>';
while ($return_row = mysqli_fetch_array($return_query)) {
    $id = $return_row['book_id'];
    $category_id = $return_row['category_id'];

    $cat_query = mysqli_query($con, "SELECT * from category where category_id = '$category_id'") or die(mysqli_error($con));
    $cat_row = mysqli_fetch_array($cat_query);
    // $sum += $return_row['aa'];
    $output .=
        '<td>' . ++$i . '</td>
                                    <td>' . $return_row['book_barcode'] . '</td>
                                    <td>' . $return_row['book_title'] . '</td>
                                    <td>' . $return_row['isbn'] . '</td>
                                    <td>' . $return_row['author'] . '<br />' . $return_row['author_2'] . '<br />' . $return_row['author_3'] . '<br />' . $return_row['author_4'] . '<br />' . $return_row['author_5'] . '</td>
                                    <td>' . $return_row['publisher_name'] . '</td>
                                    <td>' . $cat_row['classname'] . '</td>
                                    <td>' . $return_row['bookaccno'] . '</td>
                                    <td>' . date('d-m-Y', strtotime($return_row['date_added'])) . '</td>
                                    </tr></tbody>';
}
// $output .=  '<tfoot>
// 							<tr>
// 								<th colspan="4">Total No of Books</th>
// 								<th>' . $sum . '</th></tr></tfoot>';
$output .= '</table>';
// mb_convert_encoding($output, 'UCS-2LE', 'UTF-8');
// echo $output;
$filename = "Recent" . "_Bookadded_" . date('Ymd') . ".xls";
header("Content-Type: application/vnd.ms-excel;charset=utf-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
// echo $output;
echo  chr(255) . chr(254) . iconv("UTF-8", "UTF-16LE//IGNORE",  $output . "\n"); // importfor ODIA Font export not working
exit();
