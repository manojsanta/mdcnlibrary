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
// if (isset($_POST['update'])) {
//     $mobno = $_POST['mobno'];
//     $id = $_POST['roll'];
//      $stname = strtoupper($_POST['stname']);
//     $email = $_POST['email'];
//     $stdob = $_POST['stdob'];
//     $gender = $_POST['gender'];
//     mysqli_query($con, "UPDATE user SET fullname='$stname',contact='$mobno',email='$email',dob='$stdob',gender='$gender' WHERE user_id='$id'") or die(mysqli_error($con));
//     echo "<script>alert('Student Details updated successfully...');</script>";
//     echo "<script>window.location.href ='patron_list.php'</script>";
// }
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
                        <h4 class="card-title">Manage Patrons Library Card</h4>
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
                                    <a class="dropdown-item" href="javascript:void()">Exit</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display text-dark">
                                <thead>
                                    <tr>
                                        <th>Roll No</th>
                                        <th>Member Full Name</th>
                                        <th>Department</th>

                                        <th>Lib Card No</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($con, "SELECT * from user where status='Active' order by user_id desc") or die(mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id = $row['user_id'];
                                    ?>
                                        <tr>
                                            <td><?php echo $row['school_number']; ?> </td>
                                            <td><?php echo $row['fullname']; ?></td>
                                            <td><?php echo $row['subject']; ?></td>

                                            <td><?php echo $row['libcardno']; ?></td>

                                            <td>
                                                <a class="btn btn-primary" for="ViewAdmin" href="#view<?php echo $row['user_id']; ?>" data-toggle="modal">
                                                    <i class="fa fa-print"></i>
                                                </a>
                                                <a class="btn btn-warning" href="pdf_printlibcard1.php?code=<?php echo $row['libcardno']; ?>">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                                <a class="btn btn-danger" href="pdf_printlpassbook.php?code=<?php echo $row['libcardno']; ?>">
                                                    Passbook
                                                </a>


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