<?php
session_start();
error_reporting(1);
date_default_timezone_set('Asia/Kolkata');
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
}
if (isset($_POST['bsearch'])) {
    $bookid = $_POST['school_number'];
    // echo "SELECT * FROM borrow_book inner JOIN book ON borrow_book.book_id = book.book_id WHERE borrow_book.book_id = '$bookid' && borrow_book.borrowed_status = 'borrowed' order by borrow_book.borrow_book_id DESC";

    // $borrow_count = mysqli_num_rows($borrow_query);

}
// $school_number = $_GET['school_number'];
// $user_query = mysqli_query($con, "SELECT * FROM user WHERE school_number = '$school_number' ");
// $user_row = mysqli_fetch_array($user_query);
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
                        <h4 class="card-title">Book Status</h4>
                        <!-- <a href="borrow.php" class="btn btn-primary btn-rounded"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                            BACK</a> -->
                    </div>
                    <div class="card-body">
                        <!-- borrow area starta -->
                        <div class="col-md-12 mb-4">
                            <div class="basic-form">

                                <form method="post" action="">
                                    <!-- <input type="text" class="form-control input-rounded" placeholder="input-rounded"> -->
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <select name="school_number" id="single-select" class="form-control" required="required" tabindex="-1">
                                                <option value="0">Select Book Number</option>
                                                <?php
                                                $result = mysqli_query($con, "select * from book") or die(mysqli_error($con));
                                                while ($row = mysqli_fetch_array($result)) {
                                                    // $id = $row['user_id'];
                                                ?>
                                                    <option value="<?php echo $row['book_id']; ?>"><?php echo $row['book_barcode']; ?> - <?php echo $row['book_title']; ?></option>
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="col-sm-6 mt-2 mt-sm-0">
                                            <!-- <input type="text" class="form-control" placeholder="Last name"> -->
                                            <button class="btn btn-primary" name="bsearch" type="submit">search</button>
                                        </div>

                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- borrr ends -->
                        <!-- search area -->

                        <div class="row table-responsive">
                            <table class="table display text-default" cellpadding="0">
                                <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>ISBN</th>
                                        <th>Publisher</th>
                                        <th>Subject</th>
                                        <th>Status</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $borrow_query = mysqli_query($con, "SELECT category.classname as cc,book.* FROM book inner JOIN category ON book.category_id = category.category_id WHERE book.book_id = '$bookid'") or die(mysqli_error($con));
                                    $borrow_count = mysqli_num_rows($borrow_query);
                                    while ($borrow_row = mysqli_fetch_array($borrow_query)) {
                                        $due_date = $borrow_row['due_date'];
                                        $timezone = "Asia/Kolkata";
                                        if (function_exists('date_default_timezone_set')) {
                                            date_default_timezone_set($timezone);
                                        }
                                        $cur_date = date("Y-m-d H:i:s");
                                        $date_returned = date("Y-m-d H:i:s");



                                    ?>
                                        <tr>
                                            <td><?php echo $borrow_row['book_barcode']; ?></td>
                                            <td style="text-transform: capitalize">
                                                <?php echo $borrow_row['book_title']; ?></td>
                                            <td style="text-transform: capitalize"><?php echo $borrow_row['author']; ?></td>
                                            <td><?php echo $borrow_row['isbn']; ?></td>
                                            <td><?php echo $borrow_row['publisher_name']; ?></td>
                                            <td><?php echo $borrow_row['cc']; ?></td>
                                            <td>
                                                <?php if ($borrow_row['remarks'] == 'Available') { ?>
                                                    <span class="badge badge-pill badge-primary"><?php echo $borrow_row['remarks']; ?></span>
                                                <?php } else { ?>
                                                    <span class="badge badge-pill badge-danger"><?php echo $borrow_row['remarks']; ?></span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php  }
                                    if ($borrow_count <= 0) {
                                        echo '<table style="float:right;"><tr><td style="padding:10px;" class="alert alert-info">Books is in stock</td></tr></table>';
                                    } ?>
                                    <!--  -->

                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <!-- data area ned -->
        </div>
    </div>
    <!--**********************************       Content body end***********************************-->

    <?php include "includes/footer.php"; ?>