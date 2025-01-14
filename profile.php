<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Check session and validate email
if (!isset($_SESSION['login']) || !filter_var($_SESSION['login'], FILTER_VALIDATE_EMAIL)) {
    header('location:index.php');
    exit();
}

if (isset($_POST['updateprofile'])) {
    $name = htmlspecialchars($_POST['fullname'], ENT_QUOTES, 'UTF-8');
    $mobileno = filter_var($_POST['mobilenumber'], FILTER_SANITIZE_NUMBER_INT);
    $dob = htmlspecialchars($_POST['dob'], ENT_QUOTES, 'UTF-8');
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
    $city = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8');
    $country = htmlspecialchars($_POST['country'], ENT_QUOTES, 'UTF-8');
    $email = $_SESSION['login'];

    $sql = "UPDATE tblusers SET FullName=:name, ContactNo=:mobileno, dob=:dob, Address=:address, City=:city, Country=:country WHERE EmailId=:email";
    $query = $dbh->prepare($sql);

    try {
        $query->execute([
            ':name' => $name,
            ':mobileno' => $mobileno,
            ':dob' => $dob,
            ':address' => $address,
            ':city' => $city,
            ':country' => $country,
            ':email' => $email,
        ]);
        $msg = "Profile Updated Successfully";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bike Rental Portal | My Profile</title>
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        }
    </style>
</head>
<body>
<!-- Header -->
<?php include('includes/header.php'); ?>
<!-- /Header -->

<!-- Page Header -->
<section class="page-header profile_page">
    <div class="container">
        <div class="page-header_wrap">
            <div class="page-heading">
                <h1>Your Profile</h1>
            </div>
            <ul class="coustom-breadcrumb">
                <li><a href="#">Home</a></li>
                <li>Profile</li>
            </ul>
        </div>
    </div>
</section>
<!-- /Page Header -->

<?php
$useremail = $_SESSION['login'];
$sql = "SELECT * FROM tblusers WHERE EmailId=:useremail";
$query = $dbh->prepare($sql);
$query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
$query->execute();
$results = $query->fetchAll(PDO::FETCH_OBJ);

if ($query->rowCount() > 0) {
    foreach ($results as $result) {
?>
<section class="user_profile inner_pages">
    <div class="container">
        <div class="user_profile_info gray-bg padding_4x4_40">
            <div class="upload_user_logo"><img src="assets/images/dealer-logo.jpg" alt="image"></div>
            <div class="dealer_info">
                <h5><?php echo htmlentities($result->FullName); ?></h5>
                <p><?php echo htmlentities($result->Address); ?><br>
                    <?php echo htmlentities($result->City); ?>&nbsp;<?php echo htmlentities($result->Country); ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <?php include('includes/sidebar.php'); ?>
            </div>
            <div class="col-md-6 col-sm-8">
                <div class="profile_wrap">
                    <h5 class="uppercase underline">General Settings</h5>
                    <?php if ($msg) { ?>
                        <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?></div>
                    <?php } elseif ($error) { ?>
                        <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?></div>
                    <?php } ?>
                    <form method="post">
                        <div class="form-group">
                            <label>Reg Date -</label>
                            <?php echo htmlentities($result->RegDate); ?>
                        </div>
                        <?php if ($result->UpdationDate) { ?>
                            <div class="form-group">
                                <label>Last Update at -</label>
                                <?php echo htmlentities($result->UpdationDate); ?>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input class="form-control white_bg" name="fullname" value="<?php echo htmlentities($result->FullName); ?>" type="text" required>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="form-control white_bg" value="<?php echo htmlentities($result->EmailId); ?>" type="email" readonly>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input class="form-control white_bg" name="mobilenumber" value="<?php echo htmlentities($result->ContactNo); ?>" type="text" required>
                        </div>
                        <div class="form-group">
                            <label>Date of Birth (dd/mm/yyyy)</label>
                            <input class="form-control white_bg" name="dob" value="<?php echo htmlentities($result->dob); ?>" type="date">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control white_bg" name="address" rows="4"><?php echo htmlentities($result->Address); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input class="form-control white_bg" name="country" value="<?php echo htmlentities($result->Country); ?>" type="text">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input class="form-control white_bg" name="city" value="<?php echo htmlentities($result->City); ?>" type="text">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="updateprofile" class="btn">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } } ?>

<!-- Footer -->
<?php include('includes/footer.php'); ?>
<!-- /Footer -->

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>
</body>
</html>
