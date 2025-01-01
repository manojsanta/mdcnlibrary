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
    $id = $_POST['roll'];
    $stname = $_POST['stname'];
    mysqli_query($con, "UPDATE allowed_book SET qntty_books='$stname' WHERE allowed_book_id ='$id'") or die(mysqli_error($con));
    echo "<script>alert('Allowed Book Details updated successfully...');</script>";
    echo "<script>window.location.href ='settings.php'</script>";
}
if (isset($_POST['dayupdate'])) {
    $id = $_POST['droll'];
    $stname = $_POST['ndays'];
    mysqli_query($con, "UPDATE  allowed_days SET no_of_days='$stname' WHERE allowed_days_id='$id'") or die(mysqli_error($con));
    echo "<script>alert('Allowed Days Details updated successfully...');</script>";
    echo "<script>window.location.href ='settings.php'</script>";
}
if (isset($_POST['penaltyupdate'])) {
    $id = $_POST['proll'];
    $stname = $_POST['pamount'];
    mysqli_query($con, "UPDATE  penalty SET penalty_amount='$stname' WHERE penalty_id ='$id'") or die(mysqli_error($con));
    echo "<script>alert('Penalty Details updated successfully...');</script>";
    echo "<script>window.location.href ='settings.php'</script>";
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
                        <h4 class="card-title">Library Settings</h4>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- alloweed books edit -->
                            <div class="col-sm-6">
                                <h5 class="text-center text-warning">Allowed Books</h5>
                                <table class="text-dark table table-bordered table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>Allowed Books(Max)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = mysqli_query($con, "SELECT * from allowed_book") or die(mysqli_error($con));
                                        while ($row = mysqli_fetch_array($result)) {
                                            $id = $row['allowed_book_id '];
                                        ?>
                                            <tr>
                                                <td><?php echo $row['qntty_books']; ?></td>
                                                <td>
                                                    <a class="btn btn-warning" for="ViewAdmin" href="#edit<?php echo $row['allowed_book_id ']; ?>" data-toggle="modal">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <!-- edit modal -->
                                                    <div class="modal fade" data-backdrop="static" id="edit<?php echo $row['allowed_book_id ']; ?>">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                        Allowed Books
                                                                        Information</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="basic-form">
                                                                        <form method="post" action="">
                                                                            <input type="hidden" name="roll" value="<?php echo $row['allowed_book_id']; ?>">
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label>Allowed Books(max)</label>
                                                                                    <input type="number" class="form-control" required placeholder="Enter Qty" name="stname">
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

                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                            <!-- allowed_days edit -->
                            <div class="col-sm-6">
                                <h5 class="text-center text-primary">Allowed Days</h5>
                                <table class="table table-bordered table-responsive-sm text-dark">
                                    <thead>
                                        <tr>
                                            <th>Allowed Days(Max)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = mysqli_query($con, "SELECT * from allowed_days") or die(mysqli_error($con));
                                        while ($row = mysqli_fetch_array($result)) {
                                            $id = $row['allowed_days_id'];
                                        ?>
                                            <tr>
                                                <td><?php echo $row['no_of_days']; ?></td>
                                                <td>
                                                    <a class="btn btn-warning" for="ViewAdmin" href="#edit<?php echo $row['allowed_days_id']; ?>" data-toggle="modal">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <!-- edit modal -->
                                                    <div class="modal fade" data-backdrop="static" id="edit<?php echo $row['allowed_days_id']; ?>">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                        Allowed Days
                                                                        Information</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="basic-form">
                                                                        <form method="post" action="">
                                                                            <input type="hidden" name="droll" value="<?php echo $row['allowed_days_id']; ?>">
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label>Allowed Days(max)</label>
                                                                                    <input type="number" class="form-control" required placeholder="Enter Days" name="ndays">
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary" name="dayupdate">Update changes</button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- view modal -->
                                                        <!-- delete modal user -->

                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                            <!-- allowed_days -->
                            <div class="col-sm-6">
                                <h5 class="text-center text-primary">Panalty Levied Per Day</h5>
                                <table class="table table-bordered table-responsive-sm text-dark">
                                    <thead>
                                        <tr>
                                            <th>Penalty Charges Per Day</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = mysqli_query($con, "SELECT * from penalty") or die(mysqli_error($con));
                                        while ($row = mysqli_fetch_array($result)) {
                                            $id = $row['penalty_id'];
                                        ?>
                                            <tr>
                                                <td><?php echo $row['penalty_amount']; ?></td>
                                                <td>
                                                    <a class="btn btn-warning" for="ViewAdmin" href="#edit1<?php echo $row['penalty_id']; ?>" data-toggle="modal">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <!-- edit modal -->
                                                    <div class="modal fade" data-backdrop="static" id="edit1<?php echo $row['penalty_id']; ?>">
                                                        <div class="modal-dialog modal-sm">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Edit
                                                                        Penalty Charged Per Day
                                                                        Information</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="basic-form">
                                                                        <form method="post" action="">
                                                                            <input type="hidden" name="proll" value="<?php echo $row['penalty_id']; ?>">
                                                                            <div class="form-row">
                                                                                <div class="form-group col-md-12">
                                                                                    <label>Penalty per Day(In INR)</label>
                                                                                    <input type="number" class="form-control" required placeholder="Enter Amount in INR" name="pamount">
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="submit" class="btn btn-primary" name="penaltyupdate">Update changes</button>
                                                                    </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- view modal -->
                                                        <!-- delete modal user -->

                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                            </div>
                            <!-- penalty -->
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