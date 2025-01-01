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
        if (!isset($_FILES['image']['tmp_name'])) {
            echo "No book image set";
        } else {
            $file = $_FILES['image']['tmp_name'];
            $image = $_FILES["image"]["name"];
            $image_name = addslashes($_FILES['image']['name']);
            $size = $_FILES["image"]["size"];
            $error = $_FILES["image"]["error"]; {
                if ($size > 10000000) //conditions for the file
                {
                    die("Format is not allowed or file size is too big!");
                } else {

                    move_uploaded_file($_FILES["image"]["tmp_name"], "book_image/" . $_FILES["image"]["name"]);
                    $book_image = $_FILES["image"]["name"];
                    $book_title = mysqli_real_escape_string($con, $_POST['book_title']);
                    $book_type = $_POST['fund'];
                    $category_id = $_POST['category_id'];
                    $result_explode = explode('|', $category_id);
                    $cat_slash = $result_explode[0];
                    $cat_id = $result_explode[1];
                    $author = mysqli_real_escape_string($con, $_POST['author']);
                    $author_2 = mysqli_real_escape_string($con, $_POST['author_2']);
                    $author_3 = mysqli_real_escape_string($con, $_POST['author_3']);
                    // $author_4=$_POST['author_4'];
                    // $author_5=$_POST['author_5'];
                    $book_copies = $_POST['book_copies'];
                    $book_pub = $_POST['vol'];
                    $publisher_name = mysqli_real_escape_string($con, $_POST['publisher_name']);
                    $isbn = $_POST['isbn'];
                    $copyright_year = $_POST['pubyear'];
                    $status = $_POST['status'];
                    // $bktitle=$_POST['bkcode'];
                    $book_barcode = $_POST['bkcode'];
                    $accno = $_POST['accno'];
                    $accdate = $_POST['accdate'];
                    $sourcefund = $_POST['source'];
                    $invno = $_POST['invno'];
                    $invdate = $_POST['invdate'];
                    $vendoname = $_POST['vendor'];
                    // $mid = $_POST['new_barcode'];
                    // $suf = "LMS";
                    // $gen = $pre.$mid.$suf;
                    $price = $_POST['price'];
                    $userid = $_SESSION['uname'];
                    if ($status == 'Lost') {
                        $remark = 'Not Available';
                    } elseif ($status == 'Damaged') {
                        $remark = 'Not Available';
                    } else {
                        $remark = 'Available';
                    }
                    $rem = $_POST['rem'];
                    //  {
                    $duperaw = mysqli_query($con, "select * from book where book_barcode='$book_barcode'");
                    // echo "userid is".$userid;
                    if (mysqli_num_rows($duperaw) > 0) {
                        //your code ...
                        echo '<script>
                    alert("Book barcode already exist!!");
                    window.location="add_book1.php";
                    </script>';
                    }

                    //  $arr = get_defined_vars();
                    //     echo "<pre>";
                    // print_r($arr);
                    // echo "</pre>";
                    $result = mysqli_query($con, "insert into book (book_title,category_id,booktype,author,author_2,author_3,book_copies,book_pub,publisher_name,isbn,copyright_year,price,status,book_barcode,bookaccno,accdate,book_image,date_added,remarks,sourcefund,invno,invdate,vendoname,librem,entryby)
					values('$book_title','$cat_slash','$book_type','$author','$author_2','$author_3','$book_copies','$book_pub','$publisher_name','$isbn','$copyright_year','$price','$status','$book_barcode','$accno','$accdate','$book_image',NOW(),'$remark','$sourcefund','$invno','$invdate','$vendoname','$vendoname','$userid')") or die(mysqli_error($con));
                    // echo $accno;//$accdate;
                    if ($result) {
                        mysqli_query($con, "insert into barcode (pre_barcode) values ('$book_barcode') ") or die(mysqli_error($con));
                        mysqli_query($con, "insert into  temp_book(book_barcode,book_accno) values ('$book_barcode','$accno') ") or die(mysqli_error($con));
                        //     echo '<script>
                        // alert("New Book Added Successfully!!");
                        // window.location="view_barcode.php?code=' . $book_barcode . '";
                        // </script>';
                        $_SESSION['MSG'] = "Your data is saved";
                        header('location: add_book1.php');
                        exit;
                    } else {
                        echo '<script>
                        alert("Something went wrong!!");
                        // window.location="view_barcode.php?code=' . $book_barcode . '";
                        </script>';
                    }
                }
            }
            // header("location: view_barcode.php?code=".$gen);

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
                        <h4 class="card-title">Add New Books/Materials</h4>
                        <a href="" class="btn btn-primary"><i class="fa fa-plus color-info"></i> Back to List</a>
                    </div>
                    <div class="card-body">
                        <!-- message -->
                        <?php
                        if (isset($_SESSION['MSG'])) {
                        ?>
                            <div class="alert alert-success solid alert-right-icon alert-dismissible fade show">
                                <span><i class="mdi mdi-check"></i></span>
                                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span>
                                </button> <?php echo $_SESSION['MSG'];
                                            unset($_SESSION['MSG']);
                                            ?>
                            </div>
                        <?php }

                        ?>
                        <!-- message -->
                        <div class="basic-form">
                            <form role="form" method="post" enctype="multipart/form-data">

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="studentno">Book Subject.</label>
                                        <select name="category_id" class="form-control text-success" tabindex="-1" id="cat" required>
                                            <option value="">--Select--</option>
                                            <?php
                                            $result = mysqli_query($con, "select * from category") or die(mysqli_error($con));
                                            while ($row = mysqli_fetch_array($result)) {
                                                $id = $row['category_id'];
                                            ?>
                                                <option value="<?php echo $row['category_id'] . "|" . $row['shortcode']; ?>"><?php echo $row['classname']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="names">Book Source</label>
                                        <select name="fund" id="fund" class="form-control text-success" tabindex="-1" required="required" disabled="disabled">
                                            <option value="">--Select--</option>
                                            <option value="T">New Purchase</option>
                                            <option value="SP">Specimen/Donation</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="sex">Book Barcode No</label>
                                        <input type="text" class="form-control text-success fw-bolder" name="bkcode" id="bkcode" readonly required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="age">Accession No</label>
                                        <input type="text" readonly class="form-control text-success fw-bloder" name="accno" id="accno" required placeholder="Check if same as Barcode">
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="age">Book Title<span style="color: blue;">*</span></label>
                                        <input type="text" name="book_title" id="first-name2" class="form-control text-danger fw-bloder" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Accession Date</label>
                                        <input type="date" name="accdate" id="accdate" class="form-control" required>

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Author 1</label>
                                        <input type="text" class="form-control" name="author" id="first-name2" required>

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Author2</label>
                                        <input type="text" name="author_2" id="first-name2" class="form-control">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Author3</label>
                                        <input type="text" class="form-control" name="author_3" id="first-name2">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="relation">Status</label>
                                        <select name="status" class="select2_single form-control" tabindex="-1" required="required">
                                            <option value="New">New</option>
                                            <option value="Old">Old</option>
                                            <option value="Lost">Lost</option>
                                            <option value="Damaged">Damaged</option>
                                            <option value="Replacement">Replacement</option>
                                            <option value="Hardbound">Hardbound</option>
                                        </select>

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Copies</label>
                                        <input type="number" name="book_copies" step="1" value="1" min="1" max="1" required="required" readonly class="form-control">

                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="parentname">Publisher Name.</label>
                                        <input type="text" class="form-control" name="publisher_name" id="last-name2">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="parentname">Publication Year.</label>
                                        <input type="text" name="pubyear" id="last-name2" class="form-control">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="parentname">Volume/Edition.</label>
                                        <input type="text" class="form-control" name="vol" id="last-name2">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="parentname">ISBN.</label>
                                        <input type="text" name="isbn" id="isbn" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="relation">Price</label>
                                        <input type="text" class="form-control" name="price" id="price">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="relation">Book Cover Image</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="exampleInputFile" name="image">
                                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="phone1">Fund Source.</label>
                                        <select name="source" type="select" id="hoa" class="form-control" required>
                                            <option value="">--Select--</option>
                                            <option value="0101">Library Development</option>
                                            <option value="0102">RUSA</option>
                                            <option value="0000">Voluntary</option>
                                            <option value="1111">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="district">Invoice No</label>
                                        <input type="text" name="invno" id="last-name2" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="county">Invoice Date</label>
                                        <input type="date" name="invdate" id="invdate" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="village">Purchased From</label>
                                        <input type="text" name="vendor" id="last-name2" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="village">Remark</label>

                                        <textarea name="rem" id="" cols="30" rows="2" id="last-name2" class="form-control"></textarea>
                                    </div>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Save</button>
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
    $("#cat").on("change", function() {
        //   $("select[name='fund']").prop("disabled", !this.checked);
        $('#fund option[value=""]').prop('selected', true);
        $('#bkcode').val("");
        $('#accno').val("");
        if ($('#cat').val()) {
            // do something
            $('#fund').attr('disabled', false);
        } else {
            // do
            alert("Select Subject Name");
            $('#fund').attr('disabled', true);
            $('#fund option[value=""]').prop('selected', true);

            $("#cat").focus();
        }

    });
</script>
<script>
    $("#price,#isbn").on("input", function(evt) {
        var self = $(this);
        self.val(self.val().replace(/[^0-9\.]/g, ''));
        if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
            evt.preventDefault();
        }
    });
</script>
<script>
    $(document).ready(function() {
        $("#submit_form").click(function() {
            // $('#accno').val();
            var accdate = new Date($('#accdate').val());
            var invdate = new Date($('#invdate').val());
            if (new Date(accdate) < new Date(invdate)) { //compare end <=, not >=
                //your code herealert(date);
                alert("Accesion date should be after invoice date");
                return false;
            }
            // else
            // {
            //     alert("Ok");
            // }

        });
    });
</script>
<script>
    $(document).ready(function() {
        /* PREPARE THE SCRIPT */

        $("#fund").change(function() {

            //$(document).on("blur","#regno",function(){ /* WHEN YOU CHANGE AND SELECT FROM THE SELECT FIELD */
            var booktype = $(this).val(); /* GET THE VALUE OF THE SELECTED DATA */

            //  var coursename=$('select[name="category_id"] option:selected').text();
            //  $('#cname').val(coursename);
            var catid = $('#cat').val(); //(coursename);
            //$('#regno').val($('#enrollno').val()); copy input from 1 to other
            // var dataString = "allbooks="+allbooks; /* STORE THAT TO A DATA STRING */
            //alert(courseid);
            //$('#sname').html(courseid);
            if ($('#cat').val() === "") {
                alert("Select Subject Name");
                return false;
            }
            if (booktype) {
                // getRow1(id);
                // alert(catid);
                $.ajax({
                    type: 'POST',
                    url: 'ajax_data.php',
                    data: {
                        booktype_id: booktype,
                        cat_id: catid
                    }, // booktype value passed to ajax via ajax variable booktype_id
                    dataType: 'json',
                    success: function(response) {
                        //  alert(id);
                        // alert(response.accession);
                        $('#bkcode').val(response.barcode);
                        $('#accno').val(response.accession);
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