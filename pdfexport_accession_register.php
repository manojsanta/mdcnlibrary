<?php
// include('session.php');
error_reporting(E_ALL);
include('includes/dbconnection.php');
$sub = "";
require('mpdf/autoload.php');
ini_set('memory_limit', '1500000M');
ini_set("pcre.backtrack_limit", "3000000");
if (empty($_GET['id']) && empty($_GET['sub'])) {

    $type = $_GET['type'];
    // $type = 'T';
    // $return_query= mysqli_query($con,
    // ) or die (mysqli_error());
    $string = "SELECT book.*,category.* from book inner join category on book.category_id=category.category_id
							where book.booktype='$type'";
} else {
    $type = $_GET['type'];
    $id = $_GET['id'];
    $sub = $_GET['sub'];

    $string = "SELECT book.* ,category.* from book inner join category on book.category_id=category.category_id
							where book.category_id in ($id)
 and book.booktype='$type'";
}


$output = "<style>
    .container {
        width: 100%;
        margin: auto;
    }

    .table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
        table-layout: fixed;
    }

    tr,
    td,
    th {
        border: 1px solid black;
        overflow: hidden;
        
        word-wrap: break-word;
        word-break: break-all;
        white-space: normal;
    }

    .table-striped tbody>tr:nth-child(odd)>td,
    .table-striped tbody>tr:nth-child(odd)>th {
        background-color: #f9f9f9;
    }


  
</style>
<table>";
$output .= '<tr>

                    <th width="45px">Sl No#</th>
                    <th width="135px">Accession No.</th>
                    <th width="95px">Barcode No.</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Publisher</th>
                    <th width="90px">Price</th>
                    <th width="100px">Purchase Date</th>
                </tr>
                      <tbody>';
$return_query = mysqli_query($con, $string);
$return_count = mysqli_num_rows($return_query);

$sum = 0;
$i = 0;
while ($return_row = mysqli_fetch_array($return_query)) {
    // $id=$return_row['borrow_book_id'];
    $sum += $return_row['book_copies'];
    // $sum+=$return_row['aa'];

    $output .= '<tr>
                        <td>' . ++$i . '</td>
                        <td style="text-transform:capitalize">' . $return_row['bookaccno'] . '</td>
                        <td style="text-transform:capitalize">' . $return_row['book_barcode'] . '</td>
                        <td style="text-transform:capitalize">' . $return_row['book_title'] . '</td>
                        <td style="text-transform:capitalize">' . $return_row['author'] . ',' . $return_row['author_2'] . ',' . $return_row['author_3'] . ',' . $return_row['author_4'] . '</td>
                        <td style="text-transform:capitalize">' . $return_row['publisher_name'] . '</td>
                         <td>' . $return_row['price'] . '</td>
                        <td>' . date('d/m/Y', strtotime($return_row['invdate'])) . '</td>
                    </tr>';
}
if ($return_count <= 0) {
    $output .= '
									<table style="float:right;">
										<tr>
											<td style="padding:10px;" class="alert alert-danger">No Books found at this moment</td>
										</tr>
									</table>
								';
}
$output .= '</tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7">Total No of Books</th>
                    <th>' . $sum . '</th>
                </tr>
            </tfoot>
        </table>';
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($output);
$file = 'media/' . time() . '.pdf';
$mpdf->output($file, 'D');
