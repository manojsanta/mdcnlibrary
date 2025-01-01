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
if (isset($_POST['submit'])) {
    $product = $_POST['product'];
    $shortcode = strtoupper($_POST['shortcode']);
    // $catcode = $_POST['catcode'];
    mysqli_query($con, "INSERT INTO category(classname, shortcode) VALUES ('$product','$shortcode')") or die(mysqli_error($con));
    echo "<script>alert('Category Added successfully to Library...');</script>";
    echo "<script>window.location.href ='category.php'</script>";
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
                        <h4 class="card-title">Manage category</h4>
                        <div>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter"><i class="fa fa-plus color-info"></i> Add New Category</button>
                            <!-- <div class="btn-group" role="group"><a href="add_book.php" class="btn btn-success btn-square"><i class="fa fa-plus color-info"></i> Add new Category</a></div> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display text-dark">
                                <thead>
                                    <tr>
                                    <tr>
                                        <th>SlNo.</th>
                                        <th>Category Name</th>
                                        <th>Category ID</th>
                                        <th>Shortcode Class</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php
                                    $i = 0;
                                    $query = mysqli_query($con, "select * from category");
                                    while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td><?php echo ++$i; ?></td>
                                            <td><?php echo $row['classname'] ?></td>
                                            <td><?php echo $row['category_id'] ?></td>
                                            <td><?php echo $row['shortcode'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>

                        <!-- modal area -->
                        <div class="modal fade" id="exampleModalCenter">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Category</h5>
                                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="forms-sample" method="post" enctype="multipart/form-data" action="" class="form-horizontal">
                                            <div class="row ">

                                                <div class="form-group col-md-6">
                                                    <label>Category Name </label>
                                                    <input type="text" name="product" class="form-control" value="" id="product" placeholder="Enter Category/Subject Name" required>
                                                </div>
                                                <div class="form-group col-md-6 ">
                                                    <label>Category ID</label>
                                                    <?php
                                                    $result = mysqli_query($con, "SELECT * FROM category");
                                                    $rows = mysqli_num_rows($result);
                                                    // $catcode = $booktype . $cat_name . str_pad((($rows) + 1), 4, '0', STR_PAD_LEFT);
                                                    // $accession = mysqli_query($con, "SELECT * FROM book where booktype='$booktype'");
                                                    // $rowacc = mysqli_num_rows($accession);
                                                    // $accessionno = "MDCN" . $cat_name . $booktype . str_pad((($rowacc) + 1), 4, '0', STR_PAD_LEFT);
                                                    ?>
                                                    <input readonly type="text" name="catcode" value="<?php echo ++$rows; ?>" class="form-control">

                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="form-group col-md-6">
                                                    <label>Category Shortcode</label>
                                                    <input type="text" name="shortcode" value="" placeholder="Enter Shortcode" class="form-control" id="price" required>
                                                </div>

                                            </div>
                                            <!-- <button type="submit" style="float: left;" name="save" class="btn btn-primary  mr-2 mb-4">Save</button> -->

                                            <!-- <p>Cras mattis consectetur purus sit amet fermentum. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p> -->
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="submit" type="submit">Save changes</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- modal area enda -->
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