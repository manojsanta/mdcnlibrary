<!--**********************************
            Content body start
        ***********************************-->
<?php
session_start();
error_reporting(1);
include 'includes/dbconnection.php';
if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
} else {
    if ($_GET['teamid']) {
        $tid = $_GET['teamid'];
    }
    if (isset($_POST['submit'])) {


        // move_uploaded_file($_FILES["image"]["tmp_name"], "book_image/" . $_FILES["image"]["name"]);
        // $book_image = $_FILES["image"]["name"];
        $book_title = mysqli_real_escape_string($con, $_POST['book_title']);

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

        //  $arr = get_defined_vars();
        //     echo "<pre>";
        // print_r($arr);
        // echo "</pre>";
        $result = mysqli_query($con, "UPDATE book set 
                    book_title='$book_title',author='$author',author_2='$author_2',author_3='$author_3',book_copies='$book_copies',book_pub='$book_pub',
                    publisher_name='$publisher_name',isbn='$isbn',copyright_year='$copyright_year',price='$price',
                    status='$status',accdate='$accdate',date_added=NOW(),sourcefund='$sourcefund',remarks='$remark',
                    invno='$invno',invdate='$invdate',vendoname='$vendoname',entryby='$userid' where book_id='$tid'") or die(mysqli_error($con));


        echo '<script>
                        alert("Book details updated Successfully!!");
                        window.location="book_list.php";
                        </script>';
        // $_SESSION['MSG'] = "Your data is updated";
        // header('location: book_list.php');
        // exit;

    }
}
// header("location: view_barcode.php?code=".$gen);

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
                            $_SESSION['uname'] = $row->firstname;
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
                        <h4 class="card-title">Edit Books/Materials</h4>
                        <a href="book_list.php" class="btn btn-primary btn-sm btn-rounded"><i class="fa fa-arrow-left color-info"></i> Back to
                            List</a>
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
                        <?php
                        }
                        // echo $tid;
                        ?>

                        <!-- message -->
                        <div class="basic-form">
                            <?php
                            $query1 = mysqli_query($con, "SELECT book.*,category.* from book LEFT JOIN category ON book.category_id = category.category_id  where book.book_id='$tid'") or die(mysqli_error($con));
                            $row = mysqli_fetch_assoc($query1);
                            ?>
                            <form role="form" method="post" enctype="multipart/form-data">

                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="studentno">Book Subject.</label>
                                        <select name="category_id" class="form-control text-success" tabindex="-1" id="cat" required disabled>
                                            <option value="">--Select--</option>
                                            <?php
                                            $result = mysqli_query($con, "select * from category") or die(mysqli_error($con));
                                            while ($rowbc = mysqli_fetch_array($result)) {
                                                $id = $rowbc['category_id'];
                                            ?>
                                                <option value="<?php echo $rowbc['category_id']; ?>" <?php if ($rowbc['category_id'] == $row['category_id']) echo 'selected="selected"'; ?>>
                                                    <?php echo $rowbc['classname']; ?></option>
                                            <?php
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="names">Book Source</label>
                                        <select name="fund" id="fund" class="form-control text-success" tabindex="-1" required="required" disabled="disabled">
                                            <option value="">--Select--</option>
                                            <option value="T" <?php if ($row['booktype'] == "T") {
                                                                    echo 'selected="selected"';
                                                                }
                                                                ?>>New
                                                Purchase</option>
                                            <option value="SP" <?php if ($row['booktype'] == "SP") {
                                                                    echo 'selected="selected"';
                                                                }
                                                                ?>>
                                                Specimen/Donation</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="sex">Book Barcode No</label>
                                        <input type="text" class="form-control text-success fw-bolder" name="bkcode" id="bkcode" readonly value="<?php echo $row['book_barcode']; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="age">Accession No</label>
                                        <input type="text" readonly class="form-control text-success fw-bloder" name="accno" id="accno" required placeholder="Check if same as Barcode" value="<?php echo $row['bookaccno']; ?>">
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="age">Book Title<span style="color: blue;">*</span></label>
                                        <input type="text" name="book_title" id="first-name2" class="form-control text-danger fw-bloder" required value="<?php echo $row['book_title']; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Accession Date</label>
                                        <input type="date" name="accdate" id="accdate" class="form-control" value="<?php echo $row['accdate']; ?>" required>

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Author 1</label>
                                        <input type="text" class="form-control" name="author" id="first-name2" required value="<?php echo $row['author']; ?>">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Author2</label>
                                        <input type="text" name="author_2" id="first-name2" value="<?php echo $row['author_2']; ?>" class="form-control">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="exampleInputFile">Author3</label>
                                        <input type="text" class="form-control" name="author_3" value="<?php echo $row['author_3']; ?>" id="first-name2">

                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="relation">Status</label>
                                        <select name="status" class="select2_single form-control" tabindex="-1" required="required">
                                            <option value="New" <?php if ($row['status'] == "New") echo 'selected="selected"'; ?>>New
                                            </option>
                                            <option value="Old" <?php if ($row['status'] == "Old") echo 'selected="selected"'; ?>>Old
                                            </option>
                                            <option value="Lost" <?php if ($row['status'] == "Lost") echo 'selected="selected"'; ?>>Lost
                                            </option>
                                            <option value="Damaged" <?php if ($row['status'] == "Damaged") echo 'selected="selected"'; ?>>
                                                Damaged</option>
                                            <option value="Replacement" <?php if ($row['status'] == "Replacement") echo 'selected="selected"'; ?>>
                                                Replacement</option>
                                            <option value="Hardbound" <?php if ($row['status'] == "Hardbound") echo 'selected="selected"'; ?>>
                                                Hardbound</option>
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
                                        <input type="text" class="form-control" name="publisher_name" id="last-name2" value="<?php echo $row['publisher_name']; ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="parentname">Publication Year.</label>
                                        <input type="text" name="pubyear" id="last-name2" class="form-control" value="<?php echo $row['copyright_year']; ?>">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="parentname">Volume/Edition.</label>
                                        <input type="text" class="form-control" name="vol" id="last-name2" value="<?php echo $row['book_pub']; ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="parentname">ISBN.</label>
                                        <input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo $row['isbn']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="relation">Price</label>
                                        <input type="text" class="form-control" name="price" id="price" value="<?php echo $row['price']; ?>">
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
                                            <option value="0101" <?php if ($row['sourcefund'] == "0101") echo 'selected="selected"'; ?>>
                                                Library Development</option>
                                            <option value="0102" <?php if ($row['sourcefund'] == "0102") echo 'selected="selected"'; ?>>RUSA
                                            </option>
                                            <option value="0000" <?php if ($row['sourcefund'] == "0000") echo 'selected="selected"'; ?>>
                                                Voluntary</option>
                                            <option value="1111" <?php if ($row['sourcefund'] == "1111") echo 'selected="selected"'; ?>>
                                                Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="district">Invoice No</label>
                                        <input type="text" name="invno" id="last-name2" class="form-control" value="<?php echo $row['invno']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="county">Invoice Date</label>
                                        <input type="date" name="invdate" id="invdate" value="<?php echo $row['invdate']; ?>" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="village">Purchased From</label>
                                        <input type="text" name="vendor" id="last-name2" value="<?php echo $row['vendoname']; ?>" class="form-control" required>
                                    </div>


                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label for="parentname">Book Image.</label>
                                        <a href=""><?php if ($row['book_image'] != "") : ?>
                                                <img src="upload/<?php echo $row['book_image']; ?>" width="100px" height="100px" style="border:4px groove #CCCCCC; border-radius:5px;">
                                            <?php else : ?>
                                                <img src="images/book_image.jpg" width="100px" height="100px" style="border:4px groove #CCCCCC; border-radius:5px;">
                                            <?php endif; ?>
                                        </a>
                                        <input type="file" style="height:44px; margin-top:10px;" name="image" id="last-name2" class="form-control col-md-7 col-xs-12" />
                                    </div>
                                </div>
                                <a href="book_list.php" class="btn btn-danger">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
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