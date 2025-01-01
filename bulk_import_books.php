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
if (isset($_POST['save'])) {
    /*  $category=$_POST['category'];
  $code=$_POST['code'];
  $categoryvalue=$_POST['categorycode'];
  $sql="insert into tblcategory(CategoryName,CategoryCode,categoryvalue)values(:category,:code,:categoryvalue)";
  $query=$dbh->prepare($sql);
  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':code',$code,PDO::PARAM_STR);
  $query->bindParam(':categoryvalue',$categoryvalue,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$dbh->lastInsertId(); */

    $cart = array();
    $insertcount = 0;
    if ($_FILES['importfile']['name']) {
        $filename = explode(".", $_FILES['importfile']['name']);
        // $filename = explode(".", $_FILES['product_file']['name']);
        if (end($filename) == "csv") {

            $handle = fopen($_FILES['importfile']['tmp_name'], "r");
            $count = 0;
            while ($data = fgetcsv($handle)) {
                $count++;

                if ($count == 1) {
                    continue; // skip the heading header of sheet
                }
                $book_title = mysqli_real_escape_string($con, $data[0]);
                $category_id = mysqli_real_escape_string($con, $data[1]);
                $booktype = mysqli_real_escape_string($con, $data[2]);
                $author = mysqli_real_escape_string($con, $data[3]);
                $author_2 = mysqli_real_escape_string($con, $data[4]);
                $author_3 = mysqli_real_escape_string($con, $data[5]);
                $author_4 = mysqli_real_escape_string($con, $data[6]);
                $author_5 = mysqli_real_escape_string($con, $data[7]);
                $publisher_name = mysqli_real_escape_string($con, $data[10]);
                $copyright_year = mysqli_real_escape_string($con, $data[12]);
                $isbn = mysqli_real_escape_string($con, $data[11]);
                $price = mysqli_real_escape_string($con, $data[13]);
                $book_barcode = mysqli_real_escape_string($con, $data[15]);
                $bookaccno = mysqli_real_escape_string($con, $data[16]);
                $sourcefund = mysqli_real_escape_string($con, $data[21]);
                $invno = mysqli_real_escape_string($con, $data[22]);
                $vendoname = mysqli_real_escape_string($con, $data[24]);
                $invdate = mysqli_real_escape_string($con, date("Y-m-d", strtotime($data[23])));
                $accdate = mysqli_real_escape_string($con, date("Y-m-d", strtotime($data[17])));
                $query = "SELECT book_barcode FROM book WHERE book_barcode = '$data[15]'";
                $check = mysqli_query($con, $query);
                $row = mysqli_num_rows($check);
                if ($row > 0) {
                    mysqli_query($con, "UPDATE book SET book_title = '" . $book_title . "', category_id = '" . $category_id . "', booktype = '" . $booktype . "', author = '" . $author . "',
                    author_2 = '" . $author_2 . "',author_3 = '" . $author_3 . "',author_4 = '" . $author_4 . "',author_5 = '" . $author_5 . "',
                    publisher_name = '" . $publisher_name . "',copyright_year = '" . $copyright_year . "',isbn = '" . $isbn . "',
                    price = '" . $price . "',bookaccno = '" . $bookaccno . "',sourcefund = '" . $sourcefund . "',
                    invno = '" . $invno . "',invdate = '" . $invdate . "',accdate = '" . $accdate . "' WHERE book_barcode = '" . $book_barcode . "'");
                    array_push($cart, $data[15]);
                } else {

                    $query = "INSERT into book(book_title,category_id,booktype,author,author_2,author_3,author_4,author_5,book_copies,book_pub,publisher_name,
                    isbn,copyright_year,price,status,book_barcode,bookaccno,accdate,date_added,sourcefund,invno,invdate,vendoname,entryby) 
                    values('$book_title','$category_id','$booktype','$author','$author_2','$author_3','$author_4','$author_5','1','1',
                    '$publisher_name','$isbn','$copyright_year','$price','New','$book_barcode','$bookaccno','$accdate',Now(),'$sourcefund',
                    '$invno','$invdate','$vendoname','Admin')";
                    mysqli_query($con, $query);
                    // temp query
                    $queryrecent = "INSERT into temp_book(book_barcode,book_accno) values('$book_barcode','$bookaccno')";
                    mysqli_query($con, $queryrecent);
                    // temp query
                    $insertcount = $insertcount + 1;
                    $msg = true;
                    $_SESSION['message'] = "Book details added successfully.";
                    // echo '<script>alert("Voucher Imported successfully")</script>';
                    header("location: bulk_import_books.php?updation=1");
                }
            }
            //echo("Error description: " . mysqli_error($con));
            fclose($handle);
            if (isset($msg)) {
                $_SESSION['message'] = "Successfully Imported";
                $_SESSION['rowcount'] = $insertcount;
                // header('Location: index.php');
                exit(0);
            } else {
                $session['message'] = $cart;
            }

            //header("location: csv-book-insert.php?updation=1");

        } else {
            $_SESSION['message'] = 'Please Select CSV File only';
        }
        // header("location: csv-upload-update.php?updation=1");

    } else {
        echo '<script>alert("Something Went Wrong. Please try again")</script>';
        // echo "<script>window.location.href ='category.php'</script>";
    }
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
                        <h4 class="card-title">Bulk Import Books</h4>
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
                            <a href="Library_Book_Import_Bulk.csv" style="background:none;">
                                <button class="btn btn-info rounded-0"><i class="fa fa-print"></i> Download sample sheet</button>
                            </a>

                            <!-- <button class="btn btn-danger rounded-0" type="button" onclick="PrintDiv();"><i class="fa fa-print"></i> Print</button> -->
                            <!--  -->

                            <!-- <div class="btn-group" role="group"><a href="#" class="btn btn-success btn-square text-white"><i class="fa fa-file color-info"></i> Export</a></div> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        if (!empty($cart)) {
                            echo '<div class="alert alert-danger">';
                            foreach ($cart as $key => $item) {
                                echo "Book Barcode $item Exists<br>";
                            }
                            echo '</div>';
                        }
                        if (isset($_SESSION['message'])) {
                            echo "<h4 class='text-success'>" . $_SESSION['message'] . "</h4>";
                            unset($_SESSION['message']);
                        }
                        ?>
                        <form class="form-inline" action="" method="post" enctype="multipart/form-data">
                            <label for="email" class="text-dark mr-3">Select File(only CSV/XLS):</label>
                            <input type="file" name="importfile" id="importfile" class="form-control" required>


                            <button type="submit" name="save" class="btn btn-primary btn-outline ml-2"><i class="fa fa-calendar-o"></i> Upload</button>
                        </form>
                        <!-- search area -->

                        <!-- search area -->
                        <div class="table-responsive mt-4" id="dvContents">
                            <?php

                            if (isset($_GET["updation"])) {
                                // Open a file 
                                // echo "<table>\n\n";
                                // $file = fopen("a.csv", "r");
                                echo "No. of Record inserted";
                                if (isset($_SESSION['rowcount'])) {
                                    echo "<h3 class='text-danger'>" . $_SESSION['rowcount'] . "</h3>";
                                    unset($_SESSION['rowcount']);
                                }
                                // Fetching data from csv file row by row 
                                // while (($data = fgetcsv($file)) !== false) {

                                //     // HTML tag for placing in row format 
                                //     echo "<tr>";
                                //     foreach ($data as $i) {
                                //         echo "<td>" . htmlspecialchars($i)
                                //             . "</td>";
                                //     }
                                //     echo "</tr> \n";
                                // }

                                // Closing the file 
                                // fclose($file);

                                // echo "\n</table>";
                            }
                            ?>

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