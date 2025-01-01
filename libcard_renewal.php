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
if (isset($_POST['search'])) {
    $rollno = $_POST['libcardno'];
    $sql = "SELECT * from libcard where libcardno='$rollno'";
    $result = mysqli_query($con, $sql) or die(mysqli_error($con));
    // $borrow_row = mysqli_fetch_array($result);
    while ($row = mysqli_fetch_array($result)) {
        $rd1 = $row['issuedate'];
        $rd2 = $row['renewaldate1'];
        $rd3 = $row['renewaldate2'];
    }
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
                        <h4 class="card-title">Library Card Renewal</h4>
                        <a href="add_book.php" class="btn btn-primary"><i class="fa fa-plus color-info"></i> Add new Book</a>
                    </div>
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="" method="post">
                                <div class="input-group mb-3 col-md-3">
                                    <label for="dd">Enter Library Card</label>
                                </div>
                                <div class="input-group mb-3 col-md-6">
                                    <input type="text" class="form-control" id="libcardno" name="libcardno" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-success text-white" type="button" id="btnLaunch"><i class="fa fa-camera" aria-hidden="true"></i></button>
                                    </div>
                                </div>
                                <div class="input-group  col-md-6">
                                    <button class="btn btn-primary" type="submit" name="search">Search</button>
                                </div>

                            </form>
                        </div>
                        <div class="row">

                            <div class="col-md-12">

                                <h5 class="text-center">Renewal Details</h5>
                                <form action="" method="post">
                                    <input type="hidden" name="due_date" value="<?php echo $rd1 ?>" />
                                    <input type="hidden" name="due_date1" value="<?php echo $rd2 ?>" />
                                    <input type="hidden" name="hdnrollno" value="<?php echo $rollno ?>" />

                                    <div class="basic-form col-md-6">
                                        <div class="input-group mb-3">
                                            <label for="aa" class="input-group text-primary">Registartion Date</label>
                                            <input type="text" class="form-control" value="<?php echo date('d-m-Y', strtotime($rd1)) ?>" readonly>
                                            <div class="input-group-append">
                                                <!-- <button class="btn btn-primary" type="button">Button</button> -->
                                            </div>
                                        </div>
                                        <?php
                                        if ($rd2 == null) {

                                            echo  '<div class="input-group mb-3">
    <label for="aa" class="input-group text-primary">Renewal Year-II</label>
    <input type="date" class="form-control" id="dateyear1" name="dateyear1">
    <div class="input-group-append">
        <button class="btn btn-primary" type="submit" name="btnren1" onclick="return ValidateTextBox()">Renew</button>
    </div>
</div>';
                                        } else {
                                            $rd22 = date('d-m-Y', strtotime($rd2));
                                            echo '<div class="input-group mb-3">
                                            <label for="aa" class="input-group text-primary">Renewal Year-II</label>
                                            <input type="text" class="form-control" id="dateyear1" name="dateyear1" value=' . $rd22 . '>
                                        </div>';
                                        }
                                        ?>
                                        <?php
                                        if ($rd3 == null) {

                                            echo  '<div class="input-group mb-3">
    <label for="waa" class="input-group text-primary">Renewal Year-III</label>
    <input type="date" class="form-control" id="dateyear2" name="dateyear2">
    <div class="input-group-append">
        <button class="btn btn-primary" type="submit" name="btnren2" onclick="return ValidateTextBox1()">Renew</button>
    </div>
</div>';
                                        } else {

                                            $rd33 = date('d-m-Y', strtotime($rd3));
                                            echo '<div class="input-group mb-3">
                                            <label for="aa" class="input-group text-primary">Renewal Year-III</label>
                                            <input type="text" class="form-control" id="btnren2btnren2" name="btnren2btnren2" value=' . $rd33 . '>
                                        </div>';
                                        }
                                        ?>


                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- data area ned -->
        <?php
        $srollno = $_POST['hdnrollno'];
        if (isset($_POST["btnren1"])) {
            $due_date = $_POST['due_date'];
            $date1 = $_POST["dateyear1"];
            // echo $due_date;
            if ($_POST['dateyear1'] > $due_date) {
                // echo "Updated";
                $resultren1 = mysqli_query($con, "UPDATE libcard set renewaldate1='$date1' where libcardno='$srollno'");
                echo '<div class="alert alert-success alert-dismissable" id="flash-msg">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Renewal Success!</h4>
            </div>';
            } else {

                echo '<div class="alert alert-danger alert-dismissable" id="flash-msg">
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                <h4><i class="icon fa fa-check"></i>Renewal date is less than issue date!</h4>
                </div>';
            }
        }
        if (isset($_POST['btnren2'])) {
            // echo "<script>
            // alert(.$rd1.)</script>";
            $dateyear1 = $_POST['dateyear2'];
            $aa1 = $_POST["due_date1"];
            if ($_POST['dateyear2']  > $aa1) {
                $resultren1 = mysqli_query($con, "update libcard set renewaldate2='$dateyear1' where libcardno='$srollno'");
                echo '<div class="alert alert-success alert-dismissable" id="flash-msg">
<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
<h4><i class="icon fa fa-check"></i>Renewal Success!</h4>
</div>';
            } else {
                echo '<div class="alert alert-danger alert-dismissable" id="flash-msg">
	<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	<h4><i class="icon fa fa-check"></i>Renewal date is less than issue date!</h4>
	</div>';
            }
        }
        ?>
    </div>
</div>
<!--**********************************
            Content body end
        ***********************************-->
<!-- modal search -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-form">
                    <form>
                        <div class="form-group">
                            <select name="category_id" id="single-select" class="form-control text-success input-rounded" tabindex="-1" id="cat" required>
                                <option value="">--Select--</option>
                                <?php
                                $result = mysqli_query($con, "select * from user where status='Active'") or die(mysqli_error($con));
                                while ($row = mysqli_fetch_array($result)) {
                                    $id = $row['category_id'];
                                ?>
                                    <option value="<?php echo $row['libcardno']; ?>"><?php echo $row['school_number'] . "/" . $row['fullname']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnSave" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
<?php include "includes/footer.php"; ?>
<script>
    $(function() {
        $('#btnLaunch').click(function() {
            $('#myModal').modal('show');
        });

        $('#btnSave').click(function() {
            var value = $('#single-select').val();
            $('h1').html(value);
            $('#libcardno').val(value);

            $('#myModal').modal('hide');
        });
    });
</script>
<script type="text/javascript">
    function ValidateTextBox() {
        // var copyText = document.getElementById("id");
        // var valueOfInput = document.getElementById("id").value
        // alert(copyText);
        // alert("You Pressed:  " + id);
        if (document.getElementById("dateyear1").value.trim() == "") {
            alert("Please Select Renewal date for Second Year!");
            return false;
        }
    };

    function ValidateTextBox1() {
        // var copyText = document.getElementById("id");
        // var valueOfInput = document.getElementById("id").value
        // alert(copyText);
        // alert("You Pressed:  " + id);
        if (document.getElementById("dateyear2").value.trim() == "") {
            alert("Please Select Renewal date for Final Year!");
            return false;
        }
    };
</script>