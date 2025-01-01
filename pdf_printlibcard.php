<?php
// $barcodeText = 'manoj';
// $barcodeType = 'Code39';
// $barcodeSize = '30';
// $barcodeDisplay = 'vertical';
// $printText = 'true';
// echo '<img  alt="' . $barcodeText . '"src="includes/barcode.php?text=' . $barcodeText . '&codetype=' . $barcodeType . '&orientation=' . $barcodeDisplay . '&size=' . $barcodeSize . '&print=' . $printText . '"/>';
include('includes/dbconnection.php');
require_once 'mpdf/autoload.php';
error_reporting(0);

// reference the Dompdf namespace

// use Dompdf\Dompdf;

//initialize dompdf class

// $document = new Dompdf();
// $document->set_options(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
$code = $_GET['code'];
$result1 = mysqli_query($con, "select * from user where libcardno = '$code' ") or die(mysqli_error($con));
$row1 = (mysqli_fetch_array($result1));
$bn = $row1['libcardno'];
// echo "<img src = 'includes/barcode.php?codetype=Code39&size=30&text=" . $row1['libcardno'] . "&print=true' />";
$output = "<style>
    @page {
        margin: 1px;
    }

    .square {
        position: relative;
        width: 90%;
        border: 1px solid #32557f;
        padding-top: 5px;
        text-align: center;
        height: 100px;

    }

    .content {
        position: absolute;
        width: 50%;
        height: 100%;
        padding: 6px;
        text-align: center;

    }

    .square:after {
        content: '';
        display: block;
        padding-bottom: 50%;
        text-align: center;
        margin-left: 10px;
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

    table {
        border-collapse: collapse;
        border-spacing: 0px;
        border: 1px solid black;
        padding: 1px;
        border-spacing: 0;
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
</style>
<table align='center' cellspacing='0'>";
// $page = file_get_contents("cat.html");
$output .= '<tr valign="top">
        <th colspan="3" scope="col"><b>MODEL DEGREE COLLEGE,NAYAGARH<br>LIBRARY CARD</b></th>
        <th colspan="2" align="right" valign="middle">LIBRARY CARD NO-</th>';
$output .= '<th colspan="3" align="center" valign="middle" style="padding-top:7px;"><img src ="includes/barcode.php?codetype=code128&size=38&text=' . $bn . '&print=true" /></td></tr>';
// $output .= '<td colspan="3" align="center" valign="middle" style="padding-top:7px;">' . strval($bn) . '</td>';
// $output .= "<img src='includes/barcode.php?codetype=Code39&size=30&text=" . $row1[' libcardno'] . "&print=true' />";
$output .= '<tr>
    <th colspan="2" align="left">Name of the Student</th>
    <th colspan="4" class="buttom">' . strtoupper($row1['fullname']) . '</th>
    <th rowspan="6" colspan="2" align="center">
   
  <div class="box">
                            <div>
                                Paste your photograph here!
                            </div>
                        </div>  </th>
</tr>
<tr>
    <th colspan="2" align="left">College Roll No : </th>
    <th colspan="4" class="buttom">' . strtoupper($row1['school_number']) . '</th>

</tr>
<tr>
    <th colspan="2" align="left">Department : </th>
    <th colspan="4" class="buttom">' . $row1['subject'] . '</th>
</tr>
<tr>
    <th colspan="2" align="left">Academic Session</th>
    <th colspan="4" class="buttom">' . $row1['admyear'] . "-" . $row1['sessionend'] . '</th>
</tr>
<tr>
    <th colspan="2" align="left">Contact</th>
    <th colspan="4" style="border-bottom: 1px solid black;
            border-collapse: collapse;">' . $row1['contact'] . '</th>
</tr>
<tr>
    <th colspan="2" align="left">Contact</th>
    <th colspan="4" style="border-bottom: 1px solid black;
            border-collapse: collapse;">' . $row1['contact'] . '</th>
</tr>


<tr>
    <td colspan="2" class="allb">Renewal Date</td>
    <td colspan="2" class="allb">YEAR-1</td>
    <td colspan="2" class="allb">YEAR-2</td>
    <td colspan="2" class="allb">YEAR-3</td>
</tr>

<tr class="allb">
    <td colspan="2" class="allb ht">';
$result2 = mysqli_query($con, "select * from libcard where libcardno = '$code' ") or die(mysqli_error($con));
$row2 = (mysqli_fetch_array($result2));
$output .= '</td>
    <td colspan="2" class="allb">' . date("d-m-Y", strtotime($row2['issuedate'])) . '</td>
    <td colspan="2" class="allb">' . $row2['renewaldate1'] . '</td>
    <td colspan="2" class="allb">' . $row2['renewaldate1'] . '</td>
</tr>
<tr>
    <td class="allb ht">Acc No.</td>
    <td class="allb ht">Book Title</td>
    <td class="allb ht">Author</td>
    <td class="allb ht">Core/AECC/SEC</td>
    <td class="allb ht">Date of Issue</td>
    <td class="allb ht">Date of Return</td>
    <td class="allb ht">Lib Sirgn.</td>
    <td class="allb ht">Remark</td>
</tr>';
for ($row = 1; $row <= 20; $row++) {
    $output .= '<tr class=\ht1\>';
    for ($col = 1; $col <= 8; $col++) {
        $output .= "<td class=\" allb ht1\">
            </td> \n";
    }
    $output .= "
    </tr>";
}
$output .= '<tr style="height:70px;margin-left:50px;">
        <td colspan="4" align="left" valign="bottom">
            <i>' . date("d-m-Y", strtotime($row2['issuedate'])) . '</i></br>Date of Issue
        </td>
        <td colspan="4" align="center" valign="bottom">Librarian</td>
    </tr>
</table>';
// echo $output;
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($output);
$fileName = rand() . '.pdf';
$mpdf->Output($fileName, 'I');
// $mpdf->Output($fileName, 'D');
