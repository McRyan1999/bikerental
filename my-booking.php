<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Redirect to login page if session is not active
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
}

// Function to fetch user data
function fetchUserData($dbh, $useremail) {
    $sql = "SELECT * FROM tblusers WHERE EmailId = :useremail";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->execute();
    return $query->fetch(PDO::FETCH_OBJ);
}

// Function to fetch bookings
function fetchUserBookings($dbh, $useremail) {
    $sql = "SELECT 
                tblvehicles.Vimage1 AS Vimage1, 
                tblvehicles.VehiclesTitle AS VehiclesTitle, 
                tblvehicles.id AS vid, 
                tblbrands.BrandName AS BrandName, 
                tblbooking.FromDate AS FromDate, 
                tblbooking.ToDate AS ToDate, 
                tblbooking.message AS message, 
                tblbooking.Status AS Status 
            FROM tblbooking 
            JOIN tblvehicles ON tblbooking.VehicleId = tblvehicles.id 
            JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
            WHERE tblbooking.userEmail = :useremail";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

// Fetch user details and bookings
$useremail = $_SESSION['login'];
$userData = fetchUserData($dbh, $useremail);
$userBookings = fetchUserBookings($dbh, $useremail);
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BikeForYou - My Bookings</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
</head>

<body>
    <!-- Start Switcher -->
    <?php include('includes/colorswitcher.php'); ?>
    <!-- /Switcher -->

    <!--Header-->
    <?php include('includes/header.php'); ?>
    <!-- /Header -->

    <!-- Page Header -->
    <section class="page-header profile_page">
        <div class="container">
            <div class="page-header_wrap">
                <div class="page-heading">
                    <h1>My Booking</h1>
                </div>
                <ul class="coustom-breadcrumb">
                    <li><a href="index.php">Home</a></li>
                    <li>My Booking</li>
                </ul>
            </div>
        </div>
        <div class="dark-overlay"></div>
    </section>
    <!-- /Page Header -->

    <section class="user_profile inner_pages">
        <div class="container">
            <div class="user_profile_info gray-bg padding_4x4_40">
                <div class="upload_user_logo">
                    <img src="assets/images/dealer-logo.jpg" alt="User Image">
                </div>
                <div class="dealer_info">
                    <h5><?php echo htmlentities($userData->FullName); ?></h5>
                    <p>
                        <?php echo htmlentities($userData->Address); ?><br>
                        <?php echo htmlentities($userData->City); ?>, <?php echo htmlentities($userData->Country); ?>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <?php include('includes/sidebar.php'); ?>
                </div>
                <div class="col-md-9">
                    <div class="profile_wrap">
                        <h5 class="uppercase underline">My Bookings</h5>
                        <div class="my_vehicles_list">
                            <ul class="vehicle_listing">
                                <?php if ($userBookings): ?>
                                    <?php foreach ($userBookings as $booking): ?>
                                        <li>
                                            <div class="vehicle_img">
                                                <a href="vehical-details.php?vhid=<?php echo htmlentities($booking->vid); ?>">
                                                    <img src="admin/img/vehicleimages/<?php echo htmlentities($booking->Vimage1); ?>" alt="Vehicle Image">
                                                </a>
                                            </div>
                                            <div class="vehicle_title">
                                                <h6>
                                                    <a href="vehical-details.php?vhid=<?php echo htmlentities($booking->vid); ?>">
                                                        <?php echo htmlentities($booking->BrandName); ?>, <?php echo htmlentities($booking->VehiclesTitle); ?>
                                                    </a>
                                                </h6>
                                                <p>
                                                    <b>From Date:</b> <?php echo htmlentities($booking->FromDate); ?><br>
                                                    <b>To Date:</b> <?php echo htmlentities($booking->ToDate); ?>
                                                </p>
                                            </div>
                                            <div class="vehicle_status">
                                                <?php if ($booking->Status == 1): ?>
                                                    <a href="#" class="btn outline btn-xs active-btn">Confirmed</a>
                                                <?php elseif ($booking->Status == 2): ?>
                                                    <a href="#" class="btn outline btn-xs">Cancelled</a>
                                                <?php else: ?>
                                                    <a href="#" class="btn outline btn-xs">Not Confirmed</a>
                                                <?php endif; ?>
                                                <div class="clearfix"></div>
                                            </div>
                                            <p><b>Message:</b> <?php echo htmlentities($booking->message); ?></p>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No bookings found.</p>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include('includes/footer.php'); ?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/interface.js"></script>
</body>

</html>
