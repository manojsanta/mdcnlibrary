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
                        <h4 class="card-title">Borrowed Books Monitoring</h4>
                        <div>

                            <!-- <div class="btn-group" role="group">
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
                            </div> -->
                            <a href="print_borrowed_books.php" target="_blank" style="background:none;">
                                <button class="btn btn-danger rounded-0"><i class="fa fa-print"></i> Print</button>
                            </a>
                            <a class="btn btn-dark rounded-0" href="export_book_issue.php?id=1&subid=1">
                                <i class="fa fa-print"></i> Export to Excel
                            </a>
                            <!-- <div class="btn-group" role="group"><a href="#" class="btn btn-success btn-square text-white"><i class="fa fa-file color-info"></i> Export</a></div> -->
                        </div>
                    </div>
                    <div class="card-body">

                        <form class="form-inline" action="">
                            <label for="email">Date From:</label>
                            <input type="date" style="color:black;" value="<?php echo date('Y-m-d'); ?>" name="datefrom" class="mr-1 ml-2 form-control col-sm-3 has-feedback-left" placeholder="Date From" aria-describedby="inputSuccess2Status4" required />
                            <!-- <input type="email" class="form-control" id="email" placeholder="Enter Username" name="email"> -->
                            <label for="pwd">Date To:</label>
                            <input type="date" style="color:black;" value="<?php echo date('Y-m-d'); ?>" name="dateto" class="mr-1 ml-2 form-control has-feedback-left col-sm-3" placeholder="Date To" aria-describedby="inputSuccess2Status4" required />
                            <button type="submit" name="search" class="btn btn-primary btn-outline ml-2"><i class="fa fa-calendar-o"></i> Search</button>
                        </form>
                        <!-- search area -->

                        <!-- search area -->
                        <div class="table-responsive mt-4">
                            <?php
                            $where = "";
                            if (isset($_GET['search'])) {
                                $where = " and (date(borrow_book.date_borrowed) between '" . date("Y-m-d", strtotime($_GET['datefrom'])) . "' and '" . date("Y-m-d", strtotime($_GET['dateto'])) . "' ) ";
                            }

                            $return_query = mysqli_query($con, "SELECT * from borrow_book
							LEFT JOIN book ON borrow_book.book_id = book.book_id
							LEFT JOIN user ON borrow_book.user_id = user.user_id
							where borrow_book.borrowed_status = 'borrowed' $where order by borrow_book.borrow_book_id DESC") or die(mysqli_error($con));
                            $return_count = mysqli_num_rows($return_query);

                            // $count_penalty = mysqli_query($con,"SELECT sum(book_penalty) FROM return_book ")or die(mysqli_error());
                            // $count_penalty_row = mysqli_fetch_array($count_penalty);

                            ?>
                            <table id="example" class="table display">
                                <thead>
                                    <tr>
                                        <th>Barcode</th>
                                        <th>Borrower Name</th>
                                        <th>Book Title</th>
                                        <th>Subject</th>
                                        <!---	<th>Author</th>
									<th>ISBN</th>	-->
                                        <th>Date Borrowed</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($return_row = mysqli_fetch_array($return_query)) {
                                        $id = $return_row['borrow_book_id'];
                                    ?>
                                        <tr>
                                            <td><?php echo $return_row['book_barcode']; ?></td>
                                            <td style="text-transform: capitalize"><?php echo $return_row['fullname']; ?></td>
                                            <td style="text-transform: capitalize"><?php echo $return_row['book_title']; ?></td>
                                            <td style="text-transform: capitalize"><?php echo $return_row['subject']; ?></td>

                                            <td><?php echo date("M d, Y h:m:s a", strtotime($return_row['date_borrowed'])); ?></td>
                                            <td><?php echo date("M d, Y h:m:s a", strtotime($return_row['due_date'])); ?></td>


                                        </tr>

                                    <?php
                                    }
                                    if ($return_count <= 0) {
                                        echo '
									<table style="float:right;">
										<tr>
											<td style="padding:10px;" class="alert alert-danger">No Books Borowed at this moment</td>
										</tr>
									</table>
								';
                                    }
                                    ?>
                                </tbody>

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