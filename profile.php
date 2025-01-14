<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Redirect if not logged in
if (strlen($_SESSION['login']) == 0) {
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

    // Update profile query
    $sql = "UPDATE tblusers 
            SET FullName=:name, ContactNo=:mobileno, dob=:dob, Address=:address, City=:city, Country=:country 
            WHERE EmailId=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':mobileno', $mobileno, PDO::PARAM_STR);
    $query->bindParam(':dob', $dob, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->bindParam(':city', $city, PDO::PARAM_STR);
    $query->bindParam(':country', $country, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);

    try {
        $query->execute();
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
    <title>Bike Rental Portal | My Profile</title>
    <!-- Stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <style>
        .errorWrap, .succWrap {
            padding: 10px;
            margin-bottom: 20px;
            background: #fff;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
        }
        .errorWrap {
            border-left: 4px solid #dd3d36;
        }
        .succWrap {
            border-left: 4px solid #5cb85c;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<!-- Page Header -->
<section class="page-header profile_page">
    <div class="container">
        <h1>Your Profile</h1>
    </div>
</section>

<!-- Profile Section -->
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
        <div class="profile_wrap">
            <h5>General Settings</h5>
            <?php if ($msg) { ?>
                <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div>
            <?php } elseif ($error) { ?>
                <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div>
            <?php } ?>

            <form method="post">
                <div class="form-group">
                    <label>Registration Date:</label>
                    <p><?php echo htmlentities($result->RegDate); ?></p>
                </div>
                <?php if ($result->UpdationDate) { ?>
                    <div class="form-group">
                        <label>Last Updated:</label>
                        <p><?php echo htmlentities($result->UpdationDate); ?></p>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" name="fullname" value="<?php echo htmlentities($result->FullName); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" class="form-control" value="<?php echo htmlentities($result->EmailId); ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="mobilenumber" value="<?php echo htmlentities($result->ContactNo); ?>" required>
                </div>
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" class="form-control" name="dob" value="<?php echo htmlentities($result->dob); ?>">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" name="address" rows="4"><?php echo htmlentities($result->Address); ?></textarea>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" class="form-control" name="city" value="<?php echo htmlentities($result->City); ?>">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" class="form-control" name="country" value="<?php echo htmlentities($result->Country); ?>">
                </div>
                <div class="form-group">
                    <button type="submit" name="updateprofile" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</section>
<?php
    }
}
?>

<?php include('includes/footer.php'); ?>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/interface.js"></script>
</body>
</html>
<?php
}
?>
