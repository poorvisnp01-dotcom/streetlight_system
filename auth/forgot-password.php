Forgot password code



<?php

session_start();

include "../config/database.php";

$message = "";

// If the user already has a reset session, clear it first
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);

    if (empty($email)) {

        $message = "Please enter your email.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } else {

        try {

            $users = $db->users;

            $user = $users->findOne([
                "email" => $email
            ]);

            if ($user) {

                // Store email in session
                $_SESSION["reset_email"] = $email;

                // Redirect to reset password page
                header("Location: reset-password.php");
                exit();

            } else {

                $message = "Email not found.";

            }

        } catch (Exception $e) {

            $message = "Something went wrong. Please try again.";

        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="form-container">

    <div class="form-box">

        <h2>Forgot Password</h2>

        <?php if (!empty($message)) { ?>

            <p style="color:red;">
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php } ?>

        <form method="POST" action="forgot-password.php">

            <input
                type="email"
                name="email"
                placeholder="Enter Registered Email"
                required
            >

            <button type="submit">
                Next
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