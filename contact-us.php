<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Handle form submission
if (isset($_POST['send'])) {
    $name = htmlspecialchars(trim($_POST['fullname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $contactno = htmlspecialchars(trim($_POST['contactno']));
    $message = htmlspecialchars(trim($_POST['message']));

    $sql = "INSERT INTO tblcontactusquery (name, EmailId, ContactNumber, Message) VALUES (:name, :email, :contactno, :message)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':contactno', $contactno, PDO::PARAM_STR);
    $query->bindParam(':message', $message, PDO::PARAM_STR);

    if ($query->execute()) {
        $msg = "Query sent successfully. We will contact you shortly.";
    } else {
        $error = "Something went wrong. Please try again.";
    }
}
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us | BikeForYou</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- FontAwesome -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <!-- Custom Style -->
    <style>
        .errorWrap {
            padding: 10px;
            margin: 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
        .succWrap {
            padding: 10px;
            margin: 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
        }
    </style>
</head>
<body>

<!-- Header -->
<?php include('includes/header.php'); ?>

<!-- Page Header -->
<section class="page-header contactus_page">
    <div class="container">
        <div class="page-header_wrap">
            <div class="page-heading">
                <h1>Contact Us</h1>
            </div>
            <ul class="coustom-breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li>Contact Us</li>
            </ul>
        </div>
    </div>
</section>

<!-- Contact Us Form -->
<section class="contact_us section-padding">
    <div class="container">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-md-6">
                <h3>Get in touch using the form below</h3>
                <?php if ($error): ?>
                    <div class="errorWrap"><strong>ERROR</strong>: <?php echo htmlentities($error); ?></div>
                <?php elseif ($msg): ?>
                    <div class="succWrap"><strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?></div>
                <?php endif; ?>
                <div class="contact_form gray-bg">
                    <form method="post">
                        <div class="form-group">
                            <label for="fullname">Full Name <span>*</span></label>
                            <input type="text" name="fullname" class="form-control white_bg" id="fullname" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address <span>*</span></label>
                            <input type="email" name="email" class="form-control white_bg" id="email" required>
                        </div>
                        <div class="form-group">
                            <label for="contactno">Phone Number <span>*</span></label>
                            <input type="text" name="contactno" class="form-control white_bg" id="contactno" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message <span>*</span></label>
                            <textarea class="form-control white_bg" name="message" id="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" name="send" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-md-6">
                <h3>Contact Info</h3>
                <div class="contact_detail">
                    <?php
                    $sql = "SELECT Address, EmailId, ContactNo FROM tblcontactusinfo";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) { ?>
                            <ul>
                                <li><i class="fa fa-map-marker"></i> <?php echo htmlentities($result->Address); ?></li>
                                <li><i class="fa fa-envelope"></i> <a href="mailto:<?php echo htmlentities($result->EmailId); ?>"><?php echo htmlentities($result->EmailId); ?></a></li>
                                <li><i class="fa fa-phone"></i> <?php echo htmlentities($result->ContactNo); ?></li>
                            </ul>
                        <?php }
                    } else {
                        echo "<p>No contact information available.</p>";
                    }
                    ?>
                </div>
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
