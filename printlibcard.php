<?php

include('includes/dbconnection.php');

$code = $_GET['code'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Degree College,Nayagarh!!!</title>
    <link href="fonts/css/font-awesome.min.css" rel="stylesheet">
    <script type="text/javascript">
        function PrintDiv() {

            var printContents = document.getElementById("dvContents").innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
    <style type="text/css" media="print">
        @page {
            size: auto;
            /* auto is the initial value */
            margin: 0mm;
            /* this affects the margin in the printer settings */
        }
    </style>
    <style>
        /* .square {
  height: 150px;
  width: 100px;
  /* background-color: #555; */
        .square {
            position: relative;
            width: 80%;
            border: 1px solid #32557f;
            padding-top: 5px;
            height: 100px;
            /* align:left; */
        }

        .square:after {
            content: "";
            display: block;
            padding-bottom: 70%;
        }

        .content {
            position: absolute;
            width: 50%;
            height: 100%;
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
            /* padding:1px;   border-spacing:0; */
        }

        .center1 {
            margin: auto;
            width: 50%;
            border: 3px solid green;
            padding: 10px;
        }
    </style>
</head>

<body>
    <center>
        <div><a href="#" onclick="PrintDiv()" style="text-decoration:none;color:inherit;"><i class="fa fa-print lg" style="font-size:24px;"></i>
                <!--  <input type="button"  onclick="PrintDiv()" value="Print"/> -->
                Print</a></div>
    </center>
    <div id="dvContents">
        <table align="center" cellspacing="0">
            <thead>
                <?php
                $result1 = mysqli_query($con, "select * from user where libcardno = '$code' ") or die(mysqli_error($con));
                $row1 = (mysqli_fetch_array($result1));
                // <!-- $code1=$row1['school_number'];
                // $code2=$row1['firstname']." ".$row1['middlename']." ".$row1['lastname'];
                // $code3=$row1['level']; -->
                ?>
                <tr valign="top">
                    <th colspan="3" scope="col"><b>MODEL DEGREE COLLEGE,NAYAGARH<br>LIBRARY CARD</b></th>
                    <th colspan="2" align="right" valign="middle">LIBRARY CARD NO-</th>
                    <th colspan="3" align="center" valign="middle" style="padding-top:7px;"><?php echo "<img src = 'includes/barcode.php?codetype=Code39&size=30&text=" . $row1['libcardno'] . "&print=true' />"; ?></th>
                </tr>
                <tr>
                    <th colspan="2" align="left">Name of the Student</th>
                    <th colspan="4" class="buttom"><?php echo strtoupper($row1['fullname']); ?></th>
                    <th rowspan="6" colspan="2" align="center">
                        <div class="square">
                            <div class="content">
                                Paste your photograph here!
                            </div>
                        </div>
                    </th>
                </tr>
                <tr>
                    <th colspan="2" align="left">College Roll No : </th>
                    <th colspan="4" class="buttom"><?php echo strtoupper($row1['school_number']); ?></th>
                    <!-- <th></th> -->
                </tr>
                <tr>
                    <th colspan="2" align="left">Department : </th>
                    <th colspan="4" class="buttom"><?php echo $row1['subject']; ?></th>
                    <!-- <th></th> -->
                </tr>
                <tr>
                    <th colspan="2" align="left">Academic Session</th>
                    <th colspan="4" class="buttom"><?php echo $row1['admyear'] . "-" . $row1['sessionend']; ?></th>
                </tr>
                <tr>
                    <th colspan="2" align="left">Contact</th>
                    <td colspan="4" class="buttom"><?php echo $row1['contact']; ?></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" class="allb">Renewal Date</td>
                    <td colspan="2" class="allb">YEAR-1</td>
                    <td colspan="2" class="allb">YEAR-2</td>
                    <td colspan="2" class="allb">YEAR-3</td>
                </tr>
                <tr class="allb">
                    <td colspan="2" class="allb ht">
                        <?php
                        $result2 = mysqli_query($con, "select * from libcard where libcardno = '$code' ") or die(mysqli_error($con));
                        $row2 = (mysqli_fetch_array($result2));
                        ?>
                    </td>
                    <td colspan="2" class="allb"><?php echo date("d-m-Y", strtotime($row2['issuedate'])); ?></td>
                    <td colspan="2" class="allb"><?php echo $row2['renewaldate1']; ?></td>
                    <td colspan="2" class="allb"><?php echo $row2['renewaldate1']; ?></td>
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
                </tr>
                <?php
                for ($row = 1; $row <= 20; $row++) {
                    echo "<tr class=\ht1\>\n";
                    for ($col = 1; $col <= 8; $col++) {

                        echo "<td class=\"allb ht1\">&nbsp</td> \n";
                    }
                    echo "</tr>";
                }
                ?>
                <tr style="height:70px;margin-left:50px;">
                    <td colspan="4" align="left" valign="bottom">
                        <i> <?php echo date("d-m-Y", strtotime($row2['issuedate'])) ?> </i></br>Date of Issue
                    </td>
                    <td colspan="4" align="center" valign="bottom">Librarian</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>