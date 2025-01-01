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
                        <h4 class="card-title">Library Catalogue</h4>
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

                            <!-- <button class="btn btn-danger rounded-0" type="button" onclick="PrintDiv();"><i class="fa fa-print"></i> Print</button> -->
                            <!--  -->

                            <!-- <div class="btn-group" role="group"><a href="#" class="btn btn-success btn-square text-white"><i class="fa fa-file color-info"></i> Export</a></div> -->
                        </div>
                    </div>
                    <div class="card-body">

                        <form class="form-inline" action="" method="post">
                            <label for="email" class="text-dark mr-3">Select Subject:</label>
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

                            <button type="submit" name="search" class="btn btn-primary btn-outline ml-2"><i class="fa fa-calendar-o"></i> View</button>
                        </form>
                        <!-- search area -->

                        <!-- search area -->
                        <div class="table-responsive mt-4" id="dvContents">
                            <?php

                            if (isset($_POST['search'])) {
                                $dept = $_POST['dept'];

                                // $_SESSION['name'] = $dept;
                                // $_SESSION['ffund'] = $fund;
                                //                                 echo '<form action="print_barcode_books.php" method="POST">
                                // <input type="text" name="ddept" value="' . $dept . '">
                                // </form>';
                                $sqli = "SELECT * FROM category where category_id='$dept'";
                                $result = mysqli_query($con, $sqli);
                                while ($row = mysqli_fetch_array($result)) {
                                    $deptname = $row['classname'];
                                }
                                $return_query = mysqli_query($con, "SELECT book.book_title,book.author,book.author_2,book.author_3,book.author_4,book.publisher_name,book.category_id,count(book.book_barcode) as aa ,category.* from book inner join category on book.category_id=category.category_id
                                where book.category_id='$dept' group by book.book_title") or die(mysqli_error($con));
                                $return_count = mysqli_num_rows($return_query);
                                $sum = 0;
                                $i = 0;

                                echo
                                '
                                <a class="btn btn-success rounded-0 btn-sm mb-3" href="print_book_catalog.php?id=' . $dept . '&subid=' . $deptname . '" target="_blank">
                                <i class="fa fa-print"></i> Print
                                </a>

                          ';
                                echo
                                '
                                <a class="btn btn-dark rounded-0 btn-sm mb-3" href="export_book_catalog.php?id=' . $dept . '&subid=' . $deptname . '">
                                <i class="fa fa-print"></i> Export to Excel
                                </a>

                          ';

                                echo '<table class="table table-outline table-bordered text-dark">
                                <thead><tr>
                                <th colspan="5"><h4 class="text-center">'
                                    . $deptname . '</h4></th>
                                </tr>
                                <tr>
                                <th>Sl.No</th>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Publisher</th>
                                <th>No. of Copies</th>
                                </tr></thead><tbody><tr>';
                                while ($return_row = mysqli_fetch_array($return_query)) {
                                    $sum += $return_row['aa'];


                                    echo
                                    '<td>' . ++$i . '</td>
                                    <td>' . $return_row['book_title'] . '</td>
                                    <td>' . $return_row['author'] . '</td>
                                    <td>' . $return_row['publisher_name'] . '</td>
                                    <td>' . $return_row['aa'] . '</td>
                                    
                                    
                                    </tr></tbody>';
                                }
                                if ($return_count <= 0) {
                                    echo '
                                        <table style="float:right;">
                                            <tr>
                                                <td style="padding:10px;" class="alert alert-danger text-white">No Books found at this moment</td>
                                            </tr>
                                        </table>
                                    ';
                                }
                                echo  '<tfoot>
								<tr>
									<th colspan="4">Total No of Books</th>
									<th>' . $sum . '</th></tr></tfoot>';
                                echo '</table>';
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