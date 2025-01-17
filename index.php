<?php
// Start the session
session_start();

// Include configuration file
require_once 'includes/config.php';

// Set error reporting level to exclude notices
error_reporting(E_ALL & ~E_NOTICE);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="bike rental">
    <meta name="description" content="Explore a wide range of bikes for rent.">
    <title>Bike Rental Portal</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css">
    <link rel="stylesheet" href="assets/css/slick.css">
    <link rel="stylesheet" href="assets/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">

    <!-- Color Switcher -->
    <link rel="stylesheet" href="assets/switcher/css/switcher.css" id="switcher-css">
    <link rel="alternate stylesheet" href="assets/switcher/css/red.css" title="red">
    <link rel="alternate stylesheet" href="assets/switcher/css/orange.css" title="orange">
    <link rel="alternate stylesheet" href="assets/switcher/css/blue.css" title="blue">
    <link rel="alternate stylesheet" href="assets/switcher/css/pink.css" title="pink">
    <link rel="alternate stylesheet" href="assets/switcher/css/green.css" title="green">
    <link rel="alternate stylesheet" href="assets/switcher/css/purple.css" title="purple">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
    <link rel="shortcut icon" href="assets/images/favicon-icon/24x24.png">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
</head>
<body>

<!-- Color Switcher -->
<?php include 'includes/colorswitcher.php'; ?>

<!-- Header -->
<?php include 'includes/header.php'; ?>

<!-- Banner Section -->
<section id="banner" class="banner-section">
    <div class="container">
        <div class="div_zindex">
            <div class="row">
                <div class="col-md-5 col-md-push-7">
                    <div class="banner_content">
                        <h1>Find Your Perfect Bike</h1>
                        <p>We offer a wide variety of bikes to suit your needs.</p>
                        <a href="#" class="btn">Read More <span class="angle_arrow"><i class="fa fa-angle-right"></i></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recently Listed Bikes -->
<section class="section-padding gray-bg">
    <div class="container">
        <div class="section-header text-center">
            <h2>Find the Best <span>Bike For You</span></h2>
            <p>Enjoy your holidays with the perfect bike. Our team is here to assist you with all your rental needs!</p>
        </div>
        <div class="row">
            <div class="recent-tab">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#recent-bikes" role="tab" data-toggle="tab">New Bikes</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="recent-bikes">
                    <?php
                    $sql = "SELECT v.VehiclesTitle, b.BrandName, v.PricePerDay, v.FuelType, v.ModelYear, v.id, v.SeatingCapacity, v.VehiclesOverview, v.Vimage1 
                            FROM tblvehicles v
                            JOIN tblbrands b ON b.id = v.VehiclesBrand";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                            ?>
                            <div class="col-list-3">
                                <div class="recent-car-list">
                                    <div class="car-info-box">
                                        <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id); ?>">
                                            <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1); ?>" class="img-responsive" alt="Bike">
                                        </a>
                                        <ul>
                                            <li><i class="fa fa-car"></i> <?php echo htmlentities($result->FuelType); ?></li>
                                            <li><i class="fa fa-calendar"></i> <?php echo htmlentities($result->ModelYear); ?> Model</li>
                                            <li><i class="fa fa-user"></i> <?php echo htmlentities($result->SeatingCapacity); ?> seats</li>
                                        </ul>
                                    </div>
                                    <div class="car-title-m">
                                        <h6><a href="vehical-details.php?vhid=<?php echo htmlentities($result->id); ?>">
                                                <?php echo htmlentities($result->BrandName); ?>, <?php echo htmlentities($result->VehiclesTitle); ?>
                                            </a></h6>
                                        <span class="price">$<?php echo htmlentities($result->PricePerDay); ?> /Day</span>
                                    </div>
                                    <div class="inventory_info_m">
                                        <p><?php echo substr($result->VehiclesOverview, 0, 70); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

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
