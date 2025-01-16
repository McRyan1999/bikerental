<?php
require_once("includes/config.php");

// Check if email ID is provided
if (!empty($_POST["emailid"])) {
    $email = trim($_POST["emailid"]);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "status" => "error",
            "message" => "You did not enter a valid email."
        ]);
        exit;
    }

    // Use prepared statements to prevent SQL injection
    try {
        $sql = "SELECT EmailId FROM tblusers WHERE EmailId = :email";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo json_encode([
                "status" => "exists",
                "message" => "Email already exists.",
                "disableSubmit" => true
            ]);
        } else {
            echo json_encode([
                "status" => "available",
                "message" => "Email available for registration.",
                "disableSubmit" => false
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
}
?>
