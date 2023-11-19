<?php
session_start();
include "dbconfig.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <style>
        body {
            background-color: #f0f0f0;
            color: #000;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            background-color: #333;
            color: #fff;
            padding: 15px;
            width: 250px;
            position: fixed;
            height: 100%;
        }

        .content {
            margin-left: 280px;
            padding: 20px;
        }

        h1 {
            color: #4CAF50; /* Green color */
        }

        h3 {
            color: #FF5722; /* Dark orange color */
        }

        form {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            color: #333;
            margin-right: 5px;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="password"],
        input[type="text"] {
            color: #333;
        }

        input[type="submit"] {
            background-color: #4CAF50; /* Green button color */
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>
<?php
include 'sidebar.php';

$userId = $_SESSION['user_id'];
?>
<div class="content" id="dynamic-content">
    <h1>Welcome to MPTCL</h1>
    <h3>Enter Login Details for AngelONE</h3>
    <form method="POST" action="smartapi_login.php">
        <?php
        $query = "SELECT * FROM users WHERE id = $userId"; // Replace 'users' with your table name

        $result = $conn->query($query);

        if ($result) {
            $userDetails = $result->fetch_assoc();

            $clientcode = $userDetails['clientcode'];
            $_SESSION['clientcode'] = $userDetails['clientcode'];
            $apiKey = $userDetails['apikey'];
            $userEmail = $userDetails['email'];
            echo '<label for="apiKey">Client Code:</label>';
            echo '<input type="text" id="apiKey" name="clientCode" value="' . $clientcode . '" readonly><br>';
            echo '<label for="apiKey">API Key:</label>';
            echo '<input type="text" id="apiKey" name="apiKey" value="' . $apiKey . '" readonly><br>';
            echo '<label for="userEmail">Your Email:</label>';
            echo '<input type="text" id="userEmail" name="userEmail" value="' . $userEmail . '" readonly><br>';
        }
        ?>

        <label for="password">AngelONE Login Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="totp">TOTP:</label>
        <input type="text" id="totp" name="totp" required><br>

        <input type="submit" value="Login">
    </form>
</div>
</body>
</html>
