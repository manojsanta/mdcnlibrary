<!--**********************************
            Content body start
        ***********************************-->
<?php
session_start();
error_reporting(1);
date_default_timezone_set("Asia/Calcutta");

include 'includes/dbconnection.php';
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
}
if (isset($_POST['update'])) {
    $mobno = $_POST['mobno'];
    $id = $_POST['roll'];
    $stname = strtoupper($_POST['stname']);
    $email = $_POST['email'];
    $stdob = $_POST['stdob'];
    $gender = $_POST['gender'];
    mysqli_query($con, "UPDATE user SET fullname='$stname',contact='$mobno',email='$email',dob='$stdob',gender='$gender' WHERE user_id='$id'") or die(mysqli_error($con));
    echo "<script>alert('Student Details updated successfully...');</script>";
    echo "<script>window.location.href ='patron_list.php'</script>";
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
                        <h4 class="card-title">Books Issue Vintage</h4>
                        <!-- <div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i
                                        class="fa fa-product-hunt" aria-hidden="true"></i> Register</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="add_patron.php"><i class="fa fa-plus color-info"></i>
                                        New Student</a>
                                    <a class="dropdown-item" href="add_book.php"><i class="fa fa-plus color-info"></i>
                                        New Faculty</a>
                                    <a class="dropdown-item" href="add_book.php"><i class="fa fa-plus color-info"></i>
                                        New Alumni</a>
                                    <a class="dropdown-item" href="javascript:void()"><i class="fa fa-smile-o"
                                            aria-hidden="true"></i> Guest</a>
                                </div>
                            </div> -->
                        <!-- <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i
                                        class="fa fa-search" aria-hidden="true"></i> Search Patron</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void()">Active</a>
                                    <a class="dropdown-item" href="javascript:void()">Exit</a>
                                </div>
                            </div> -->
                        <!-- </div> -->

                    </div>
                    <div class="card-body">
                        <form class="form-inline mb-3" action="" method="post">
                            <label for="email" class="text-dark mr-2">Enter Days to view:</label>
                            <input type="number" class="form-control" id="days" placeholder="Enter Days" name="days" required>

                            <button type="submit" name="search" class="btn btn-primary btn-outline ml-2"><i class="fa fa-calendar-o"></i> Search</button>
                        </form>
                        <div class="table-responsive">
                            <?php

                            $days = '7'; //DEFALUT 7 DAYS
                            if (isset($_POST['search'])) {
                                echo $days = $_POST['days'];
                                $_SESSION['days'] = $days;
                            }
                            ?>
                            <table class="table table-bordered text-dark table-responsive-sm">
                                <?php echo
                                '
                                <a class="btn btn-dark rounded-0 btn-sm mb-3" href="export_issue_vintage.php?id=' . $days . '&subid=' . '1' . '">
                                <i class="fa fa-print"></i> Export to Excel
                                </a>';
                                ?>
                                <thead>
                                    <tr>
                                        <th>Sl. No</th>
                                        <th>Roll No</th>
                                        <th>Full Name</th>
                                        <th>Book No</th>
                                        <th>Book Title</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Vintage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT borrow_book.*,user.user_id,user.school_number,user.fullname,book.book_id,book.book_title,book.book_barcode,
                                    datediff(CURDATE(),borrow_book.due_date) AS days FROM borrow_book inner JOIN user ON borrow_book.user_id=user.user_id inner JOIN book ON borrow_book.book_id=
                                    book.book_id WHERE DATEDIFF(CURDATE(),borrow_book.due_date)>'$days' AND borrow_book.borrowed_status='borrowed'";
                                    $result = mysqli_query($con, $sql) or die(mysqli_error($con));
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id = $row['user_id'];
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt++; ?></td>
                                            <td><?php echo $row['school_number']; ?></td>
                                            <td><?php echo $row['fullname']; ?></td>
                                            <td><?php echo $row['book_barcode']; ?></td>
                                            <td><?php echo $row['book_title']; ?></td>
                                            <td><?php
                                                echo date('d-m-Y h:i:s A', strtotime($row['date_borrowed']));
                                                ?></td>
                                            <td><?php
                                                echo date('d-m-Y h:i:s A', strtotime($row['due_date']));
                                                ?></td>
                                            <td><?php echo $row['days']; ?></td>

                                        </tr>
                                    <?php
                                    } ?>
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