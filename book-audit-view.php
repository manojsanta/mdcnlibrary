<!--**********************************
            Content body start
        ***********************************-->
<?php
session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
}
@include "includes/header.php"; ?>
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script> -->
<div class="content-body">
    <!-- row -->
    <div class="container-fluid">
        <!-- hammerberg -->
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <?php
                    $eid = $_SESSION['sid'];
                    $sql = "SELECT * from admin where admin_id=:eid ";
                    $query = $dbh->prepare($sql);
                    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    $cnt = 1;
                    if ($query->rowCount() > 0) {
                        foreach ($results as $row) {
                    ?>
                            <h4>Hi, welcome back!</h4>
                            <span class="ml-1"><?php echo ($row->firstname); ?> <?php echo ($row->lastname); ?></span>

                    <?php
                        }
                    } ?>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Library</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">MDC,Nayagarh</a></li>
                </ol>
            </div>
        </div>
        <!-- hammerberg ends -->
        <!-- data area -->
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Books Audit Sheet Print</h4>
                        <div>

                            <!-- <div class="btn-group" role="group">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-square" data-toggle="dropdown"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="print_barcode1.php" target="_blank">Book Barcode</a>
                                    <a class="dropdown-item" href="book_print.php" target="_blank">Book List</a>
                                </div>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning dropdown-toggle btn-square" data-toggle="dropdown"><i class="fa fa-search" aria-hidden="true"></i> View</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void()">Dropdown link</a>
                                    <a class="dropdown-item" href="javascript:void()">Dropdown link</a>
                                </div>
                            </div> -->
                            <!-- <a href="print_barcode_books.php" target="_blank" style="background:none;">
                                <button class="btn btn-info rounded-0"><i class="fa fa-print"></i> X Print</button>
                            </a> -->
                            <a href="pdf_printbookaudit.php" target="_blank" style="background:none;">
                                <button class="btn btn-success rounded-0"><i class="fa fa-file color-white"></i> Export to PDF</button>
                            </a>
                            <!-- <a href="pdf_printbarcodeA3.php" target="_blank" style="background:none;">
                                <button class="btn btn-warning rounded-0"><i class="fa fa-file color-white"></i> Export to PDF(A3)</button>
                            </a> -->
                            <button class="btn btn-danger rounded-0" type="button" onclick="PrintDiv();"><i class="fa fa-print"></i> Print</button>
                            <!--  -->

                            <!-- <div class="btn-group" role="group"><a href="#" class="btn btn-success btn-square text-white"><i class="fa fa-file color-info"></i> Export</a></div> -->
                        </div>
                    </div>
                    <div class="card-body">

                        <form class="form-inline" action="" method="post">
                            <label for="email" class="text-dark">Select Subject:</label>
                            <select class="form-control m-2" required name="dept">
                                <option value="">Select Department</option>
                                <?php

                                $sqli = "SELECT * FROM category";
                                $result = mysqli_query($con, $sqli);
                                while ($row = mysqli_fetch_array($result)) {
                                    echo '<option value="' . $row['category_id'] . '">' . $row['classname'] . '</option>';
                                }
                                ?>
                            </select> <!-- <input type="email" class="form-control" id="email" placeholder="Enter Username" name="email"> -->
                            <label for="pwd" class="text-dark mr-2">Select Caterory:</label>
                            <select class="form-control m-2" required name="fund">
                                <option value="">--Select Source of Book--</option>
                                <option value="T">New Purchase</option>
                                <option value="SP">Specimen/Donation</option>
                                <option value="O">Old Books</option>
                                <option value="OSP">Old Specimen/Donation</option>
                            </select>
                            <button type="submit" name="search" class="btn btn-primary btn-outline ml-2"><i class="fa fa-calendar-o"></i> Search</button>
                        </form>
                        <!-- search area -->

                        <!-- search area -->
                        <div class="table-responsive mt-4 text-dark" id="dvContents">
                            <?php

                            if (isset($_POST['search'])) {
                                $dept = $_POST['dept'];
                                $fund = $_POST['fund'];
                                $_SESSION['name'] = $dept;
                                $_SESSION['ffund'] = $fund;
                                //                                 echo '<form action="print_barcode_books.php" method="POST">
                                // <input type="text" name="ddept" value="' . $dept . '">
                                // </form>';


                                // $where = " and (date(borrow_book.date_borrowed) between '" . date("Y-m-d", strtotime($_GET['datefrom'])) . "' and '" . date("Y-m-d", strtotime($_GET['dateto'])) . "' ) ";

                                //    dynamic print



                                $dyn_table = '<table border="1" cellpadding="10"  cellspacing="12" width="100%" >';
                                $query = mysqli_query($con, "SELECT * from book where category_id='$dept' and booktype='$fund' order by book_id asc");
                                $i = 0;
                                while ($row = mysqli_fetch_array($query)) {
                                    $id = $row['book_id'];
                                    $bn = $row['book_barcode'];
                                    $bn = htmlspecialchars($bn);
                                    //$bn=<img src = 'includes/barcode.php?codetype=Code39&size=30&text=".$row3['barcode']."&print=true' />;
                                    if ($i % 5 == 0) {
                                        //$dyn_table.='<tr><td>'.$bn.'</td>';
                                        // echo $bn;
                                        $dyn_table .= '<tr><td style="text-align: center; vertical-align: top;height:70px">' . htmlspecialchars($bn) . '</td>';
                                    } else {
                                        // echo $bn;
                                        $dyn_table .= '<td style="text-align: center; vertical-align: top;height:70px">' . strval($bn) . '</td>';
                                    }
                                    $i++;
                                }
                                $dyn_table .= '</tr></table>';
                                echo $dyn_table;

                                // dynamic print

                            } ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- data area ned -->


    </div>
</div>
<!--**********************************
            Content body end
        ***********************************-->

<?php include "includes/footer.php"; ?>
<script type="text/javascript">
    function PrintDiv() {

        var printContents = document.getElementById("dvContents").innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
<script>
    var doc = new jsPDF();
    var specialElementHandlers = {
        '#print-btn': function(element, renderer) {
            return true;
        }
    };

    $('#submit').click(function() {
        doc.fromHTML($('#dvContents').html(), 15, 15, {
            'width': 700,
            'elementHandlers': specialElementHandlers
        });
        doc.save('pdf-version.pdf');
    });
</script>