<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Get page type from query parameter
$pagetype = $_GET['type'] ?? '';

// Fetch page details from the database
function getPageDetails($dbh, $pagetype) {
    $sql = "SELECT type, detail, PageName FROM tblpages WHERE type = :pagetype";
    $query = $dbh->prepare($sql);
    $query->bindParam(':pagetype', $pagetype, PDO::PARAM_STR);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

// Fetch page details
$pageDetails = getPageDetails($dbh, $pagetype);
?>
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bike Rental Portal | Page Details</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" id="switcher-css" href="assets/switcher/css/switcher.css">
    <link rel="alternate stylesheet" href="assets/switcher/css/red.css" title="red" data-default-color="true">
    <link rel="alternate stylesheet" href="assets/switcher/css/orange.css" title="orange">
    <link rel="alternate stylesheet" href="assets/switcher/css/blue.css" title="blue">
    <link rel="alternate stylesheet" href="assets/switcher/css/pink.css" title="pink">
    <link rel="alternate stylesheet" href="assets/switcher/css/green.css" title="green">
    <link rel="alternate stylesheet" href="assets/switcher/css/purple.css" title="purple">
    <link rel="shortcut icon" href="assets/images/favicon-icon/24x24.png">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
</head>

<body>
    <!-- Switcher -->
    <?php include('includes/colorswitcher.php'); ?>
    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <?php if (!empty($pageDetails)): ?>
        <?php foreach ($pageDetails as $page): ?>
            <!-- Page Header -->
            <section class="page-header aboutus_page">
                <div class="container">
                    <div class="page-header_wrap">
                        <div class="page-heading">
                            <h1><?php echo htmlentities($page->PageName); ?></h1>
                        </div>
                        <ul class="coustom-breadcrumb">
                            <li><a href="index.php">Home</a></li>
                            <li><?php echo htmlentities($page->PageName); ?></li>
                        </ul>
                    </div>
                </div>
                <div class="dark-overlay"></div>
            </section>

            <!-- Page Content -->
            <section class="about_us section-padding">
                <div class="container">
                    <div class="section-header text-center">
                        <h2><?php echo htmlentities($page->PageName); ?></h2>
                        <p><?php echo htmlentities($page->detail); ?></p>
                    </div>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <section class="about_us section-padding">
            <div class="container">
                <div class="section-header text-center">
                    <h2>Page Not Found</h2>
                    <p>The page you are looking for does not exist.</p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Back to Top -->
    <div id="back-top" class="back-top">
        <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i></a>
    </div>

    <!-- Login Form -->
    <?php include('includes/login.php'); ?>
    <!-- Registration Form -->
    <?php include('includes/registration.php'); ?>
    <!-- Forgot Password Form -->
    <?php include('includes/forgotpassword.php'); ?>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/interface.js"></script>
    <script src="assets/switcher/js/switcher.js"></script>
    <script src="assets/js/bootstrap-slider.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
</body>

</html>
