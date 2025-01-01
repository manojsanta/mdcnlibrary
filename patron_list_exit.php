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
                        <h4 class="card-title">Exited Patrons List</h4>
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
                                    <a class="dropdown-item" href="patron_list.php">Active</a>
                                    <a class="dropdown-item" href="patron_list_exit.php">Exit</a>
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
                                        <th>Subject</th>
                                        <th>Lib Card No</th>
                                        <th>Exit Type</th>
                                        <th>Exit Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($con, "SELECT * from user where status='Exit' order by user_id desc") or die(mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id = $row['user_id'];
                                    ?>
                                        <tr>
                                            <td><a class="text-dark" target="_blank" href="print_barcode_individual.php?code=<?php echo $row['school_number']; ?>"><?php echo $row['school_number']; ?></a>
                                            </td>
                                            <td><?php echo $row['fullname']; ?></td>
                                            <td><?php echo $row['subject']; ?></td>
                                            <td><?php echo $row['libcardno']; ?></td>
                                            <td><?php echo $row['exitstatus']; ?></td>
                                            <td><?php echo $row['exitdate']; ?></td>

                                            <td>
                                                <a class="btn btn-primary" for="ViewAdmin" href="#view<?php echo $row['user_id']; ?>" data-toggle="modal">
                                                    <i class="fa fa-search"></i>
                                                </a>
                                                <a class="btn btn-warning" for="ViewAdmin" href="#edit<?php echo $row['user_id']; ?>" data-toggle="modal">
                                                    <i class="fa fa-edit"></i>
                                                </a>

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
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary">Save
                                                                    changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- view modal -->
                                                <!-- edit modal -->
                                                <div class="modal fade" data-backdrop="static" id="edit<?php echo $row['user_id']; ?>">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Edit Member
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
                                                                    <form method="post" action="">
                                                                        <input type="hidden" name="roll" value="<?php echo $row['user_id']; ?>">
                                                                        <div class="form-row">
                                                                            <div class="form-group col-md-6">
                                                                                <label>Student Name</label>
                                                                                <input type="text" class="form-control" required placeholder="1234 Main St" name="stname" value="<?php echo $row['fullname']; ?>">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>Mobile No.</label>
                                                                                <input type="text" class="form-control" required placeholder="Contact No." name="mobno" value="<?php echo $row['contact']; ?>">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>Email ID</label>
                                                                                <input type="email" class="form-control" required placeholder="Email Id" name="email" value="<?php echo $row['email']; ?>">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Student DOB</label>
                                                                                <input type="date" class="form-control" name="stdob" required value="<?php echo $row['dob']; ?>">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Gender</label>
                                                                                <select id="inputState" class="form-control" name="gender" required>
                                                                                    <option selected>Choose...</option>
                                                                                    <option value="Male" <?php if ($row['gender'] == "Male") echo 'selected="selected"'; ?>>Male</option>
                                                                                    <option value="Female" <?php if ($row['gender'] == "Female") echo 'selected="selected"'; ?>>Female</option>

                                                                                </select>
                                                                            </div>
                                                                        </div>





                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary" name="update">Update changes</button>
                                                                </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- view modal -->
                                                    <!-- delete modal user -->
                                                    <div class="modal fade" id="delete<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel"><i class="glyphicon glyphicon-user"></i> User</h4>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="alert alert-danger">
                                                                        Are you sure you want to Exit the Member?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true"><i class="glyphicon glyphicon-remove icon-white"></i>
                                                                            No</button>
                                                                        <a href="delete_user.php<?php echo '?user_id=' . $id; ?>" style="margin-bottom:5px;" class="btn btn-primary"><i class="glyphicon glyphicon-ok icon-white"></i>
                                                                            Yes</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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