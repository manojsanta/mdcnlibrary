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
                        <h4 class="card-title">Summary of Books</h4>
                        <div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-secondary dropdown-toggle btn-square" data-toggle="dropdown"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="print_barcode1.php" target="_blank">Book Barcode</a>
                                    <a class="dropdown-item" href="book_print.php" target="_blank">Book List</a>
                                </div>
                            </div>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-warning dropdown-toggle btn-square" data-toggle="dropdown"><i class="fa fa-search" aria-hidden="true"></i> View</button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void()">Dropdown link</a>
                                    <a class="dropdown-item" href="javascript:void()">Dropdown link</a>
                                </div>
                            </div>
                            <div class="btn-group" role="group"><a href="add_book.php" class="btn btn-success btn-square"><i class="fa fa-plus color-info"></i> Add new Book</a></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="display text-dark table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">#</th>
                                        <th rowspan="2">Subject</th>
                                        <th rowspan="2">Total Books</th>
                                        <th colspan="4">Purchased</th>
                                        <th colspan="4">Specimen</th>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <th>Issued</th>
                                        <th>Stock</th>
                                        <th>Balance</th>
                                        <th>Total</th>
                                        <th>Issued</th>
                                        <th>Stock</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "select category.classname,count(*) as total_count, count(if(booktype='T',1,null)) as Purchased, count(if(booktype='SP',1,null)) as Specimen, count(if(booktype='T' and book_copies=0,1,null)) as Puchase_Issue, count(if(booktype='T' and book_copies=1,1,null)) as Puchase_Stock, count(if(booktype='SP' and book_copies=0,1,null)) as Specimen_Issue, count(if(booktype='SP' and book_copies=1,1,null)) as Specimen_stock, count(if(booktype='T',1,null))-count(if(booktype='T' and book_copies=0,1,null)) as balance_purchase, count(if(booktype='SP',1,null)) -count(if(booktype='SP' and book_copies=0,1,null)) as balance_specimen from book inner join category on book.category_id=category.category_id GROUP by category.classname";
                                    $result = mysqli_query($con, $sql) or die(mysqli_error($con));
                                    $i = 0;
                                    $tc = $pur = $pi = $ps = $pbal = $sp = $spi = $sps = $spbal = 0;
                                    while ($row = mysqli_fetch_array($result)) {
                                        $tc += $row['total_count'];
                                        $pur += $row['Purchased'];
                                        $pi += $row['Puchase_Issue'];
                                        $ps += $row['Puchase_Stock'];
                                        $pbal += $row['balance_purchase'];
                                        $sp += $row['Specimen'];
                                        $spi += $row['Specimen_Issue'];
                                        $sps += $row['Specimen_stock'];
                                        $spbal += $row['balance_specimen'];
                                    ?>
                                        <tr>
                                            <td><?php echo ++$i; ?></td>
                                            <td><?php echo $row['classname'] ?></td>
                                            <td style="color:#079992;font-size:18px;"><?php echo $row['total_count'] ?></td>
                                            <td><?php echo $row['Purchased'] ?></td>
                                            <td><?php echo $row['Puchase_Issue'] ?></td>
                                            <td><?php echo $row['Puchase_Stock'] ?></td>
                                            <?php
                                            if ($row['balance_purchase'] > 10) { ?>
                                                <td style="color:white;font-size:18px;background-color:#26de81;font-face:bold;"><?php echo $row['balance_purchase'] ?></td>
                                            <?php    } else { ?>
                                                <td style="color:white;font-size:18px;background-color:#d63031;"><?php echo $row['balance_purchase'] ?></td>
                                            <?php
                                            }
                                            ?>
                                            <td><?php echo $row['Specimen'] ?></td>
                                            <td><?php echo $row['Specimen_Issue'] ?></td>
                                            <td><?php echo $row['Specimen_stock'] ?></td>
                                            <?php
                                            if ($row['balance_specimen'] > 10) { ?>
                                                <td style="color:green;font-size:18px;background-color:#26de81;"><?php echo $row['balance_specimen'] ?></td>
                                            <?php    } else { ?>
                                                <td style="color:white;font-size:18px;background-color:#d63031;"><?php echo $row['balance_specimen'] ?></td>
                                            <?php
                                            }
                                            ?>
                                            <!-- <td style="color:green;font-size:18px;"></td> -->
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot style="color:#0984e3;font-size:18px;">
                                    <th colspan="2">Grand Total</th>

                                    <th><?php echo $tc; ?></th>
                                    <th><?php echo $pur; ?></th>
                                    <th><?php echo $pi; ?></th>
                                    <th><?php echo $ps; ?></th>
                                    <th><?php echo $pbal; ?></th>
                                    <th><?php echo $sp; ?></th>
                                    <th><?php echo $spi; ?></th>
                                    <th><?php echo $sps; ?></th>
                                    <th><?php echo $spbal; ?></th>
                                </tfoot>


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