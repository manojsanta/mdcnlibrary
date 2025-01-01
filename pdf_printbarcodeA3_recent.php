<?php
// $barcodeText = 'manoj';
// $barcodeType = 'Code39';
// $barcodeSize = '30';
// $barcodeDisplay = 'vertical';
// $printText = 'true';
// echo '<img  alt="' . $barcodeText . '"src="includes/barcode.php?text=' . $barcodeText . '&codetype=' . $barcodeType . '&orientation=' . $barcodeDisplay . '&size=' . $barcodeSize . '&print=' . $printText . '"/>';
@session_start();
include('includes/dbconnection.php');
require_once 'mpdf/autoload.php';
error_reporting(0);

// reference the Dompdf namespace

// use Dompdf\Dompdf;

//initialize dompdf class

// $document = new Dompdf();
// $document->set_options(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

$sql = "SELECT temp_book.*,book.* from temp_book inner join book on temp_book.book_barcode=book.book_barcode";



$dyn_table = '';
// echo "<img src = 'includes/barcode.php?codetype=Code39&size=30&text=" . $row1['libcardno'] . "&print=true' />";
$dyn_table .= "<style>
    @page {
        margin: 1px;
    }

   
    tr,
    td,
    th {
        border: 1px solid black;
    }
   

   

    table {
        border-collapse: collapse;
        border: 1px solid black;
        
        width: 100%;
        margin-bottom: 10px;
        margin-top: 5px;
       
          
        
    }

</style>";
$dyn_table .= '<table border="0" cellpadding="5" cellspacing="5" width="100%">';
$query = mysqli_query($con, $sql);
$i = 0;
while ($row = mysqli_fetch_array($query)) {
    $id = $row['book_id'];
    $bn = $row['book_barcode'];
    $category_id = $row['category_id'];
    $bn = htmlspecialchars($bn);

    if ($i % 6 == 0) {
        $dyn_table .= '<tr><td style="text-align: center; vertical-align: middle;"><img src ="includes/barcode.php?codetype=code128&size=38&text=' . $bn . '&print=true" /></td>';
    } else {
        // echo $bn;
        $dyn_table .= '<td style="text-align: center; vertical-align: middle;"><img src ="includes/barcode.php?codetype=code128&size=38&text=' . $bn . '&print=true" /></td>';
    }
    $i++;
}
$dyn_table .= '</tr></table>';
// echo $dyn_table;
// unset($_SESSION["name"]);
// unset($_SESSION["ffund"]);


// echo $output;
$mpdf = new \Mpdf\Mpdf(['format' => 'A3']);
$mpdf->WriteHTML($dyn_table);
$fileName = rand() . '.pdf';
$mpdf->Output($fileName, 'D');
// $mpdf->Output($fileName, 'D');
unset($_SESSION["name"]);
unset($_SESSION["ffund"]);
