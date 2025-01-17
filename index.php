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
<title>Bike Rental Portal</title>

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

<!-- Banner Section -->
<section id="banner" class="banner-section">
  <div class="container">
    <div class="div_zindex">
      <div class="row">
        <div class="col-md-5 col-md-push-7">
          <div class="banner_content">
            <h1>Find Your Perfect Bike</h1>
            <p>We have more than a thousand bikes for you to choose from.</p>
            <a href="#" class="btn">Read More <span class="angle_arrow"><i class="fa fa-angle-right"></i></span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Recent Bikes Section -->
<section class="section-padding gray-bg">
  <div class="container">
    <div class="section-header text-center">
      <h2>Find the Best <span>Bike For You</span></h2>
      <p>We offer the best bikes with the best deals to ensure a memorable riding experience!</p>
    </div>

    <div class="row">
      <div class="recent-tab">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="#resentnewcar" role="tab" data-toggle="tab">New Bike</a>
          </li>
        </ul>
      </div>

      <!-- Display Recent Bikes -->
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="resentnewcar">
          <?php
          $sql = "SELECT tblvehicles.VehiclesTitle, tblbrands.BrandName, tblvehicles.PricePerDay, tblvehicles.FuelType, 
                  tblvehicles.ModelYear, tblvehicles.id, tblvehicles.SeatingCapacity, tblvehicles.VehiclesOverview, 
                  tblvehicles.Vimage1 
                  FROM tblvehicles 
                  JOIN tblbrands ON tblbrands.id = tblvehicles.VehiclesBrand";
          $query = $dbh->prepare($sql);
          $query->execute();
          $results = $query->fetchAll(PDO::FETCH_OBJ);

          if ($query->rowCount() > 0) {
            foreach ($results as $result) { ?>
              <div class="col-list-3">
                <div class="recent-car-list">
                  <div class="car-info-box">
                    <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id); ?>">
                      <img src="admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1); ?>" class="img-responsive" alt="image">
                    </a>
                    <ul>
                      <li><i class="fa fa-car"></i> <?php echo htmlentities($result->FuelType); ?></li>
                      <li><i class="fa fa-calendar"></i> <?php echo htmlentities($result->ModelYear); ?> Model</li>
                      <li><i class="fa fa-user"></i> <?php echo htmlentities($result->SeatingCapacity); ?> seats</li>
                    </ul>
                  </div>
                  <div class="car-title-m">
                    <h6>
                      <a href="vehical-details.php?vhid=<?php echo htmlentities($result->id); ?>">
                        <?php echo htmlentities($result->BrandName); ?>, <?php echo htmlentities($result->VehiclesTitle); ?>
                      </a>
                    </h6>
                    <span class="price">$<?php echo htmlentities($result->PricePerDay); ?> /Day</span>
                  </div>
                  <div class="inventory_info_m">
                    <p><?php echo substr(htmlentities($result->VehiclesOverview), 0, 70); ?>...</p>
                  </div>
                </div>
              </div>
            <?php }
          } else {
            echo "<p class='text-center'>No bikes available at the moment.</p>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Fun Facts Section -->
<section class="fun-facts-section">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-xs-6">
        <div class="fun-facts-m">
          <h2><i class="fa fa-calendar"></i> 40+</h2>
          <p>Years In Business</p>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="fun-facts-m">
          <h2><i class="fa fa-motorcycle"></i> 1000+</h2>
          <p>New Bikes For Sale</p>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="fun-facts-m">
          <h2><i class="fa fa-motorcycle"></i> 999+</h2>
          <p>Used Bikes For Sale</p>
        </div>
      </div>
      <div class="col-lg-3 col-xs-6">
        <div class="fun-facts-m">
          <h2><i class="fa fa-user-circle-o"></i> 850+</h2>
          <p>Satisfied Customers</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonials -->
<section class="section-padding testimonial-section">
  <div class="container">
    <div class="section-header white-text text-center">
      <h2>Our Satisfied <span>Customer's Review</span></h2>
    </div>
    <div class="row">
      <div id="testimonial-slider">
        <?php
        $sql = "SELECT tbltestimonial.Testimonial, tblusers.FullName 
                FROM tbltestimonial 
                JOIN tblusers ON tbltestimonial.UserEmail = tblusers.EmailId 
                WHERE tbltestimonial.status = 1";
        $query = $dbh->prepare($sql);
        $query->execute();
        $testimonials = $query->fetchAll(PDO::FETCH_OBJ);

        if ($query->rowCount() > 0) {
          foreach ($testimonials as $testimonial) { ?>
            <div class="testimonial-m">
              <div class="testimonial-img">
                <img src="assets/images/cat-profile.png" alt="Customer">
              </div>
              <div class="testimonial-content">
                <h5><?php echo htmlentities($testimonial->FullName); ?></h5>
                <p><?php echo htmlentities($testimonial->Testimonial); ?></p>
              </div>
            </div>
          <?php }
        } else {
          echo "<p class='text-center'>No testimonials available.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>
</body>
</html>
