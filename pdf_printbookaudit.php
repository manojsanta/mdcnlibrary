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
if (isset($_SESSION["name"])) {
    $did = $_SESSION["name"];
    $fund = $_SESSION["ffund"];
    $sqlcat = "SELECT * from category where category_id='$did'";
    $querycat = mysqli_query($con, $sqlcat);
    while ($rowcat = mysqli_fetch_array($querycat)) {
        $catnameid = $rowcat['classname'];
    }
    $sql = "SELECT * from book where category_id='$did' and booktype='$fund' order by book_id asc";
    // unset($_SESSION["name"]);
    // unset($_SESSION["ffund"]);
} else {
    $sql = "SELECT * from book order by category_id asc";
}
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
$dyn_table .= '<table border="0" cellpadding="5" cellspacing="5" width="100%" >
<tr><td colspan="4"><h3>' . $catnameid . '</h3></td><td colspan="2">Date of Verification:</td></tr>
';
$query = mysqli_query($con, $sql);
$i = 0;
while ($row = mysqli_fetch_array($query)) {
    $id = $row['book_id'];
    $bn = $row['book_barcode'];
    $bn = htmlspecialchars($bn);

    if ($i % 6 == 0) {
        $dyn_table .= '<tr><td style="text-align: center; vertical-align: top; height:50px;">' . $bn . '</td>';
    } else {
        // echo $bn;
        $dyn_table .= '<td style="text-align: center; vertical-align: top; height:50px;">' . $bn . '</td>';
    }
    $i++;
}
$dyn_table .= '</tr>
<tr><td colspan="2">No. of Books not found</td><td colspan="4"></tr>
<tr><td colspan="2" height="70px">Remark for Not found</td><td colspan="4"></tr>
<tr><td colspan="3" height="50px" style="vertical-align:buttom">Library Staff</td><td colspan="3">Verified By</tr>
</table>';
// echo $dyn_table;
// unset($_SESSION["name"]);
// unset($_SESSION["ffund"]);


// echo $output;
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($dyn_table);
$fileName = rand() . '.pdf';
$mpdf->Output($fileName, 'I');
// $mpdf->Output($fileName, 'D');
unset($_SESSION["name"]);
unset($_SESSION["ffund"]);
