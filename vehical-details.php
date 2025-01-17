<?php
session_start();
include('includes/config.php');
error_reporting(0);

if (isset($_POST['submit'])) {
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $message = $_POST['message'];
    $useremail = $_SESSION['login'];
    $status = 0;
    $vhid = $_GET['vhid'];

    try {
        $sql = "INSERT INTO tblbooking (userEmail, VehicleId, FromDate, ToDate, message, Status) 
                VALUES (:useremail, :vhid, :fromdate, :todate, :message, :status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
        $query->bindParam(':vhid', $vhid, PDO::PARAM_INT);
        $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
        $query->bindParam(':todate', $todate, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        $query->bindParam(':status', $status, PDO::PARAM_INT);

        if ($query->execute()) {
            echo "<script>alert('Booking successful.');</script>";
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
    }
}

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Rental Port | Vehicle Details</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
</head>
<body>

<!--Header-->
<?php include('includes/header.php'); ?>
<!--/Header-->

<?php
$vhid = intval($_GET['vhid']);
$sql = "SELECT tblvehicles.*, tblbrands.BrandName, tblbrands.id AS bid 
        FROM tblvehicles 
        JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
        WHERE tblvehicles.id = :vhid";
$query = $dbh->prepare($sql);
$query->bindParam(':vhid', $vhid, PDO::PARAM_INT);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if ($query->rowCount() > 0) {
    foreach ($results as $result) {
        $_SESSION['brndid'] = $result->bid;
?>
<section id="listing_img_slider">
    <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1); ?>" class="img-responsive" alt="image"></div>
    <?php if (!empty($result->Vimage2)) { ?>
    <div><img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage2); ?>" class="img-responsive" alt="image"></div>
    <?php } ?>
</section>

<section class="listing-detail">
    <div class="container">
        <div class="listing_detail_head row">
            <div class="col-md-9">
                <h2><?php echo htmlentities($result->BrandName); ?> , <?php echo htmlentities($result->VehiclesTitle); ?></h2>
            </div>
            <div class="col-md-3">
                <div class="price_info">
                    <p>$<?php echo htmlentities($result->PricePerDay); ?> </p>Per Day
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="main_features">
                    <ul>
                        <li><i class="fa fa-calendar"></i><h5><?php echo htmlentities($result->ModelYear); ?></h5><p>Reg.Year</p></li>
                        <li><i class="fa fa-cogs"></i><h5><?php echo htmlentities($result->FuelType); ?></h5><p>Fuel Type</p></li>
                        <li><i class="fa fa-user-plus"></i><h5><?php echo htmlentities($result->SeatingCapacity); ?></h5><p>Seats</p></li>
                    </ul>
                </div>

                <div class="listing_more_info">
                    <div class="listing_detail_wrap">
                        <ul class="nav nav-tabs gray-bg" role="tablist">
                            <li role="presentation" class="active"><a href="#vehicle-overview" role="tab" data-toggle="tab">Vehicle Overview</a></li>
                            <li role="presentation"><a href="#accessories" role="tab" data-toggle="tab">Accessories</a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="vehicle-overview">
                                <p><?php echo htmlentities($result->VehiclesOverview); ?></p>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="accessories">
                                <ul>
                                    <li>AntiLock Braking System: <?php echo $result->AntiLockBrakingSystem ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>'; ?></li>
                                    <li>Leather Seats: <?php echo $result->LeatherSeats ? '<i class="fa fa-check"></i>' : '<i class="fa fa-close"></i>'; ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <aside class="col-md-3">
                <div class="sidebar_widget">
                    <div class="widget_heading">
                        <h5><i class="fa fa-envelope"></i>Book Now</h5>
                    </div>
                    <form method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" name="fromdate" placeholder="From Date (dd/mm/yyyy)" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="todate" placeholder="To Date (dd/mm/yyyy)" required>
                        </div>
                        <div class="form-group">
                            <textarea rows="4" class="form-control" name="message" placeholder="Message" required></textarea>
                        </div>
                        <?php if ($_SESSION['login']) { ?>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name="submit" value="Book Now">
                        </div>
                        <?php } else { ?>
                        <a href="#loginform" class="btn btn-secondary" data-toggle="modal" data-dismiss="modal">Login to Book</a>
                        <?php } ?>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</section>
<?php 
    } 
}
?>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
