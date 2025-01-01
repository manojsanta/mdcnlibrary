<?php
// include('session.php');

include('includes/dbconnection.php');
$sub = "";
if (empty($_GET['id']) && empty($_GET['sub'])) {

    $type = $_GET['type'];
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
            table-layout: fixed;
        }

        tr,
        td,
        th {
            border: 1px solid black;
            overflow: hidden;
            /* word-wrap: break-word; */
            word-wrap: break-word;
            word-break: break-all;
            white-space: normal;
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
                <h5 style="font-style:Calibri"></h5>&nbsp; &nbsp;&nbsp; MODEL DEGREE COLLEGE,NAYAGARH &nbsp; &nbsp;
            </center>
            <center>
                <h5 style="font-style:Calibri; margin-top:-14px;"></h5> &nbsp; &nbsp; Library Management System
            </center>
            <center>
                <h5 style="font-style:Calibri; margin-top:-14px;"></h5> Lathipada,Nayagarh-752079
            </center>

            <button type="submit" id="print" onclick="printPage()">Print</button>
            <p class="text-center" style="text-align:center;margin-left:30px; margin-top:50px; font-size:14pt; font-weight:bold;">ACCESSION REGISTER</p>
            <div align="right">
                <b style="color:blue;">Date Prepared:</b>
                <?php include('currentdate.php');
                ?>
            </div>
            <br />
            <?php
            $return_query = mysqli_query($con, $string);
            // "SELECT book.book_title,book.author,book.price,book.author_2,book.author_3,book.author_4,book.publisher_name,book.category_id,count(book.book_barcode) as aa ,category.* from book inner join category on book.category_id=category.category_id
            // where book.category_id='$id' group by book.book_title") or die (mysqli_error());
            $return_count = mysqli_num_rows($return_query);
            // $count_penalty = mysqli_query($con,"SELECT sum(book_penalty) FROM return_book ")or die(mysqli_error());
            // $count_penalty_row = mysqli_fetch_array($count_penalty);
            ?>
            <table class="table table-striped" width="100%">
                <!--<div class="pull-left">
                                    <div class="span"><div class="alert alert-info"><i class="icon-credit-card icon-large"></i>&nbsp;Total Amount of Penalty:&nbsp;<?php echo "Php " . $count_penalty_row['sum(book_penalty)'] . ".00"; ?></div></div>
                                </div>
								<br /> -->
                <thead>


                    <?php
                    // if (isset($_POST['datefrom'])) {echo $_POST['datefrom'];}
                    // <!-- <span id="dept1"></span></th> -->
                    if (!empty($sub)) {
                        echo    "<tr>
								<th colspan='8' style='text-align:center !important'>
								<h3>$sub</h3></tr>";
                    }
                    // echo $sub;
                    ?></h3>

                    <tr>

                        <th width="45px">Sl No#</th>
                        <th width="135px">Accession No.</th>
                        <th width="95px">Barcode No.</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <!---	<th>Author</th>
									<th>ISBN</th>	-->
                        <th width="90px">Price</th>
                        <th width="100px">Purchase Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sum = 0;
                    $i = 0;
                    while ($return_row = mysqli_fetch_array($return_query)) {
                        // $id=$return_row['borrow_book_id'];
                        $sum += $return_row['book_copies'];
                        // $sum+=$return_row['aa'];
                    ?>
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td style="text-transform:capitalize"><?php echo $return_row['bookaccno']; ?></td>
                            <td style="text-transform:capitalize"><?php echo $return_row['book_barcode']; ?></td>
                            <td style="text-transform:capitalize"><?php echo $return_row['book_title']; ?></td>
                            <td style="text-transform:capitalize"><?php echo $return_row['author'] . ',' . $return_row['author_2'] . ',' . $return_row['author_3'] . ',' . $return_row['author_4']; ?></td>
                            <td style="text-transform:capitalize"><?php echo $return_row['publisher_name']; ?></td>
                            <!---	<td style="text-transform: capitalize"><?php // echo $return_row['author']; 
                                                                            ?></td>
								<td><?php // echo $return_row['isbn']; 
                                    ?></td>	-->
                            <td><?php echo $return_row['price']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($return_row['invdate'])); ?></td>


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
                <tfoot>
                    <tr>
                        <th colspan="7">Total No of Books</th>
                        <th><?php echo $sum; ?></th>
                    </tr>
                </tfoot>
            </table>

            <br />

            <h2><i class="glyphicon glyphicon-user"></i> <?php echo '<span style="color:blue; font-size:15px;">Prepared by:' . "<br /><br /> Library Admin ." . '</span>'; ?></h2>



        </div>





    </div>
</body>


</html>