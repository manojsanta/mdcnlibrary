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
    $code = $_GET['code'];
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
                        <h4 class="card-title">Barcode of the Material</h4>
                        <a href="add_book.php" class="btn btn-primary"><i class="fa fa-plus color-info"></i> Add new Book</a>
                    </div>
                    <div class="card-body">
                        <form role="form" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="new_barcode" value="<?php echo $new_barcode; ?>">
                            <div class="card-body">
                                <span style="color: brown">
                                    <h5>Book details</h5>
                                </span>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-md-4 offset-3">
                                        <?php echo "<img style='border:2px solid black; padding:15px;' src = 'includes/barcode.php?codetype=Code39&size=40&text=" . $code . "&print=true'/>"; ?>
                                    </div>



                                </div>



                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <a class="btn btn-primary" href="book_list.php"><i class="icon-ok"></i> Ok</a>
                            </div>
                        </form>
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