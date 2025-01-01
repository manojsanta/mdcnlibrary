<?php

// session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (isset($_GET['id']) && isset($_GET['subid'])) {
    $id = $_GET['id'];
    $sub = $_GET['subid'];

    $output = "";
    $return_query = mysqli_query($con, "SELECT book.book_title,book.author,book.author_2,book.author_3,book.author_4,book.publisher_name,book.category_id,count(book.book_barcode) as aa ,category.* from book inner join category on book.category_id=category.category_id
    where book.category_id='$id' group by book.book_title") or die(mysqli_error($con));
    $return_count = mysqli_num_rows($return_query);
    $sum = 0;
    $i = 0;
    $output .= '<table border="1" class="table">
                                <thead><tr>
                                <th colspan="5"><h4 class="text-center">'
        . $sub . '</h4></th>
                                </tr>
                                <tr>
                                <th>Sl.No</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th>No. of Copies</th>
                                </tr></thead><tbody><tr>';
    while ($return_row = mysqli_fetch_array($return_query)) {
        $sum += $return_row['aa'];


        $output .=
            '<td>' . ++$i . '</td>
                                    <td>' . $return_row['book_title'] . '</td>
                                    <td>' . $return_row['author'] . '</td>
                                    <td>' . $return_row['publisher_name'] . '</td>
                                    <td>' . $return_row['aa'] . '</td>
                                    
                                    
                                    </tr></tbody>';
    }

    $output .=  '<tfoot>
								<tr>
									<th colspan="4">Total No of Books</th>
									<th>' . $sum . '</th></tr></tfoot>';
    $output .= '</table>';
    $filename = $sub . "_Catalog_" . date('Ymd') . ".xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $output;
}
