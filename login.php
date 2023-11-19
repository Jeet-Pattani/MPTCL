<?php
session_start(); 

// if($_SESSION['loggedin']==true || (isset($_SESSION['loggedin']))){
//     header("Location: trade");
// } else{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Database connection
        require_once 'dbconfig.php';
    
        // Get user input
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
    
        // Validate user input
        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = "Please fill in both email and password fields.";
        } else {
            // Check if the user exists in the database
            $sql = "SELECT id, email, password FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
    
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();
    
                if ($stmt->num_rows === 1) {
                    $stmt->bind_result($id, $dbEmail, $dbPassword);
                    $stmt->fetch();
    
                    // Verify the password
                    if (password_verify($password, $dbPassword)) {
                        // Password is correct, create a session and redirect
                        $_SESSION['loggedin'] = true;
                        $_SESSION['angel_login'] = false;
                        $_SESSION['user_id'] = $id;
                        if($email=='admin@mptcl'){
                            header('Location: addnews.php');
                        }else{
                            header('Location: angel_one_login');
                        }
                         // Redirect to the dashboard or another page
                        exit();
                    } else {
                        $_SESSION['login_error'] = "Incorrect password. Please try again.";
                    }
                } else {
                    $_SESSION['login_error'] = "User with this email does not exist.";
                }
    
                $stmt->close();
            } else {
                $_SESSION['login_error'] = "Error in database query.";
            }
    
            $conn->close();
        }
    }
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
  <div class="content" id="dynamic-content" style="height:100vh;">
  <div class="form-container" id="login-form">
        <h2>Login</h2>
        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<div class="error-message">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']);
        }
        ?>
        <form method="post">
            <div class="form-group">
                <label for="user-email">Email</label>
                <input id="user-email" type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="user-password">Password</label>
                <input id="user-password" type="password" name="password" required>
                <div class="showpass">
                    <input type="checkbox" id="show-password"> <label for="show-password">Show Password</label>
                </div>    
            </div>

            <div class="form-actions">
                <button type="submit">Login</button>
            </div>
            <div class="switch-form">
                Don't have an account? <a href="signup" id="show-register-form">Register here</a>
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
