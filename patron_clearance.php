<!--**********************************
            Content body start // add user table columnn exit status
        ***********************************-->
<?php
session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
}
if (isset($_POST['update'])) {
    // $mobno = $_POST['mobno'];
    $id = $_POST['roll'];
    // $stname = strtoupper($_POST['stname']);
    // $email = $_POST['email'];
    $stdob = $_POST['stdob'];
    $gender = $_POST['gender'];
    mysqli_query($con, "UPDATE user SET exitstatus='$gender',exitdate='$stdob',status='Exit' WHERE user_id='$id'") or die(mysqli_error($con));
    echo "<script>alert('Student Exit from Library successfully...');</script>";
    echo "<script>window.location.href ='patron_clearance.php'</script>";
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
                        <h4 class="card-title">Manage Patron's Clearance</h4>
                        <div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-product-hunt" aria-hidden="true"></i> Register</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="add_patron.php"><i class="fa fa-plus color-info"></i>
                                        New Student</a>
                                    <a class="dropdown-item" href="add_book.php"><i class="fa fa-plus color-info"></i>
                                        New Faculty</a>
                                    <a class="dropdown-item" href="add_book.php"><i class="fa fa-plus color-info"></i>
                                        New Alumni</a>
                                    <a class="dropdown-item" href="javascript:void()"><i class="fa fa-smile-o" aria-hidden="true"></i> Guest</a>
                                </div>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-search" aria-hidden="true"></i> Search Patron</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void()">Active</a>
                                    <a class="dropdown-item" href="patron_list_exit.php">Exit</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <a class="btn btn-dark rounded-0 btn-sm mb-3" href="export_user_outstanding.php?id='1'">
                                <i class="fa fa-print"></i> Export to Excel
                            </a>
                            <table id="example" class="display text-dark table-sm">
                                <thead>
                                    <tr>
                                        <th>Roll No</th>
                                        <th>Member Full Name</th>
                                        <th>Subject</th>
                                        <th>Mobile No.</th>
                                        <th>Lib Card No</th>
                                        <th>Outstanding</th>
                                        <th>Exit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sqlclearance = "SELECT user.libcardno,user.user_id,user.school_number,user.fullname,user.subject,user.contact, SUM(IF(borrow_book.borrowed_status ='borrowed', 1, 0)) AS post_count
                                    FROM user
                                    LEFT JOIN borrow_book ON borrow_book.user_id = user.user_id 
                                    WHERE user.status='Active'
                                    GROUP BY 1";
                                    $result = mysqli_query($con, $sqlclearance) or die(mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        // $id = $row['user_id'];
                                    ?>
                                        <tr>

                                            <td><?php echo $row['school_number']; ?></td>
                                            <td><?php echo $row['fullname']; ?></td>
                                            <td><?php echo $row['subject']; ?></td>
                                            <td><?php echo $row['contact']; ?></td>
                                            <td><?php echo $row['libcardno']; ?></td>
                                            <td><?php echo $row['post_count']; ?></td>
                                            <td>
                                                <?php
                                                if ($row['post_count'] > 0) {
                                                    echo '<span class="badge badge-pill badge-warning">Return Pending</span>';
                                                } else { ?>
                                                    <a class="btn btn-danger btn-sm" for="ViewAdmin" href="#view<?php echo $row['user_id']; ?>" data-toggle="modal">
                                                        <i class="fa fa-sign-out" aria-hidden="true"></i>
                                                    </a>

                                                <?php } ?>
                                                <!-- view modal -->
                                                <div class="modal fade" data-backdrop="static" id="view<?php echo $row['user_id']; ?>">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Member
                                                                    Information</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="tid" value="<?php echo $row['user_id']; ?>">
                                                                <table class="table">
                                                                    <?php
                                                                    $query = "SELECT * FROM user WHERE user_id={$row['user_id']}";
                                                                    // echo $query;
                                                                    $query_run = mysqli_query($con, $query);

                                                                    if (mysqli_num_rows($query_run) > 0) //Atleast 1 record is there or not
                                                                    {
                                                                        foreach ($query_run as $row) {
                                                                    ?>
                                                                            <tr>
                                                                                <td>Student Name</td>
                                                                                <td><?php echo $row['fullname']; ?></td>
                                                                                <td>Department</td>
                                                                                <td><?php echo $row['subject']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Roll No</td>
                                                                                <td><?php echo $row['school_number']; ?></td>
                                                                                <td>Library Card No</td>
                                                                                <td><?php echo $row['libcardno']; ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Mobile No</td>
                                                                                <td><?php echo $row['contact']; ?></td>
                                                                                <td>Gender</td>
                                                                                <td><?php echo $row['gender']; ?></td>
                                                                            </tr>
                                                                    <?php }
                                                                    } ?>
                                                                </table>
                                                                <div class="basic-form">
                                                                    <h5 class="text-danger">Exit Remarks</h5>
                                                                    <form method="post" action="">
                                                                        <input type="hidden" name="roll" value="<?php echo $row['user_id']; ?>">
                                                                        <div class="form-row">

                                                                            <div class="form-group col-md-6">
                                                                                <label>Date of Exit</label>
                                                                                <input type="date" class="form-control" name="stdob" required max="<?php echo date("Y-m-d"); ?>">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>Purpose of Exit</label>
                                                                                <select id="inputState" class="form-control" name="gender" required>
                                                                                    <option selected>Choose...</option>
                                                                                    <option value="Passout">Passout</option>
                                                                                    <option value="Dropout">Dropout</option>
                                                                                    <option value="Discountinued">Discountinued</option>
                                                                                    <option value="HigherStudy">Higher Study</option>
                                                                                    <option value="Other">Other Issue</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" name="update" class="btn btn-danger">Save
                                                                    changes</button>
                                                            </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- view modal -->
                                                <!-- edit modal -->

                                                <!-- view modal -->
                                                <!-- delete modal user -->

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