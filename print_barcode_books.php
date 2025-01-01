<?php
session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
}
// @include "includes/header.php";
$did = $_SESSION["name"];
$fund = $_SESSION["ffund"];
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
            border-collapse: collapse;
        }

        tr,
        td,
        th {
            border: 1px solid black;
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



            <button type="submit" id="print" onclick="printPage()">Print</button>

            <?php

            $dyn_table = '<table border="0" cellpadding="5" cellspacing="5" width="100%">';
            $query = mysqli_query($con, "SELECT * from book where category_id='$did' and booktype='$fund' order by book_id asc");
            $i = 0;
            while ($row = mysqli_fetch_array($query)) {
                $id = $row['book_id'];
                $bn = $row['book_barcode'];
                $bn = htmlspecialchars($bn);

                if ($i % 5 == 0) {
                    $dyn_table .= '<tr><td style="text-align: center; vertical-align: middle;"><img src ="includes/barcode.php?codetype=code128&size=38&text=' . htmlspecialchars($bn) . '&print=true" /></td>';
                } else {
                    // echo $bn;
                    $dyn_table .= '<td style="text-align: center; vertical-align: middle;"><img src ="includes/barcode.php?codetype=code128&size=38&text=' . strval($bn) . '&print=true" /></td>';
                }
                $i++;
            }
            $dyn_table .= '</tr></table>';
            echo $dyn_table;

            // dynamic print



            ?>

        </div>





    </div>
</body>


</html>