<?php
session_start();
error_reporting(1);
date_default_timezone_set('Asia/Kolkata');
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
}
$school_number = $_GET['school_number'];
$user_query = mysqli_query($con, "SELECT * FROM user WHERE school_number = '$school_number' ");
$user_row = mysqli_fetch_array($user_query);
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
                        <a href="borrow.php" class="btn btn-primary btn-rounded"><i class="fa fa-arrow-left" aria-hidden="true"></i>
                            BACK</a>
                    </div>
                    <div class="card-body">
                        <div id="arear">
                            <?php
                            $sql = mysqli_query($con, "SELECT * FROM user WHERE school_number = '$school_number' ");
                            $row = mysqli_fetch_array($sql);
                            ?>
                            <h4>
                                Borrower Name : <span class="text-primary"><?php echo $row['fullname'] . " /" . $row['school_number'] . " " . $row['subject']; ?></span>
                            </h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table display" cellpadding="0" id="example">
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
                                    $borrow_query = mysqli_query($con, "SELECT * FROM borrow_book LEFT JOIN book ON borrow_book.book_id = book.book_id WHERE user_id = '" . $user_row['user_id'] . "' && borrow_book.borrowed_status = 'borrowed' order by borrow_book.borrow_book_id DESC") or die(mysqli_error($con));
                                    $borrow_count = mysqli_num_rows($borrow_query);
                                    while ($borrow_row = mysqli_fetch_array($borrow_query)) {
                                        $due_date = $borrow_row['due_date'];
                                        $timezone = "Asia/Kolkata";
                                        if (function_exists('date_default_timezone_set')) {
                                            date_default_timezone_set($timezone);
                                        }
                                        $cur_date = date("Y-m-d H:i:s");
                                        $date_returned = date("Y-m-d H:i:s");

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
                                                    <button name="return" id="btnreturn" onclick="return confirm('Are you sure to Return the Book?')" type="submit" class="btn btn-sm btn-success btn-square "><i class="fa fa-arrow-down"></i> Return</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php
                                    } ?>
                                    <?php
                                    if ($borrow_count <= 0) {
                                        echo '<table style="float:right;"><tr><td style="padding:10px;" class="alert alert-danger">No books borrowed</td></tr></table>';
                                    }
                                    ?>
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

                                        // echo "<script>alert('same message');</script>";   
                                    ?>
                                        <script>
                                            window.location = "borrow_book.php?school_number=<?php echo $school_number ?>";
                                        </script>
                                    <?php }
                                    ?>

                                </tbody>

                            </table>
                        </div>
                        <!-- return area ends -->
                        <!-- borrow area starta -->
                        <div class="row">
                            <div class="basic-form col-sm-6 offset-2">
                                <form method="post" action="">
                                    <div class="form-group">
                                        <input type="text" class="form-control input-rounded" name="barcode" placeholder="Enter barcode here....." autofocus required />
                                        <!-- <input type="text" class="form-control input-rounded" placeholder="input-rounded"> -->
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive">
                                <table class="table display" id="example">
                                    <thead>
                                        <form method="post" action="">
                                            <tr>
                                                <th>Barcode</th>
                                                <th>Book Title</th>
                                                <th>Author </th>
                                                <th>Publisher</th>
                                                <th>ISBN</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_POST['barcode'])) {
                                            $barcode = strtoupper($_POST['barcode']);
                                            $book_query = mysqli_query($con, "SELECT * FROM book WHERE book_barcode = '$barcode' ") or die(mysqli_error($con));
                                            $book_count = mysqli_num_rows($book_query);
                                            $book_row = mysqli_fetch_array($book_query);
                                            if ($book_row['book_barcode'] != $barcode) {
                                                echo '<table><tr><td class="alert alert-info">No match for the barcode entered!</td></tr></table>';
                                            } elseif ($barcode == '') {
                                                echo '<table>
												<tr>
													<td class="alert alert-info">Enter the correct details!</td>
												</tr></table>';
                                            } else {
                                        ?>
                                                <tr>
                                                    <input type="hidden" name="user_id" value="<?php echo $user_row['user_id'] ?>">
                                                    <input type="hidden" name="book_id" value="<?php echo $book_row['book_id'] ?>">
                                                    <td>
                                                        <?php if ($book_row['book_image'] != "") : ?>
                                                            <img src="upload/<?php echo $book_row['book_image']; ?>" width="80px" height="80px" style="border:4px groove #CCCCCC; border-radius:5px;">
                                                        <?php else : ?>
                                                            <img src="images/book_image.jpg" width="90px" height="90px" style="border:4px groove #CCCCCC; border-radius:5px;">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $book_row['book_barcode'] ?></td>
                                                    <td style="text-transform: capitalize"><?php echo $book_row['book_title'] ?></td>
                                                    <td style="text-transform: capitalize"><?php echo $book_row['author'] ?></td>
                                                    <td><?php echo $book_row['isbn'] ?></td>
                                                    <td><?php echo $book_row['remarks'] ?></td>
                                                    <td>
                                                        <label class="col-form-label">Borrow Date <span class="required" style="color:red;">*</span></label>
                                                        <input type="datetime-local" name="borrowdate" class="form-control" required /></br>
                                                        <button name="borrow" class="btn btn-secondary btn-rounded" onclick="return confirm('Are you sure to Issue the Book?')"><i class="fa fa-check"></i> Borrow</button>
                                                    </td>
                                                </tr> <?php }
                                                } ?>

                                        <?php
                                        $allowable_days_query = mysqli_query($con, "select * from allowed_days order by allowed_days_id DESC ") or die(mysqli_error($con));
                                        $allowable_days_row = mysqli_fetch_assoc($allowable_days_query);
                                        $timezone = "Asia/Kolkata";
                                        if (function_exists('date_default_timezone_set')) {
                                            date_default_timezone_set($timezone);
                                        }

                                        $allowdays = $allowable_days_row['no_of_days'];

                                        ?>
                                        <input type="hidden" name="allowdays" class="new_text" id="sd" value="<?php echo $allowdays ?>" size="16" maxlength="10" />
                                        <?php
                                        if (isset($_POST['borrow'])) {
                                            $user_id = $_POST['user_id'];
                                            $book_id = $_POST['book_id'];
                                            $allowdays = $_POST['allowdays'];
                                            // $date_borrowed =$_POST['date_borrowed'];
                                            $date_borrowed = $_POST['borrowdate'];
                                            $due_date = strtotime($date_borrowed);
                                            // $date_borrowed = date("Y-m-d H:i:s");
                                            // $date_borrowed =date('Y-m-d H:i:s',$_POST['borrowdate']);
                                            $due_date = strtotime("+" . $allowdays . " day", $due_date);
                                            // $due_date =$_POST['due_date'];
                                            $due_date = date('Y-m-d H:i:s', $due_date);
                                            $trapBookCount = mysqli_query($con, "SELECT count(*) as books_allowed from borrow_book where user_id = '$user_id' and borrowed_status = 'borrowed'") or die(mysqli_error($con));
                                            $countBorrowed = mysqli_fetch_assoc($trapBookCount);
                                            $bookCountQuery = mysqli_query($con, "SELECT count(*) as book_count from borrow_book where user_id = '$user_id' and borrowed_status = 'borrowed' and book_id = $book_id") or die(mysqli_error($con));
                                            $bookCount = mysqli_fetch_assoc($bookCountQuery);
                                            $allowed_book_query = mysqli_query($con, "select * from allowed_book order by allowed_book_id DESC ") or die(mysqli_error($con));
                                            $allowed = mysqli_fetch_assoc($allowed_book_query);
                                            if ($countBorrowed['books_allowed'] == $allowed['qntty_books']) {
                                                echo "<script>alert(' " . $allowed['qntty_books'] . " " . 'Books Allowed per User!' . " '); window.location='borrow_book.php?school_number=" . $school_number . "'</script>";
                                            } elseif ($bookCount['book_count'] == 1) {
                                                echo "<script>alert('Book Already Borrowed!'); window.location='borrow_book.php?school_number=" . $school_number . "'</script>";
                                            } else {

                                                $update_copies = mysqli_query($con, "SELECT * from book where book_id = '$book_id' ") or die(mysqli_error($con));
                                                $copies_row = mysqli_fetch_assoc($update_copies);

                                                $book_copies = $copies_row['book_copies'];
                                                $new_book_copies = $book_copies - 1;

                                                if ($new_book_copies < 0) {
                                                    echo "<script>alert('Book out of Copy!'); window.location='borrow_book.php?school_number=" . $school_number . "'</script>";
                                                } elseif ($copies_row['status'] == 'Damaged') {
                                                    echo "<script>alert('Book Cannot Borrow At This Moment!'); window.location='borrow_book.php?school_number=" . $school_number . "'</script>";
                                                } elseif ($copies_row['status'] == 'Lost') {
                                                    echo "<script>alert('Book Cannot Borrow At This Moment!'); window.location='borrow_book.php?school_number=" . $school_number . "'</script>";
                                                } else {

                                                    if ($new_book_copies == '0') {
                                                        $remark = 'Not Available';
                                                    } else {
                                                        $remark = 'Available';
                                                    }

                                                    mysqli_query($con, "UPDATE book SET book_copies = '$new_book_copies' where book_id = '$book_id' ") or die(mysqli_error($con));
                                                    mysqli_query($con, "UPDATE book SET remarks = '$remark' where book_id = '$book_id' ") or die(mysqli_error($con));

                                                    mysqli_query($con, "INSERT INTO borrow_book(user_id,book_id,date_borrowed,due_date,borrowed_status)
                                                                            VALUES('$user_id','$book_id','$date_borrowed','$due_date','borrowed')") or die('Error: ' . mysqli_error($con));

                                                    $report_history = mysqli_query($con, "select * from admin where admin_id = $eid ") or die(mysqli_error($con));
                                                    $report_history_row = mysqli_fetch_array($report_history);
                                                    $admin_row = $report_history_row['firstname'] . " " . $report_history_row['middlename'] . " " . $report_history_row['lastname'];

                                                    mysqli_query($con, "INSERT INTO report
                                                                            (book_id, user_id, admin_name, detail_action, date_transaction)
                                                                            VALUES ('$book_id','$user_id','$admin_row','Borrowed Book',NOW())") or die(mysqli_error($con));
                                                }
                                            } ?>

                                            <script>
                                                window.location = "borrow_book.php?school_number=<?php echo $school_number ?>";
                                            </script>
                                        <?php   }
                                        ?>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- borrr ends -->





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
<!-- <script>
        window.location = "borrow_book.php?school_number=<php echo $school_number ?>";
    </script> -->

<?php include "includes/footer.php"; ?>