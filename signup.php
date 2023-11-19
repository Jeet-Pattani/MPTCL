<?php
session_start(); 
?>
<?php
//session_start(); // Start the session

require_once 'dbconfig.php';

if (isset($_POST['submit'])) {
    $name = isset($_POST['register_name']) ? $_POST['register_name'] : '';
    $email = isset($_POST['register_email']) ? $_POST['register_email'] : '';
    $mobno = isset($_POST['register_mobno']) ? $_POST['register_mobno'] : '';
    $password = isset($_POST['register_password']) ? $_POST['register_password'] : '';
    $clientCode = isset($_POST['client_code']) ? $_POST['client_code'] : '';
    $apiKey = isset($_POST['api_key']) ? $_POST['api_key'] : '';

    if (empty($name) || empty($email) || empty($mobno) || empty($password)) {
        $_SESSION['registration_error'] = "Please fill in all required fields.";
        header('Location: signup.php'); // Stay on the registration page
        exit();
    } else {
        // Check if the email is already registered
        $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
        $checkStmt = $conn->prepare($checkEmailQuery);

        if ($checkStmt) {
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                $_SESSION['registration_error'] = "This email is already registered. Please use a different email.";
                header('Location: signup.php'); // Stay on the registration page
                exit();
            } else {
                // Hash the password for security
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // SQL query to insert user data into the database
                $insertQuery = "INSERT INTO users (name, email, mobno, password, clientcode, apikey) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);

                if ($stmt) {
                    $stmt->bind_param("ssssss", $name, $email, $mobno, $hashedPassword, $clientCode, $apiKey);

                    if ($stmt->execute()) {
                        // Registration successful
                        $_SESSION['registration_success'] = "Registration successful. Please log in.";
                        header('Location: login.php'); // Redirect to the login page
                        exit();
                    } else {
                        // Registration failed
                        $_SESSION['registration_error'] = "Registration failed: " . $stmt->error;
                        header('Location: signup.php'); // Stay on the registration page
                        exit();
                    }

                    $stmt->close();
                } else {
                    // Error in preparing the statement
                    $_SESSION['registration_error'] = "Error in database query: " . $conn->error;
                    header('Location: signup.php'); // Stay on the registration page
                    exit();
                }
            }
        } else {
            $_SESSION['registration_error'] = "Error in database query.";
            header('Location: signup.php'); // Stay on the registration page
            exit();
        }

        $checkStmt->close();
    }
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="content" id="dynamic-content">
    <div class="form-container" id="register-form">
        <h2>Register</h2>
        <?php
        if (isset($_SESSION['registration_error'])) {
            echo '<div class="error-message">' . $_SESSION['registration_error'] . '</div>';
            unset($_SESSION['registration_error']);
        }
        ?>
        <form method="post" action="signup.php">
            <div class="form-group">
                <label for="user-name">Name</label>
                <input id="user-name" type="text" name="register_name" required>
            </div>
            <div class="form-group">
                <label for="user-email">Email</label>
                <input id="user-email" type="email" name="register_email" required>
            </div>
            <div class="form-group">
                <label for="user-mobno">Phone Number</label>
                <input id="user-mobno" type="number" name="register_mobno" inputmode="numeric" required>
            </div>
            <div class="form-group">
                <label for="client_code">Client Code</label>
                <input id="client_code" type="text" name="client_code" required>
            </div>
            <div class="form-group">
                <label for="api_key">API Key</label>
                <input id="api_key" type="text" name="api_key" required>
            </div>
            <div class="form-group">
                <label for="user-password">Password</label>
                <input id="user-password" type="password" name="register_password" required>
                <div class="showpass">
                    <input type="checkbox" id="show-password"> <label for="show-password">Show Password</label>
                </div>    
            </div>
            <style>
                .form-group .showpass{
    display: flex;
    margin-top: 0.3rem;
}
.form-group .showpass label{
    margin-bottom:0;
    margin-left: 0.3rem;
}
            </style>
            <div class="form-actions">
            <button type="submit" name="submit">Register</button>
            </div>
            <div class="switch-form">
                Already have an account? <a href="login" id="show-login-form">Login here</a>
            </div>
        </form>
    </div>
</div>
<script>
    const passwordInput = document.getElementById('user-password');
    const showPasswordCheckbox = document.getElementById('show-password');

    showPasswordCheckbox.addEventListener('change', function() {
        if (this.checked) {
            passwordInput.type = 'text'; // Change input type to text to show the password
        } else {
            passwordInput.type = 'password'; // Change input type back to password to hide the password
        }
    });
</script>
</body>
</html>

