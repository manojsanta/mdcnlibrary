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
                        <h4 class="card-title">Recently Added Books/Materials</h4>
                        <div>
                            <div class="btn-group" role="group">
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
                            </div>
                            <div class="btn-group" role="group"><a href="add_book.php" class="btn btn-success btn-square"><i class="fa fa-plus color-info"></i> Add new Book</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <a class="btn btn-dark rounded-0 btn-sm mb-3" href="export_recent_book.php?id='1'">
                            <i class="fa fa-print"></i> Export to Excel
                        </a>
                        <a href="pdf_printbarcodeA3_recent.php" target="_blank" class="btn btn-warning text-white rounded-0 btn-sm mb-3">
                            <i class="fa fa-file color-white"></i> Export to PDF(A3)
                        </a>
                        <div class="table-responsive">
                            <table id="example" class="display text-dark">
                                <thead>
                                    <tr>
                                        <th>Sl.No</th>
                                        <th>Barcode</th>
                                        <th>Title</th>
                                        <th>ISBN</th>
                                        <th>Author/s</th>
                                        <th>Subject</th>
                                        <th>Added on</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($con, "SELECT temp_book.*,book.* from temp_book inner join book on temp_book.book_barcode=book.book_barcode ") or die(mysqli_error($con));
                                    $i = 1;
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id = $row['book_id'];
                                        $category_id = $row['category_id'];

                                        $cat_query = mysqli_query($con, "SELECT * from category where category_id = '$category_id'") or die(mysqli_error($con));
                                        $cat_row = mysqli_fetch_array($cat_query);
                                    ?>
                                        <tr>
                                            <td>
                                                <?php echo $i++; ?>
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
                                                <?php echo $row['date_added']; ?>
                                            </td>


                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
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