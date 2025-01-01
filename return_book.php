<?php
session_start();
error_reporting(0);
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
                        <h4 class="card-title">Borrowed Transaction</h4>
                    </div>
                    <div class="card-body">
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
                                        <th>Date Borrowed</th>
                                        <th>Due Date</th>
                                        <th>Penalty</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $borrow_query = mysqli_query($con, "SELECT borrow_book.*,book.* FROM borrow_book inner JOIN book ON borrow_book.book_id = book.book_id WHERE borrow_book.book_id = '$bookid' && borrow_book.borrowed_status = 'borrowed' order by borrow_book.borrow_book_id DESC") or die(mysqli_error($con));
                                    $borrow_count = mysqli_num_rows($borrow_query);
                                    while ($borrow_row = mysqli_fetch_array($borrow_query)) {
                                        $due_date = $borrow_row['due_date'];
                                        $timezone = "Asia/Kolkata";
                                        if (function_exists('date_default_timezone_set')) {
                                            date_default_timezone_set($timezone);
                                        }
                                        $cur_date = date("Y-m-d H:i:s");
                                        $date_returned = date("Y-m-d H:i:s");
                                        $penalty_amount_query = mysqli_query($con, "select * from penalty order by penalty_id DESC ") or die(mysqli_error($con));
                                        $penalty_amount = mysqli_fetch_assoc($penalty_amount_query);
                                        if ($date_returned > $due_date) {
                                            $penalty = round((float) (strtotime($date_returned) - strtotime($due_date)) / (60 * 60 * 24) * ($penalty_amount['penalty_amount']));
                                        } elseif ($date_returned < $due_date) {
                                            $penalty = 'No Penalty';
                                        } else {
                                            $penalty = 'No Penalty';
                                        }

                                    ?>
                                        <tr>
                                            <td><?php echo $borrow_row['book_barcode']; ?></td>
                                            <td style="text-transform: capitalize">
                                                <?php echo $borrow_row['book_title']; ?></td>
                                            <td style="text-transform: capitalize"><?php echo $borrow_row['author']; ?></td>
                                            <td><?php echo $borrow_row['isbn']; ?></td>
                                            <td><?php echo date("M d, Y h:m:s a", strtotime($borrow_row['date_borrowed'])); ?></td>
                                            <?php
                                            if ($borrow_row['status'] != 'Hardbound') {
                                                echo "<td>" . date('M d, Y h:m:s a', strtotime($borrow_row['due_date'])) . "</td>";
                                            } else {
                                                echo "<td>" . 'Hardbound Book, Inside Library Issue Only' . "</td>";
                                            }
                                            if ($borrow_row['status'] != 'Hardbound') {
                                                echo "<td>" . $penalty . "</td>";
                                            } else {
                                                echo "<td>" . 'Hardbound Book, Inside Library Issue Only' . "</td>";
                                            }
                                            ?>
                                            <td>
                                                <form method="post" action="">
                                                    <input type="hidden" name="date_returned" class="new_hidden" id="sd" value="<?php echo $date_returned ?>" size="16" maxlength="10" />
                                                    <input type="hidden" name="user_id" value="<?php echo $borrow_row['user_id']; ?>">
                                                    <input type="hidden" name="borrow_book_id" value="<?php echo $borrow_row['borrow_book_id']; ?>">
                                                    <input type="hidden" name="book_id" value="<?php echo $borrow_row['book_id']; ?>">
                                                    <input type="hidden" name="date_borrowed" value="<?php echo $borrow_row['date_borrowed']; ?>">
                                                    <input type="hidden" name="due_date" value="<?php echo $borrow_row['due_date']; ?>">
                                                    <button name="return" id="btnreturn" onclick="return confirm('Are you sure to Return the Book?')" type="submit" class="btn btn-sm btn-success btn-square text-white"><i class="fa fa-arrow-down"></i> Return</button>
                                                </form>
                                            </td>
                                        </tr>

                                    <?php  }
                                    if ($borrow_count <= 0) {
                                        echo '<table style="float:right;"><tr><td style="padding:10px;" class="alert alert-info">Books is in stock</td></tr></table>';
                                    } ?>

                                    <?php
                                    if (isset($_POST['return'])) {
                                        $user_id = $_POST['user_id'];
                                        $borrow_book_id = $_POST['borrow_book_id'];
                                        $book_id = $_POST['book_id'];
                                        $date_borrowed = $_POST['date_borrowed'];
                                        $due_date = $_POST['due_date'];
                                        $date_returned = $_POST['date_returned'];
                                        $update_copies = mysqli_query($con, "SELECT * from book where book_id = '$book_id' ") or die(mysqli_error($con));
                                        $copies_row = mysqli_fetch_assoc($update_copies);
                                        $book_copies = $copies_row['book_copies'];
                                        $new_book_copies = $book_copies + 1;
                                        if ($new_book_copies == '0') {
                                            $remark = 'Not Available';
                                        } else {
                                            $remark = 'Available';
                                        }
                                        mysqli_query($con, "UPDATE book SET book_copies = '$new_book_copies' where book_id = '$book_id'") or die(mysqli_error($con));
                                        mysqli_query($con, "UPDATE book SET remarks = '$remark' where book_id = '$book_id' ") or die(mysqli_error($con));
                                        $timezone = "Asia/Kolkata";
                                        if (function_exists('date_default_timezone_set')) {
                                            date_default_timezone_set($timezone);
                                        }

                                        $cur_date = date("Y-m-d H:i:s");
                                        $date_returned_now = date("Y-m-d H:i:s");
                                        //$due_date = strtotime($cur_date);
                                        //$due_date = strtotime("+3 day", $due_date);
                                        //$due_date = date('F j, Y g:i a', $due_date);
                                        ///$checkout = date('m/d/Y', strtotime("+1 day", strtotime($due_date)));

                                        $penalty_amount_query = mysqli_query($con, "select * from penalty order by penalty_id DESC ") or die(mysqli_error($con));
                                        $penalty_amount = mysqli_fetch_assoc($penalty_amount_query);

                                        if ($date_returned > $due_date) {
                                            $penalty = round((float) (strtotime($date_returned) - strtotime($due_date)) / (60 * 60 * 24) * ($penalty_amount['penalty_amount']));
                                        } elseif ($date_returned < $due_date) {
                                            $penalty = 'No Penalty';
                                        } else {
                                            $penalty = 'No Penalty';
                                        }

                                        mysqli_query($con, "UPDATE borrow_book SET borrowed_status = 'returned', date_returned = '$date_returned_now', book_penalty = '$penalty' WHERE borrow_book_id= '$borrow_book_id' and user_id = '$user_id' and book_id = '$book_id' ") or die(mysqli_error($con));
                                        mysqli_query($con, "INSERT INTO return_book (user_id, book_id, date_borrowed, due_date, date_returned, book_penalty) values ('$user_id', '$book_id', '$date_borrowed', '$due_date', '$date_returned', '$penalty')") or die(mysqli_error($con));

                                        $report_history1 = mysqli_query($con, "select * from admin where admin_id = $eid ") or die(mysqli_error($con));
                                        $report_history_row1 = mysqli_fetch_array($report_history1);
                                        $admin_row1 = $report_history_row1['firstname'] . " " . $report_history_row1['middlename'] . " " . $report_history_row1['lastname'];
                                        mysqli_query($con, "INSERT INTO report(book_id, user_id, admin_name, detail_action, date_transaction) VALUES ('$book_id','$user_id','$admin_row1','Returned Book',NOW())") or die(mysqli_error($con));

                                        echo "<script>alert('Book successfully returned to Library...');</script>";
                                        echo "<script>window.location.href ='return_book.php'</script>";
                                    }
                                    ?>


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
<?php include "includes/footer.php"; ?>