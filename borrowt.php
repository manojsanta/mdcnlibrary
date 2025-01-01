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
                        <h4 class="card-title">Borrow Books/Materials to Teacher/Staff</h4>
                        <a href="borrow.php" class="btn btn-primary"><i class="fa fa-level-up" aria-hidden="true"></i> Borrow to Student</a>
                    </div>
                    <div class="card-body">
                        <div class="col-md-6 offset-3">
                            <form method="post" action="">
                                <select name="school_number" id="single-select" class="form-control" required="required" tabindex="-1">
                                    <option value="0">Select Enrollment Number</option>
                                    <?php
                                    $result = mysqli_query($con, "select * from user where status = 'Active' and type='Teacher'") or die(mysqli_error($con));
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id = $row['user_id'];
                                    ?>
                                        <option value="<?php echo $row['school_number']; ?>"><?php echo $row['school_number']; ?> - <?php echo $row['fullname']; ?></option>
                                    <?php } ?>
                                </select>
                                <br />
                                <br />
                                <button name="submit" type="submit" class="btn btn-primary" style="margin-left:110px;"><i class="glyphicon glyphicon-log-in"></i> Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- data area ned -->

        <?php
        if (isset($_POST['submit'])) {

            $school_number = $_POST['school_number'];

            $sql = mysqli_query($con, "SELECT * FROM user WHERE school_number = '$school_number' ");
            $count = mysqli_num_rows($sql);
            $row = mysqli_fetch_array($sql);

            if ($count <= 0) {
                echo "<div class='alert alert-danger'>" . 'No match found for the School ID Number' . "</div>";
            } else {
                $school_number = $_POST['school_number'];
                echo ('<script> location.href="borrow_book.php?school_number=' . $school_number . '";</script');
            }
        }
        ?>
    </div>
</div>
<!--**********************************
            Content body end
        ***********************************-->

<?php include "includes/footer.php"; ?>