<?php

// session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (isset($_GET['id']) && isset($_GET['subid'])) {
    $id = $_GET['id'];
    $sub = $_GET['subid'];
}

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
            <br />
            <img src="images/weblogo.png" style=" margin-top:-10px; float:left; margin-left:115px; margin-bottom:-6px; width:100px; height:100px;">
            <img src="images/weblogo.png" style=" margin-top:-10px; float:right; margin-right:115px; width:100px; height:100px;">
            <center>
                <h4 style="font-style:Calibri">MODEL DEGREE COLLEGE,NAYAGARH</h4>
            </center>
            <center>
                <h5 style="font-style:Calibri; margin-top:-14px;">Library Management System</h5>
            </center>
            <center>
                <h5 style="font-style:Calibri; margin-top:-14px;">Lathipada,Nayagarh-752079</h5>
            </center>

            <button type="submit" id="print" onclick="printPage()">Print</button>
            <p class="text-center" style="text-align:center;margin-left:30px; margin-top:50px; font-size:14pt; font-weight:bold;">BOOKS CATALOG</p>
            <div align="right">
                <b style="color:blue;">Date Prepared:</b>
                <?php include('currentdate.php');
                ?>
            </div>
            <br />
            <?php
            $return_query = mysqli_query($con, "SELECT book.book_title,book.author,book.price,book.author_2,book.author_3,book.author_4,book.publisher_name,book.category_id,count(book.book_barcode) as aa ,category.* from book inner join category on book.category_id=category.category_id
							where book.category_id='$id' group by book.book_title") or die(mysqli_error($con));
            $return_count = mysqli_num_rows($return_query);
            // $count_penalty = mysqli_query($con,"SELECT sum(book_penalty) FROM return_book ")or die(mysqli_error());
            // $count_penalty_row = mysqli_fetch_array($count_penalty);
            ?>
            <table class="table table-striped">
                <!--<div class="pull-left">
                                    <div class="span"><div class="alert alert-info"><i class="icon-credit-card icon-large"></i>&nbsp;Total Amount of Penalty:&nbsp;<?php echo "Php " . $count_penalty_row['sum(book_penalty)'] . ".00"; ?></div></div>
                                </div>
								<br /> -->
                <thead>
                    <tr>
                        <th colspan="5" style="text-align:center !important">
                            <h3>
                                <?php
                                // if (isset($_POST['datefrom'])) {echo $_POST['datefrom'];}
                                // <!-- <span id="dept1"></span></th> -->
                                echo $sub; ?></h3>
                    </tr>
                    <tr>

                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Price</th>
                        <!---	<th>Author</th>
									<th>ISBN</th>	-->
                        <th>No. of Copies</th>

                        <!-- <th>Date Returned</th> -->
                        <!-- <th>Penalty</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sum = 0;
                    while ($return_row = mysqli_fetch_array($return_query)) {
                        // $id=$return_row['borrow_book_id'];
                        $sum += $return_row['aa'];
                    ?>
                        <tr>
                            <td style="text-align:center;"><?php echo $return_row['book_title']; ?></td>
                            <td style="text-transform: capitalize; text-align:center;"><?php echo $return_row['author']; ?></td>
                            <td style="text-transform: capitalize; text-align:center;"><?php echo $return_row['publisher_name']; ?></td>
                            <td style="text-transform: capitalize; text-align:center;"><?php echo $return_row['price']; ?></td>
                            <!---	<td style="text-transform: capitalize"><?php // echo $return_row['author']; 
                                                                            ?></td>
								<td><?php // echo $return_row['isbn']; 
                                    ?></td>	-->
                            <td style="text-align:center;"><?php echo $return_row['aa']; ?></td>

                        </tr>
                    <?php
                    }
                    if ($return_count <= 0) {
                        echo '
									<table style="float:right;">
										<tr>
											<td style="padding:10px;" class="alert alert-danger">No Books found at this moment</td>
										</tr>
									</table>
								';
                    }


                    ?>
                    </tr>
                </tbody>

            </table>
            <table class="table table-striped" style="width:100%">
                <tbody>
                    <tr>
                        <th colspan="4">Total No of Books</th>
                        <th><?php echo $sum; ?></th>
                    </tr>
                </tbody>
            </table>

            <br />
            <br />
            <?php
            include('include/dbcon.php');
            $user_query = mysqli_query($con, "select * from admin where admin_id='$id_session'") or die(mysqli_error($con));
            $row = mysqli_fetch_array($user_query); {
            ?> <h2><i class="glyphicon glyphicon-user"></i> <?php echo '<span style="color:blue; font-size:15px;">Prepared by:' . "<br /><br /> Library Admin ." . '</span>'; ?></h2>
            <?php } ?>


        </div>





    </div>
</body>


</html>