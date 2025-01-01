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
                        <h4 class="card-title">Manage Books/Materials</h4>
                        <div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-square" data-toggle="dropdown"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="print_barcode1.php" target="_blank">Book Barcode</a>
                                    <a class="dropdown-item" href="book_print.php" target="_blank">Book List</a>
                                    <a class="dropdown-item" href="export_book.php?id=' . '1' . '&subid=' . '1' . '" target="_blank">Export Book List</a>
                                </div>
                            </div>
                            <!-- <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning dropdown-toggle btn-square" data-toggle="dropdown"><i class="fa fa-search" aria-hidden="true"></i> View</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void()">Dropdown link</a>
                                    <a class="dropdown-item" href="javascript:void()">Dropdown link</a>
                                </div>
                            </div> -->
                            <div class="btn-group" role="group"><a href="add_book.php" class="btn btn-success btn-square"><i class="fa fa-plus color-info"></i> Add new Book</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="offset-2">
                            <form class="form-inline" method="post" action="">

                                <div class="form-group mx-sm-2 mb-2 offset-2">
                                    <label class="sr-only">Search</label>
                                    <input type="text" name="search" class="form-control input-rounded" style="width: 550px;" placeholder="Password">
                                </div>
                                <button type="submit" name="submit" class="btn btn-danger btn-rounded mb-2">Search Book details</button>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <?php
                            $sqlcount = "SELECT COUNT(*) As total_records FROM book";
                            // SELECT * FROM book WHERE CONCAT(book_title, author, author_2,author_3,author_4,book_barcode,bookaccno,publisher_name) LIKE '%pearson%';
                            // new
                            if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
                                $page_no = $_GET['page_no'];
                            } else {
                                $page_no = 1;
                            }

                            $total_records_per_page = 30;
                            $offset = ($page_no - 1) * $total_records_per_page;
                            $previous_page = $page_no - 1;
                            $next_page = $page_no + 1;
                            $adjacents = "2";

                            $result_count = mysqli_query($con, $sqlcount);
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_pages = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_pages - 1; // total page minus 1
                            $sql = "SELECT * FROM book LIMIT $offset, $total_records_per_page";
                            if (isset($_POST['submit'])) {
                                $keyword = $_POST['search'];
                                // echo $keyword;
                                $sqlcount = "SELECT COUNT(*) As total_records FROM book WHERE CONCAT(book_title, author, author_2,author_3,author_4,book_barcode,bookaccno,publisher_name) LIKE '%$keyword%'";
                                $sql = "SELECT * FROM book WHERE CONCAT(book_title, author, author_2,author_3,author_4,book_barcode,bookaccno,publisher_name) LIKE '%$keyword%' LIMIT $offset, $total_records_per_page";
                            }

                            $result = mysqli_query($con, $sql);
                            ?>
                            <table class="table primary-table-bordered text-dark mt-3">

                                <thead class="thead-primary">
                                    <tr>
                                        <th style="width:100px;">Book Image</th>
                                        <th>Barcode</th>
                                        <th>Title</th>
                                        <th>ISBN</th>
                                        <th>Author/s</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    // $result = mysqli_query($con, "SELECT * from book  order by book_id DESC LIMIT 20") or die(mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id = $row['book_id'];
                                        $category_id = $row['category_id'];
                                        $cat_query = mysqli_query($con, "SELECT * from category where category_id = '$category_id'") or die(mysqli_error($con));
                                        $cat_row = mysqli_fetch_array($cat_query);
                                    ?>
                                        <tr>
                                            <td>
                                                <?php if ($row['book_image'] != "") : ?>
                                                    <img src="book_image/<?php echo $row['book_image']; ?>" class="img-thumbnail" width="65px" height="40px">
                                                <?php else : ?>
                                                    <img src="images/book_image.jpg" class="img-thumbnail" width="65px" height="40px">
                                                <?php endif; ?>
                                            </td>

                                            <td><a target="_blank" class="font-weight-bolder text-danger" href="print_barcode_individual1.php?code=<?php echo $row['book_barcode']; ?>">
                                                    <?php echo $row['book_barcode']; ?>
                                                </a></td>
                                            <td style="word-wrap: break-word; width: 10em;">
                                                <?php echo $row['book_title']; ?>
                                            </td>
                                            <td style="word-wrap: break-word; width: 10em;">
                                                <?php echo $row['isbn']; ?>
                                            </td>
                                            <td style="word-wrap: break-word; width: 10em;">
                                                <?php echo $row['author'] . "<br />" . $row['author_2'] . "<br />" . $row['author_3'] . "<br />" . $row['author_4'] . "<br />" . $row['author_5']; ?>
                                            </td>

                                            <td>
                                                <?php echo $cat_row['classname']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['status']; ?>
                                            </td>
                                            <td>
                                                <?php echo $row['remarks']; ?>
                                            </td>
                                            <td>
                                                <a href="edit_book.php?teamid=<?php echo $row['book_id']; ?>" class="btn btn-rounded btn-sm btn-primary">Edit</a>

                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                            <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
                                <strong>Page <?php echo $page_no . " of " . $total_no_of_pages; ?></strong>
                            </div>

                            <ul class="pagination pagination-sm pagination-circle float-right">


                                <li <?php if ($page_no <= 1) {
                                        echo "class='disabled page-item page-indicator'";
                                    } ?> class="page-item page-indicator">
                                    <a <?php if ($page_no > 1) {
                                            echo "href='?page_no=$previous_page'";
                                        } ?> class="page-link"><i class="icon-arrow-left"></i></a>
                                </li>

                                <?php
                                if ($total_no_of_pages <= 10) {
                                    for ($counter = 1; $counter <= $total_no_of_pages; $counter++) {
                                        if ($counter == $page_no) {
                                            echo "<li class='active page-item'><a>$counter</a></li>";
                                        } else {
                                            echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                        }
                                    }
                                } elseif ($total_no_of_pages > 10) {

                                    if ($page_no <= 4) {
                                        for ($counter = 1; $counter < 8; $counter++) {
                                            if ($counter == $page_no) {
                                                echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
                                            } else {
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                            }
                                        }
                                        echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                    } elseif ($page_no > 4 && $page_no < $total_no_of_pages - 4) {
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
                                        echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {
                                            if ($counter == $page_no) {
                                                echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
                                            } else {
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                            }
                                        }
                                        echo "<li class='page-item'><a class='page-link'>...</a></li>";
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
                                    } else {
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=1'>1</a></li>";
                                        echo "<li class='page-item'><a class='page-link' href='?page_no=2'>2</a></li>";
                                        echo "<li class='page-item'><a class='page-link'>...</a></li>";

                                        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
                                            if ($counter == $page_no) {
                                                echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";
                                            } else {
                                                echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
                                            }
                                        }
                                    }
                                }
                                ?>

                                <li <?php if ($page_no >= $total_no_of_pages) {
                                        echo "class='disabled page-item page-indicator'";
                                    } ?> class="page-item">
                                    <a <?php if ($page_no < $total_no_of_pages) {
                                            echo "href='?page_no=$next_page'";
                                        } ?> class='page-link'><i class="icon-arrow-right"></i></a>
                                </li>
                                <?php if ($page_no < $total_no_of_pages) {
                                    echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>Last</a></li>";
                                } ?>
                            </ul>

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