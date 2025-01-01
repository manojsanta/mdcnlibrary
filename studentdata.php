<?php
date_default_timezone_set('Asia/Kolkata');
include('includes/dbconnection.php');
// error_reporting(E_ERROR | E_PARSE);
error_reporting(1);
// session_start();
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    if ($id == 0) {
        echo "<option value=''>Select Subject Details</option>";
    } else {
        echo "<option value=''>Select Subject</option>";
        $sql = mysqli_query($con, "SELECT * FROM tbl_stream WHERE streamid=$id");
        while ($row = mysqli_fetch_array($sql)) {
            echo '<option value="' . $row['subname'] . '">' . $row['subname'] . '</option>';
        }
    }
    exit;
}
if (isset($_POST['submit'])) {
    $firstname = strtoupper($_POST['stname']);
    $admyear = $_POST['admyear'];
    $stream = $_POST['stream'];
    // $subject = $_POST['subject'];
    $prefix = $_POST['prefix'];
    // $subject = $_POST['subject'];
    $rollno = $_POST['rollno'];
    $school_number = $prefix . str_pad($rollno, 3, '0', STR_PAD_LEFT);
    $dob = $_POST['dob'];
    $gender = $_POST['radio'];
    $email = $_POST['email'];
    $contact = $_POST['mobile'];

    $address = $_POST['address'];
    $userid = 'Guest';
    $type = 'Student';
    $libno1 = $_POST['libno'];

    $eyear = $_POST['eyear'];
    //form data


    $subject = $_POST['subject'];
    $pieces = explode("/", $subject);

    // echo $pieces[0];




    $password = base64_encode("12345");
    ///
    $msg = "";

    $check = mysqli_query($con, "select * from user WHERE school_number='$school_number'");
    $checkrows = mysqli_num_rows($check);
    if ($checkrows > 0) {
        $report_history_row = mysqli_fetch_array($check);
        $status = $report_history_row['status'];
        if ($status == 'Pending') {
            $msg = "Student already applied & Status is Pending for Approval.Contact to Library.";
        } else {
            $msg = "Student already exist.Proceed for login.";
        }
        // $_SESSION['msg'] = $msg;
        // echo '<script>window.location="thanku.php"</script>';
        $msg = urlencode($msg);
        header("Location: thanku.php?user=$msg");
    } else {


        $query = mysqli_query($con, "insert into user (school_number,fullname, admyear, subject, contact, gender, type, sessionend, email, dob,status, password,user_added,cretatedby) values ('$school_number','$firstname', '$admyear', '$pieces[1]', '$contact', '$gender',  '$type', '$eyear', '$email','$dob', 'Pending','$password', NOW(),'$userid')") or die(mysqli_error($con));
        $last_id = mysqli_insert_id($con);
        if ($query) {
            // $_SESSION['stname'] = $name;
            $resultuser = mysqli_query($con, "SELECT * FROM user");
            $yy = substr($admyear, -2);
            // $endyear = $admyear_id + 3;
            $rowsuser = mysqli_num_rows($resultuser);
            $libno = "LC" . $pieces[0] . $yy . str_pad((($rowsuser) + 1), 4, '0', STR_PAD_LEFT);
            mysqli_query($con, "UPDATE user SET libcardno='$libno' WHERE user_id='$last_id'") or die(mysqli_error($con));
            $msg = "Dear " . $firstname . " your registartion is under review.";
            $msg .= "</br>Your Provisional library card no. is-" . $libno;
            $msg = urlencode($msg);
            echo '<script>alert("Student Details Submitted Successfully.")</script>';
            // echo "<script>window.location.href ='thanku.php'</script>";
            echo '<script>
                    window.location="thanku.php?user=' . $msg . '";
                   </script>';
        } else {
            echo '<script>alert("Something Went Wrong. Please try again.")</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>MDC,Nayagarh</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>

<body>

    <div class="wrapper rounded bg-white">
        <div class="h2 text-info text-center">MODEL DEGREE COLLEGE,NAYAGARH</div>
        <div class="h4">Student Enrollment to Library</div>
        <form method="post" action="" id="form1">
            <div class="form">
                <div class="row">
                    <div class="col-md-12 mt-md-0 mt-3">
                        <label>Student Name</label>
                        <input type="text" name="stname" id="stname" class="form-control" required>
                    </div>

                </div>
                <div class="row">
                    <div class="my-md-2 my-3 col-md-6 mt-md-0 mt-3">
                        <label>Admission Year</label>
                        <select id="admyear" name="admyear" class="sub" required>
                            <option value="" selected>Choose Year</option>
                            <?php
                            $starting_year  = date('Y', strtotime('-2 year'));
                            $ending_year = date('Y');

                            for ($starting_year; $starting_year <= $ending_year; $starting_year++) {
                                echo '<option value="' . $starting_year . '">' . $starting_year . '</option>';
                            }  ?>

                        </select>
                    </div>
                    <div class="my-md-2 my-3 col-md-6 mt-md-0 mt-3">
                        <label>Select Subject</label>
                        <select id="subject" name="subject" disabled="disabled" class="sub" required>
                            <option value="0" selected>Choose Option</option>
                            <?php
                            // $conn = mysqli_connect('localhost', 'root', '');
                            $result = mysqli_query($con, 'SELECT * FROM category where shortcode in("ANTH","BOT","CHE","ECO","EDU","MATH","PHY","POL","SOC","ZOL")');
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='$row[shortcode]/$row[classname]'>$row[classname]</option>";
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <!-- <div class="my-md-2 my-3">
                    <label>Select Stream</label>
                    <select id="stream" name="stream" class="sub" required>
                        <option value="0" selected hidden>Choose Option</option>
                        <option value="1|BA">ARTS</option>
                        <option value="2|BSB">BIOLOGICAL SCIENCE</option>
                        <option value="3|BSP">PHYSICAL SCIENCE</option>

                    </select>
                </div> -->



                <div class="row">
                    <div class="col-md-2 mt-md-0 mt-3">
                        <label>Prefix</label>
                        <input type="text" id="prefix" name="prefix" class="form-control" readonly required>
                    </div>
                    <div class="col-md-4 mt-md-0 mt-3">
                        <label>Roll No.</label>
                        <input type="text" pattern="\d{3}" title="must be 3 digit" name="rollno" id="rollno" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Birthday</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-md-3 mt-3">
                        <label>Library Card No.</label>
                        <input type="text" name="libno" id="libno" readonly class="form-control" required>
                        <input type="hidden" readonly name="eyear" id="eyear" class="form-control">
                    </div>
                    <div class="col-md-4 mt-md-3 mt-3">
                        <label>Gender</label>
                        <div class="d-flex align-items-center mt-2">
                            <label class="option">
                                <input type="radio" required="required" name="radio" value="Male">Male
                                <span class="checkmark"></span>
                            </label>
                            <label class="option ms-4">
                                <input type="radio" name="radio" value="Female">Female
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="col-md-6 mt-md-0 mt-3">
                        <label>Mobile Number</label>
                        <input type="tel" name="mobile" id="mobile" class="form-control" required pattern="[0-9]{10}">
                    </div>
                </div>

                <div class=" my-md-2 my-3">
                    <label>Permanent Address</label>
                    <textarea class="form-control" name="address" required></textarea>
                </div>
                <input type="submit" class="btn btn-primary mt-3" name="submit" id="submit" value="Submit">

            </div>
        </form>
    </div>
    <style>
        /* Importing fonts from Google */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');

        /* Reseting */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(45deg, #ce1e53, #8f00c7);
            min-height: 100vh;
        }

        body::-webkit-scrollbar {
            display: none;
        }

        .wrapper {
            max-width: 800px;
            margin: 80px auto;
            padding: 30px 45px;
            box-shadow: 5px 25px 35px #3535356b;
        }

        .wrapper label {
            display: block;
            padding-bottom: 0.2rem;
        }

        .wrapper .form .row {
            padding: 0.6rem 0;
        }

        .wrapper .form .row .form-control {
            box-shadow: none;
        }

        .wrapper .form .option {
            position: relative;
            padding-left: 20px;
            cursor: pointer;
        }


        .wrapper .form .option input {
            opacity: 0;
        }

        .wrapper .form .checkmark {
            position: absolute;
            top: 1px;
            left: 0;
            height: 20px;
            width: 20px;
            border: 1px solid #bbb;
            border-radius: 50%;
        }

        .wrapper .form .option input:checked~.checkmark:after {
            display: block;
        }

        .wrapper .form .option:hover .checkmark {
            background: #f3f3f3;
        }

        .wrapper .form .option .checkmark:after {
            content: "";
            width: 10px;
            height: 10px;
            display: block;
            background: linear-gradient(45deg, #ce1e53, #8f00c7);
            position: absolute;
            top: 50%;
            left: 50%;
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
            transition: 300ms ease-in-out 0s;
        }

        .wrapper .form .option input[type="radio"]:checked~.checkmark {
            background: #fff;
            transition: 300ms ease-in-out 0s;
        }

        .wrapper .form .option input[type="radio"]:checked~.checkmark:after {
            transform: translate(-50%, -50%) scale(1);
        }

        .sub {
            display: block;
            width: 100%;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            color: #333;
        }

        .sub:focus {
            outline: none;
        }

        @media(max-width: 768.5px) {
            .wrapper {
                margin: 30px;
            }

            .wrapper .form .row {
                padding: 0;
            }
        }

        @media(max-width: 400px) {
            .wrapper {
                padding: 25px;
                margin: 20px;
            }
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#admyear').change(function() {
                if ($('#admyear').val()) {
                    // do something
                    //    alert($('#admyear').val());
                    $('#subject').attr('disabled', false);
                } else {
                    // do
                    alert("Select Admission Year");
                    $("#stream").val(0);
                    $('#subject option[value="0"]').prop('selected', true);
                    $('#subject').attr('disabled', true);


                    $("#admyear").focus();
                }
            });

            // $("#stream").on("change", function() {
            //     var admyear = $('#admyear').val();
            //     var stream_id = $(this).val();
            //     alert(stream_id);
            //     var arr = stream_id.split('|');
            //     // alert(admyear. slice(-2));
            //     $("#prefix").val(arr[1] + admyear.slice(-2) + '-');
            //     // $("#prefix").val($(this).val());
            //     if (!$('#admyear').val()) {
            //         alert('Select Admission Year!');

            //         $("#stream").val(0);
            //         return false;
            //     }

            //     // var sub_id = 'id='+stream_id;

            //     $.ajax({
            //         type: "POST",
            //         url: "studentdata.php",
            //         // data: sub_id,
            //         data: 'id=' + arr[0],
            //         cache: false,
            //         success: function(cities) {

            //             $("#subject").html(cities);
            //             $("#ge1").html(cities);
            //             $("#ge2").html(cities);
            //             console.log(cities);
            //             if (arr[0] == 2) {
            //                 $('#ge1').append(`<option value="Chemistry">Chemistry</option>`);
            //                 $('#ge2').append(`<option value="Chemistry">Chemistry</option>`);
            //             }
            //         }
            //     });


            // });



        });
    </script>
    <script>
        $(document).ready(function() {
            $("#submit").click(function() {
                if (!$('#admyear').val()) {
                    alert('Enter Admission Year!');
                    $('#admyear').focus();
                    return false;
                }
                if ($('#stream').val() == '0') {
                    alert('Enter Stream!');
                    $('#stream').focus();
                    return false;
                }
                if (!$('#subject').val()) {
                    alert('Select Subject Admitted!');
                    $('#subject').focus();
                    return false;
                }
                if ($('#libno').val() == '') {
                    alert('Libary Card No cannot be empty');
                    $('#libno').focus();
                    $('#form1').trigger("reset");
                    return false;
                }
                if ($('#rollno').val() == '') {
                    alert('Enter Roll Number!');
                    $('#form1').trigger("reset");
                    $('#rollno').focus();
                    return false;
                }

                if (!$('#stname').val()) {
                    alert("Enter Name as per +2 Certificate!");
                    $('#stname').focus();
                    return false;
                }
                if (!$('#rollno').val()) {
                    alert("Enter Student's College Roll No!");
                    $('#rollno').focus();
                    return false;
                }

            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#rollno,#mobile,#pmobile').keypress(function(e) {

                var charCode = (e.which) ? e.which : event.keyCode

                if (String.fromCharCode(charCode).match(/[^0-9]/g))

                    return false;

            });

            $('#ge2').on('change', function() {

                if ($('#ge1').val() == $('#ge2').val()) {
                    alert('Both GE Subjects are not same!');
                    $("#ge2").prop("selectedIndex", 0);
                    $('#ge2').first();
                    $('#ge2').focus();
                    return false;
                }
            });

        });
    </script>
    <script>
        $(document).ready(function() {
            /* PREPARE THE SCRIPT */

            $("#subject").change(function() {
                //$(document).on("blur","#regno",function(){ /* WHEN YOU CHANGE AND SELECT FROM THE SELECT FIELD */
                var subjectname = $(this).val(); /* GET THE VALUE OF THE SELECTED DATA */
                var arr = subjectname.split('/');
                var prefix = "";
                alert(arr[0]);
                //  var coursename=$('select[name="category_id"] option:selected').text();
                //  $('#cname').val(coursename);
                var admyear = $('#admyear').val(); //(coursename);
                //$('#regno').val($('#enrollno').val()); copy input from 1 to other
                // var dataString = "allbooks="+allbooks; /* STORE THAT TO A DATA STRING */
                //  alert(subjectname);
                //$('#sname').html(courseid);
                myArray = [
                    ["ANTH", "BA", "White", "Albuquerque"],
                    ["BOT", "BSB", "White", "Albuquerque"],
                    ["ECO", "BA", "White", "Albuquerque"],
                    ["ZOL", "BSB", "White", "Albuquerque"],
                    ["EDU", "BA", "White", "Albuquerque"],
                    ["CHE", "BSP", "White", "Albuquerque"],
                    ["POL", "BA", "White", "Albuquerque"],
                    ["MATH", "BSP", "White", "Albuquerque"],
                    ["SOC", "BA", "White", "Albuquerque"],
                    ["PHY", "BSP", "White", "Albuquerque"]
                ];

                var test = arr[0];

                for (var i = 0; i < myArray.length + 1; i++) {
                    if (myArray[i][0] === test) {
                        var index = i;
                        break;
                    }
                }
                prefix = myArray[i][1] + admyear.substr(-2, 2) + '-';
                // alert(prefix);
                //return
                if ($('#admyear').val() === "") {
                    alert("Select Admission year Name");
                    return false;
                }
                if (subjectname) {
                    // getRow1(id);
                    // alert(admyear);
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_data.php',
                        data: {
                            sub_id: arr[0],
                            admyear_id: admyear
                        }, // booktype value passed to ajax via ajax variable booktype_id
                        dataType: 'json',
                        success: function(response) {
                            //  alert(id);
                            alert(response.libcardno);
                            $('#libno').val(response.libcardno);
                            $('#eyear').val(response.endyr);
                            $('#prefix').val(prefix);
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
</body>

</html>