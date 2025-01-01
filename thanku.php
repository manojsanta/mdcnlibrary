<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MDCN,Library Signup</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/password-resets/password-reset-7/assets/css/password-reset-7.css">


</head>

<body style="background-color: #ff3838;">
    <!-- Password Reset 7 - Bootstrap Brain Component -->
    <section class=" p-3 p-md-4 p-xl-5" style="background-color: #ff3838;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                    <div class="card border border-light-subtle rounded-4">
                        <div class="card-body p-4 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">

                                        <h2 class="h4 text-center text-primary">Thank you for Registration</h2>
                                        <h3 class="fs-9 fw-normal text-danger text-center m-0">
                                            <?php if (isset($_GET['user'])) {
                                                $message = urldecode($_GET['user']);
                                                echo $message;
                                            } ?></h3>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                    <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
                                        <a href="#!" class="btn btn-primary form-control rounded-0">Login</a>
                                        <a href="#!" class="btn btn-success form-control rounded-0">Register</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>