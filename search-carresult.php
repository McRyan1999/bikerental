<?php
session_start();
include('includes/config.php');
error_reporting(0);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Bike Rental Portal | Bike Listing</title>

<!-- Stylesheets -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/styles.css">
<link rel="stylesheet" href="assets/css/owl.carousel.css">
<link rel="stylesheet" href="assets/css/owl.transitions.css">
<link href="assets/css/slick.css" rel="stylesheet">
<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
<link href="assets/css/font-awesome.min.css" rel="stylesheet">

<!-- Switcher -->
<link rel="stylesheet" href="assets/switcher/css/switcher.css">
</head>

<body>

<!-- Start Switcher -->
<?php include('includes/colorswitcher.php'); ?>
<!-- /Switcher -->

<!-- Header -->
<?php include('includes/header.php'); ?>
<!-- /Header -->

<!-- Page Header -->
<section class="page-header listing_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>Bike Listing</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="#">Home</a></li>
        <li>Bike Listing</li>
      </ul>
    </div>
  </div>
  <div class="dark-overlay"></div>
</section>
<!-- /Page Header -->

<!-- Listing Section -->
<section class="listing-page">
  <div class="container">
    <div class="row">
      <div class="col-md-9 col-md-push-3">
        <div class="result-sorting-wrapper">
          <div class="sorting-count">
            <?php
            // Check if filter is set
            $brand = isset($_POST['brand']) ? trim(htmlspecialchars($_POST['brand'])) : "";
            $fueltype = isset($_POST['fueltype']) ? trim(htmlspecialchars($_POST['fueltype'])) : "";

            // Query for listing count
            $sql = "SELECT COUNT(id) as total FROM tblvehicles WHERE VehiclesBrand LIKE :brand AND FuelType LIKE :fueltype";
            $query = $dbh->prepare($sql);
            $query->bindValue(':brand', "%$brand%", PDO::PARAM_STR);
            $query->bindValue(':fueltype', "%$fueltype%", PDO::PARAM_STR);
            $query->execute();
            $countResult = $query->fetch(PDO::FETCH_OBJ);
            ?>
            <p><span><?php echo htmlentities($countResult->total); ?> Listings</span></p>
          </div>
        </div>

        <?php
        // Fetch Bike Listings
        $sql = "SELECT tblvehicles.*, tblbrands.BrandName FROM tblvehicles 
                JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
                WHERE tblvehicles.VehiclesBrand LIKE :brand AND tblvehicles.FuelType LIKE :fueltype";
        $query = $dbh->prepare($sql);
        $query->bindValue(':brand', "%$brand%", PDO::PARAM_STR);
        $query->bindValue(':fueltype', "%$fueltype%", PDO::PARAM_STR);
        $query->execute();
        $vehicles = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
          foreach ($vehicles as $vehicle) { ?>
            <div class="product-listing-m gray-bg">
              <div class="product-listing-img">
                <a href="vehical-details.php?vhid=<?php echo htmlentities($vehicle->id); ?>">
                  <img src="admin/img/vehicleimages/<?php echo htmlentities($vehicle->Vimage1); ?>" class="img-responsive" alt="Bike Image">
                </a>
              </div>
              <div class="product-listing-content">
                <h5><a href="vehical-details.php?vhid=<?php echo htmlentities($vehicle->id); ?>">
                  <?php echo htmlentities($vehicle->BrandName); ?>, <?php echo htmlentities($vehicle->VehiclesTitle); ?>
                </a></h5>
                <p class="list-price">$<?php echo htmlentities($vehicle->PricePerDay); ?> Per Day</p>
                <ul>
                  <li><i class="fa fa-user"></i> <?php echo htmlentities($vehicle->SeatingCapacity); ?> seats</li>
                  <li><i class="fa fa-calendar"></i> <?php echo htmlentities($vehicle->ModelYear); ?> model</li>
                  <li><i class="fa fa-car"></i> <?php echo htmlentities($vehicle->FuelType); ?></li>
                </ul>
                <a href="vehical-details.php?vhid=<?php echo htmlentities($vehicle->id); ?>" class="btn">View Details</a>
              </div>
            </div>
          <?php }
        } else {
          echo "<p class='text-center'>No bikes found matching your criteria.</p>";
        }
        ?>
      </div>

      <!-- Sidebar -->
      <aside class="col-md-3 col-md-pull-9">
        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-filter"></i> Find Your Bike</h5>
          </div>
          <form action="search-carresult.php" method="post">
            <div class="form-group select">
              <select class="form-control" name="brand">
                <option value="">Select Brand</option>
                <?php
                $sql = "SELECT * FROM tblbrands";
                $query = $dbh->prepare($sql);
                $query->execute();
                $brands = $query->fetchAll(PDO::FETCH_OBJ);
                foreach ($brands as $brand) {
                  echo "<option value='" . htmlentities($brand->id) . "'>" . htmlentities($brand->BrandName) . "</option>";
                }
                ?>
              </select>
            </div>
            <div class="form-group select">
              <select class="form-control" name="fueltype">
                <option value="">Select Fuel Type</option>
                <option value="Petrol">Petrol</option>
                <option value="Diesel">Diesel</option>
                <option value="CNG">CNG</option>
              </select>
            </div>
            <button type="submit" class="btn btn-block"><i class="fa fa-search"></i> Search Bike</button>
          </form>
        </div>
      </aside>
      <!-- /Sidebar -->
    </div>
  </div>
</section>

<!-- Footer -->
<?php include('includes/footer.php'); ?>
<!-- /Footer -->

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
