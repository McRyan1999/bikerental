<?php
session_start();

error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit;
}

if (isset($_POST['update'])) {
    $password = md5($_POST['password']);
    $newpassword = md5($_POST['newpassword']);
    $email = $_SESSION['login'];

    try {
        $sql = "SELECT Password FROM tblusers WHERE EmailId = :email AND Password = :password";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':password', $password, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            $updateSql = "UPDATE tblusers SET Password = :newpassword WHERE EmailId = :email";
            $updateQuery = $dbh->prepare($updateSql);
            $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
            $updateQuery->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $updateQuery->execute();

            $msg = "Your password has been successfully updated.";
        } else {
            $error = "Your current password is incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Something went wrong. Please try again later.";
    }
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <script>
        function validatePassword() {
            const newPassword = document.getElementById('newpassword').value;
            const confirmPassword = document.getElementById('confirmpassword').value;

            if (newPassword !== confirmPassword) {
                alert("New Password and Confirm Password fields do not match!");
                return false;
            }
            return true;
        }
    </script>
    <style>
        .errorWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #dd3d36;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        }
        .succWrap {
            padding: 10px;
            margin: 0 0 20px 0;
            background: #fff;
            border-left: 4px solid #5cb85c;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<section class="page-header profile_page">
    <div class="container">
        <div class="page-header_wrap">
            <div class="page-heading">
                <h1>Update Password</h1>
            </div>
            <ul class="coustom-breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li>Update Password</li>
            </ul>
        </div>
    </div>
</section>

<section class="user_profile inner_pages">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="profile_wrap">
                    <h6>Update Password</h6>
                    <?php if (isset($error)) { ?>
                        <div class="errorWrap"> <strong>ERROR</strong>: <?php echo htmlentities($error); ?> </div>
                    <?php } else if (isset($msg)) { ?>
                        <div class="succWrap"> <strong>SUCCESS</strong>: <?php echo htmlentities($msg); ?> </div>
                    <?php } ?>
                    <form name="chngpwd" method="post" onsubmit="return validatePassword();">
                        <div class="form-group">
                            <label for="password">Current Password</label>
                            <input type="password" class="form-control" name="password" id="password" required>
                        </div>
                        <div class="form-group">
                            <label for="newpassword">New Password</label>
                            <input type="password" class="form-control" name="newpassword" id="newpassword" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmpassword">Confirm Password</label>
                            <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" required>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
