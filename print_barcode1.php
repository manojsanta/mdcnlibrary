<?php
@session_start();
include('includes/dbconnection.php');
include('includes/barcode128.php');
error_reporting(0);
?>
<html>

<head>
    <title>Library Management System</title>

    <style>
        .container {
            width: 100%;
            margin: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
        }

        .table-striped tbody>tr:nth-child(odd)>td,
        .table-striped tbody>tr:nth-child(odd)>th {
            background-color: #f9f9f9;
        }

        @media print {
            #print {
                display: none;
            }
        }

        #print {
            width: 90px;
            height: 30px;
            font-size: 18px;
            background: white;
            border-radius: 4px;
            margin-left: 28px;
            cursor: hand;
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>

</head>


<body>
    <div class="container">
        <div id="header">
            <br />
            <img src="images/weblogo.png" style=" margin-top:-17px; float:left; margin-left:115px; margin-bottom:-6px; width:100px; height:100px;">
            <img src="images/weblogo.png" style=" margin-top:-17px; float:right; margin-right:115px; width:100px; height:100px;">
            <center>
                <h5 style="font-style:Calibri">LIBRARY MANAGEMENT SYSTEM</h5>
            </center>
            <center>
                <h5 style="font-style:Calibri; margin-top:-14px;">Model Degree COllege,Nayagarh</h5>
            </center>
            <center>
                <h5 style="font-style:Calibri; margin-top:-14px;">Lathipada,Nayagarh-752079</h5>
            </center>

            <button type="submit" id="print" onclick="printPage()">Print</button>
            <p style="margin-left:30px; margin-top:50px; font-size:14pt; font-weight:bold;">Books Barcode&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            <div align="right">
                <b style="color:blue;">Date Prepared:</b>
                <?php include('currentdate.php'); ?>
            </div>
            <br />
            <br />
            <br />
            <?php
            $result = mysqli_query($con, "select * from book LEFT JOIN category ON book.category_id = category.category_id 
							order by book_id DESC") or die(mysqli_error($con));
            ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Barcode Image</th>
                        <th>Barcode</th>
                        <th>Title</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $id = $row['book_id'];
                    ?>
                        <tr>
                            <!-- <td style="text-align:cent 'include/barcode.php?filetype=PNG&dpi=72&scale=1&rotation=0&font_family=Arial.ttf&font_size=10&text=".$row['book_barcode']."&thickness=50&start=NULL&code=BCGcode128' />";?></td> -->
                            <td style="text-align:center;">
                                <?php echo "<img alt='testing' src='includes/barcode.php?codetype=Code39&size=40&text=" . $row['book_barcode'] . "&print=true'/>"; ?>
                            </td>
                            <!-- <td style="text-align:center;"><echo bar128(stripcslashes($row['book_barcode']));?></td> -->
                            <td style="text-align:center;"><?php echo $row['book_barcode']; ?></td>
                            <td style="text-align:center;"><?php echo $row['book_title']; ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>

            <br />
            <br />
            <?php
            include('includes/dbconnection.php');
            include('includes/session.php');
            $user_query = mysqli_query($con, "select * from admin where admin_id='$id_session'") or die(mysqli_error($con));
            $row = mysqli_fetch_array($user_query); {
            ?> <h2><i class="glyphicon glyphicon-user"></i> <?php echo '<span style="color:blue; font-size:15px;">Prepared by:' . "<br /><br /> " . $row['firstname'] . " " . $row['lastname'] . " " . '</span>'; ?></h2>
            <?php } ?>


        </div>





    </div>
</body>


</html>