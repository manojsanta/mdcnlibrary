<?php
// $barcodeText = 'manoj';
// $barcodeType = 'Code39';
// $barcodeSize = '30';
// $barcodeDisplay = 'vertical';
// $printText = 'true';
// echo '<img  alt="' . $barcodeText . '"src="includes/barcode.php?text=' . $barcodeText . '&codetype=' . $barcodeType . '&orientation=' . $barcodeDisplay . '&size=' . $barcodeSize . '&print=' . $printText . '"/>';
include('includes/dbconnection.php');
require_once 'mpdf/autoload.php';
error_reporting(1);

// reference the Dompdf namespace

// use Dompdf\Dompdf;

//initialize dompdf class

// $document = new Dompdf();
// $document->set_options(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
$code = $_GET['code'];
$result1 = mysqli_query($con, "select * from user where libcardno = '$code' ") or die(mysqli_error($con));
$row1 = (mysqli_fetch_array($result1));
$bn = $row1['libcardno'];
$rollno = $row1['user_id'];
// echo "<img src = 'includes/barcode.php?codetype=Code39&size=30&text=" . $row1['libcardno'] . "&print=true' />";
$output = "<style>
    @page {
        margin: 1px;
    }

    .square {
        position: relative;
        width: 100px;
        border: 1px solid #32557f;
        padding-top:5px;
        height: 120px;
        text-align:center;
        margin:auto;
      }
      .square:after {
        content: '';
        display: block;
        padding-bottom: 70%;
      }
   
      #content {
        float: right ;
        width: 70% ;
      }

    .allb {
        border: 1px solid black;
        /* border-collapse: collapse; */
    }

    .ht {
        height: 20px;
    }

    .ht1 {
        height: 30px;
    }

    .buttom {
        border-bottom: 1px solid black;
        border-collapse: collapse;
    }

    table,th,td{
        padding: 1px;
        border-collapse: collapse;
        border-spacing: 0px;
        border: 1px solid black;
        border-spacing: 0;
        text-align:left;
        padding:4px;
      
        
    }

    .center1 {
        margin: auto;
        width: 50%;
        border: 3px solid green;
        padding: 10px;
    }
   
    .box {
        width: 100px;
  height: 100px;
 
  background-color: lightgrey;

  border: 1px solid green;
  padding: 50px;
  margin: 20px;
      }
      .no-border {
        border-collapse: collapse;
        border: none;
       
      }
      .row {
        // margin-left:-5px;
        // margin-right:-5px;
      }
      .row::after {
        content: '';
        clear: both;
        display: table;
       
      .column {
        float: left;
        width: 50%;
        padding: 5px;
      }
</style>";
$output .= '<table width="100%">
<tr><td align="center" colspan="3"><h4>STUDENT LIBRARY  PASSBOOOK</h4></td></tr>
<tr>
<td align="center" width="25%"><img src="images/weblogo.png" width="70" height="70"/></td>
<td align="center" valign="top" ><h3 style="margin-left:100px">MODEL DEGREE COLLEGE,NAYAGARH</h3></td>
<td align="center"><img src ="includes/barcode.php?codetype=code128&size=38&text=' . $bn . '&print=true" /></td>
</tr>
</table>';
$output .= '<table width="100%" height="100%">
<tr valigh="top">
<td width="100%" valign="top">
<table width="100%" cellspacing="0">
<tr><th>Name of the Student</th><th>' . strtoupper($row1['fullname']) . '</th><th>College Roll No.</th>
<th>' . strtoupper($row1['school_number']) . '</th><th>Honours Department</th><th>' . $row1['subject'] . '</td></tr>
<tr><th>Academic Session</th><th>' .  $row1['admyear'] . "-" . $row1['sessionend']  . '</td><th colspan="3">Contact Details</th><th>'
    . substr($row1['contact'], 0, 2)
    . str_repeat('*', (strlen($row1['contact']) - 4))
    . substr($row1['contact'], -2) .
    '</td></tr>
<tr><th>Space for Photograph</th></tr>
<tr ><th style="height:150px">
<div class="box">
                            <div>
                                Paste your photograph here!
                            </div>
                        </div>
</th>
<th>Renewal Details</th>
<td>
<table width="100%">
<tr>
<th>Year-1</th>
<th>Year-2</th>
<th>Year-3</th>
</tr>
<tr>';
$result2 = mysqli_query($con, "select * from libcard where libcardno = '$code' ") or die(mysqli_error($con));
$row2 = (mysqli_fetch_array($result2));
$output .= '<th>' . date("d-m-Y", strtotime($row2['issuedate'])) . '</th>
<th>' . $row2['renewaldate1'] . '</th>
<th>' . $row2['renewaldate2'] . '</th>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="3" style="height:100px" valign="bottom">
 Date of Issue : ' . date("d-m-Y", strtotime($row2['issuedate'])) . '</td>
<td colspan="3" valign="bottom">Librarin Sign.</td>
</tr>

</td>
</tr>
</table></td>
</tr><tr>
<td width="100%" valign="top">
<table width="100%">
<tr valign="top">
    <th>Acc No.</th>
    <th>Book Title</th>
    <th>Author</th>
    <th>Core/AECC/SEC</th>
    <th>Issue Date</th>
    <th>Date Return</th>
    <th>Lib Sign.</th>
    <th>Remark</th>
</tr>';
$query2 = mysqli_query($con, "SELECT borrow_book.*,book.* from borrow_book inner join book on borrow_book.book_id=book.book_id where borrow_book.user_id='$rollno'");
if (mysqli_num_rows($query2) > 0) {
    while ($row2 = mysqli_fetch_array($query2)) {
        $output .= ' <tr>
                                        <td>' . $row2['book_barcode'] . '</td>
                                        <td>' . $row2['book_title'] . '</td>
                                        <td>' . $row2['author'] . '</td>
                                        <td>' . '' . '</td>
                                        <td>' . date("d-m-Y", strtotime($row2['date_borrowed'])) . '</td>
                                         <td>';
        if ($row2['date_returned'] == NULL) {
            $dr = "";
        } else {
            $dr = date("d-m-Y", strtotime($row2['date_returned']));
        }
        $output .= $dr;
        $output .= '</td><td></td><td>'
            . $row2['borrowed_status'] . '</td></tr>';
    }
} else {
    // for ($row = 1; $row <= 20; $row++) {
    //     $output .= '<tr class=\ht1\>';
    //     for ($col = 1; $col <= 8; $col++) {
    //         $output .= "<td class=\" allb ht1\"></td> \n";
    //     }
    //     $output .= "</tr>";
    // }
}

$output .= '</table></td></tr></table>';
// <table align='center' cellspacing='0'>";
// $page = file_get_contents("cat.html");

// $output .= '<th colspan="3" align="center" valign="middle" style="padding-top:7px;"><img src ="includes/barcode.php?codetype=code128&size=38&text=' . $bn . '&print=true" /></td></tr>';
// $output .= '<td colspan="3" align="center" valign="middle" style="padding-top:7px;">' . strval($bn) . '</td>';
// $output .= "<img src='includes/barcode.php?codetype=Code39&size=30&text=" . $row1[' libcardno'] . "&print=true' />";


// echo $output;
$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$mpdf->WriteHTML($output);
$fileName = "LIBRARYPASSBOOK" . rand() . '.pdf';
$mpdf->Output($fileName, 'D');
// $mpdf->Output($fileName, 'D');