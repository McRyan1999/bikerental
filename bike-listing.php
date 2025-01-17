<?php
session_start();
include('includes/config.php');
error_reporting(0);
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<meta charset="utf-8">
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
</head>

<body>
<!-- Header -->
<?php include('includes/header.php'); ?>

<!-- Page Header -->
<section class="page-header listing_page">
  <div class="container">
    <div class="page-header_wrap">
      <div class="page-heading">
        <h1>Bike Listing</h1>
      </div>
      <ul class="coustom-breadcrumb">
        <li><a href="index.php">Home</a></li>
        <li>Bike Listing</li>
      </ul>
    </div>
  </div>
</section>

<!-- Bike Listings Section -->
<section class="listing-page">
  <div class="container">
    <div class="row">
      <!-- Main Content -->
      <div class="col-md-9 col-md-push-3">
        <div class="result-sorting-wrapper">
          <div class="sorting-count">
            <?php
            // Get Total Listings Count
            $sql = "SELECT COUNT(id) AS total FROM tblvehicles";
            $query = $dbh->prepare($sql);
            $query->execute();
            $countResult = $query->fetch(PDO::FETCH_OBJ);
            ?>
            <p><span><?php echo htmlentities($countResult->total); ?> Listings</span></p>
          </div>
        </div>

        <!-- Fetch and Display Bike Listings -->
        <?php
        $sql = "SELECT tblvehicles.*, tblbrands.BrandName 
                FROM tblvehicles 
                JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand";
        $query = $dbh->prepare($sql);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
          foreach ($results as $result) { ?>
            <div class="product-listing-m gray-bg">
              <div class="product-listing-img">
                <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id); ?>">
                  <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1); ?>" class="img-responsive" alt="Bike Image">
                </a>
              </div>
              <div class="product-listing-content">
                <h5>
                  <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id); ?>">
                    <?php echo htmlentities($result->BrandName); ?>, <?php echo htmlentities($result->VehiclesTitle); ?>
                  </a>
                </h5>
                <p class="list-price">$<?php echo htmlentities($result->PricePerDay); ?> Per Day</p>
                <ul>
                  <li><i class="fa fa-user"></i> <?php echo htmlentities($result->SeatingCapacity); ?> seats</li>
                  <li><i class="fa fa-calendar"></i> <?php echo htmlentities($result->ModelYear); ?> model</li>
                  <li><i class="fa fa-motorcycle"></i> <?php echo htmlentities($result->FuelType); ?></li>
                </ul>
                <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id); ?>" class="btn">View Details</a>
              </div>
            </div>
          <?php }
        } else {
          echo "<p class='text-center'>No bikes available at the moment.</p>";
        }
        ?>
      </div>

      <!-- Sidebar -->
      <aside class="col-md-3 col-md-pull-9">
        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-filter"></i> Find Your Bike</h5>
          </div>
          <div class="sidebar_filter">
            <form action="search-carresult.php" method="post">
              <div class="form-group select">
                <select class="form-control" name="brand">
                  <option>Select Brand</option>
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
                  <option>Select Fuel Type</option>
                  <option value="Petrol">Petrol</option>
                  <option value="Diesel">Diesel</option>
                  <option value="CNG">CNG</option>
                </select>
              </div>
              <button type="submit" class="btn btn-block"><i class="fa fa-search"></i> Search Bike</button>
            </form>
          </div>
        </div>

        <!-- Recently Listed Bikes -->
        <div class="sidebar_widget">
          <div class="widget_heading">
            <h5><i class="fa fa-motorcycle"></i> Recently Listed Bikes</h5>
          </div>
          <div class="recent_addedcars">
            <ul>
              <?php
              $sql = "SELECT tblvehicles.*, tblbrands.BrandName 
                      FROM tblvehicles 
                      JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand 
                      ORDER BY id DESC 
                      LIMIT 4";
              $query = $dbh->prepare($sql);
              $query->execute();
              $recentBikes = $query->fetchAll(PDO::FETCH_OBJ);

              if ($query->rowCount() > 0) {
                foreach ($recentBikes as $bike) { ?>
                  <li class="gray-bg">
                    <div class="recent_post_img">
                      <a href="vehical-details.php?vhid=<?php echo htmlentities($bike->id); ?>">
                        <img src="admin/img/vehicleimages/<?php echo htmlentities($bike->Vimage1); ?>" alt="Bike Image">
                      </a>
                    </div>
                    <div class="recent_post_title">
                      <a href="vehical-details.php?vhid=<?php echo htmlentities($bike->id); ?>">
                        <?php echo htmlentities($bike->BrandName); ?>, <?php echo htmlentities($bike->VehiclesTitle); ?>
                      </a>
                      <p class="widget_price">$<?php echo htmlentities($bike->PricePerDay); ?> Per Day</p>
                    </div>
                  </li>
                <?php }
              } else {
                echo "<p class='text-center'>No recently listed bikes available.</p>";
              }
              ?>
            </ul>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>
</body>
</html>
