<!--**********************************
            Content body start
        ***********************************-->
<?php
session_start();
error_reporting(1);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $school_number = strtoupper($_POST['school_number']);
        $firstname = strtoupper($_POST['fullname']);
        $admyear = $_POST['admyear'];
        $subject = $_POST['subject'];
        $pieces = explode("/", $subject);
        $contact = $_POST['contact'];
        $gender = $_POST['gender'];
        $libno = $_POST['libno'];
        $type = $_POST['type'];
        $eyear = $_POST['eyear'];
        $userid = $_SESSION['uname'];
        $email = $_POST['email'];
        $dob = $_POST['dob'];
        $password = base64_encode("12345");
        $result = mysqli_query($con, "SELECT * from user WHERE school_number='$school_number' AND status='Active'") or die(mysqli_error($con));
        $row = mysqli_num_rows($result);
        if ($row > 0) {
            echo "<script>alert('Teacher already exist!'); window.location='techer_list.php'</script>";
        } else {
            mysqli_query($con, "insert into user (school_number,fullname, admyear, subject, contact, gender, libcardno, type, sessionend, email, dob,status, password,user_added,cretatedby) values ('$school_number','$firstname', '$admyear', '$pieces[1]', '$contact', '$gender', '$libno', '$type', '$eyear', '$email','$dob', 'Active','$password', NOW(),'$userid')") or die(mysqli_error($con));
            $libinsert = mysqli_query($con, "insert into libcard (libcardno,rollno,issuedate) values ('$libno','$school_number',NOW())") or die(mysqli_error($con));
            echo "<script>alert('Teacher details added successfully!'); window.location='teacher_list.php'</script>";
        }
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
                        <h4 class="card-title">Add New Teacher</h4>
                        <a href="patron_list.php" class="btn btn-secondary"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back to List</a>
                    </div>
                    <div class="card-body">
                        <div class="basic-form text-dark">
                            <form role="form" method="post" enctype="multipart/form-data">

                                <div class="form-row">
                                    <!-- <div class="form-group col-md-3">
                                        <label for="sex">Roll No</label>
                                        <input type="text" name="school_number" id="first-name2" required="required" class="form-control">
                                    </div> -->
                                    <div class="form-group col-md-6">
                                        <label for="sex">Full Name of the Teacher</label>
                                        <input type="text" name="fullname" placeholder="Full Name....." id="first-name2" required="required" class="form-control">
                                        <input type="hidden" name="school_number" placeholder="Full Name....." id="rollno" required="required" class="form-control">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="studentno">Year of Joining to this college.</label>
                                        <select name="admyear" id="admyear" class="form-control" required>
                                            <option value="">--select--</option>
                                            <?php
                                            $starting_year  = date('Y', strtotime('-5 year'));
                                            $ending_year = date('Y');

                                            for ($starting_year; $starting_year <= $ending_year; $starting_year++) {
                                                echo '<option value="' . $starting_year . '">' . $starting_year . '</option>';
                                            }  ?>
                                        </select>
                                        <input type="hidden" name="sessionyear" id="sessionendyear">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="names">Department</label>
                                        <select disabled="disabled" name="subject" id="subject" class="form-control" required>
                                            <option value="">--select--</option>
                                            <?php
                                            // $conn = mysqli_connect('localhost', 'root', '');
                                            $result = mysqli_query($con, 'SELECT * FROM category');
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<option value='$row[shortcode]/$row[classname]'>$row[classname]</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="sex">Library Card No.</label>
                                        <input type="text" readonly name="libno" placeholder="Library Card No....." id="libno" required="required" class="form-control">
                                        <input type="hidden" readonly name="eyear" id="eyear" class="form-control">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="age">Mobile No<span style="color: blue;">*</span></label>
                                        <input type="tel" pattern="[0-9]{10,10}" autocomplete="off" maxlength="11" name="contact" id="last-name2" class="form-control">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Gender</label>
                                        <select name="gender" class="form-control" required="required" tabindex="-1">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Email-ID</label>
                                        <input type="email" name="email" id="last-name2" class="form-control">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Membership Type</label>
                                        <select name="type" readonly class="select2_single form-control" required="required" tabindex="-1">
                                            <option value="Teacher">Teacher</option>
                                        </select>

                                    </div>
                                    <!-- <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Student DOB</label>
                                        <input type="date" class="form-control" name="dob">

                                    </div> -->

                                    <div class="form-group col-md-8">
                                        <label for="exampleInputFile">Remark</label>
                                        <textarea name="" class="form-control" id="" cols="30" rows="2"></textarea>

                                    </div>
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>
                                <button onclick="window.location.href='patron_list.php'" class="btn btn-danger">Cancel</button>
                            </form>
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
<script>
    $(document).ready(function() {
        $(".checkbox1").on("change", function() {

            if (this.checked) {
                $("#accno").val($("#bkcode").val());

            } else {

                $('#accno').attr("value", "");
                $("#accno").attr("placeholder", "Enter if different as Barcode");
            }

        });

    });
    $("#admyear").on("change", function() {
        //   $("select[name='fund']").prop("disabled", !this.checked);
        $('#subject option[value=""]').prop('selected', true);
        $('#bkcode').val("");
        $('#accno').val("");

        if ($('#admyear').val()) {
            // do something
            //    alert($('#admyear').val());
            $('#subject').attr('disabled', false);
        } else {
            // do
            alert("Select Admission Year");
            $('#subject').attr('disabled', true);
            $('#subject option[value=""]').prop('selected', true);

            $("#admyear").focus();
        }

    });
</script>

<script>
    $(document).ready(function() {
        /* PREPARE THE SCRIPT */

        $("#subject").change(function() {
            //$(document).on("blur","#regno",function(){ /* WHEN YOU CHANGE AND SELECT FROM THE SELECT FIELD */
            var subjectname = $(this).val(); /* GET THE VALUE OF THE SELECTED DATA */
            var arr = subjectname.split('/');
            // alert(arr[0]);
            //  var coursename=$('select[name="category_id"] option:selected').text();
            //  $('#cname').val(coursename);
            var admyear = $('#admyear').val(); //(coursename);
            //$('#regno').val($('#enrollno').val()); copy input from 1 to other
            // var dataString = "allbooks="+allbooks; /* STORE THAT TO A DATA STRING */
            //  alert(subjectname);
            //$('#sname').html(courseid);
            if ($('#admyear').val() === "") {
                alert("Select Admission year Name");
                return false;
            }
            if (subjectname) {
                // getRow1(id);
                alert(admyear);
                $.ajax({
                    type: 'POST',
                    url: 'ajax_data.php',
                    data: {
                        tsub_id: arr[0],
                        tadmyear_id: admyear
                    }, // booktype value passed to ajax via ajax variable booktype_id
                    dataType: 'json',
                    success: function(response) {
                        //  alert(id);
                        alert(response.libcardno);
                        $('#libno').val(response.libcardno);
                        $('#eyear').val(response.endyr);
                        $('#rollno').val(response.rollno);
                        //  $('#libno').val("nn");

                        //  $('#accno').val(response.accession);
                    }
                });
            } else {
                alert("select source of the book");
                $('#fund').focus();
                return false;
            }
            //fundtio start


            // function end
        });
    });
</script>