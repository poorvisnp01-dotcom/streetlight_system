reset


<?php

session_start();

include "../config/database.php";

// User must come through forgot-password.php
if (!isset($_SESSION["reset_email"]) || empty($_SESSION["reset_email"])) {

    header("Location: forgot-password.php");
    exit();

}

$message = "";
$messageType = "";

$email = $_SESSION["reset_email"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    // Check empty fields
    if (empty($password) || empty($confirm)) {

        $message = "Please fill in all fields.";
        $messageType = "error";

    }

    // Check password match
    elseif ($password !== $confirm) {

        $message = "Passwords do not match.";
        $messageType = "error";

    }

    // Minimum password length
    elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters.";
        $messageType = "error";

    }

    else {

        try {

            $users = $db->users;

            // Hash the new password
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // Update password
            $result = $users->updateOne(

                [
                    "email" => $email
                ],

                [
                    '$set' => [
                        "password" => $hashedPassword
                    ]
                ]

            );

            // Check whether update happened
            if ($result->getMatchedCount() > 0) {

                // Remove reset session
                unset($_SESSION["reset_email"]);

                // Redirect to login
                header("Location: login.php?reset=success");
                exit();

            } else {

                $message = "Unable to update password.";
                $messageType = "error";

            }

        } catch (Exception $e) {

            $message = "Something went wrong. Please try again.";
            $messageType = "error";

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="form-container">

    <div class="form-box">

        <h2>Reset Password</h2>

        <?php if (!empty($message)) { ?>

            <p style="color:red;">
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php } ?>

        <form method="POST" action="reset-password.php">

            <input
                type="password"
                name="password"
                placeholder="New Password"
                required
                minlength="6"
            >

            <input
                type="password"
                name="confirm"
                placeholder="Confirm Password"
                required
                minlength="6"
            >

            <button type="submit">
                Reset Password
            </button>

        </form>

        <p>

            <a href="login.php">
                Back to Login
            </a>

        </p>

    </div>

</div>

</body>

</html>