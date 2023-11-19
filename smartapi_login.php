<?php
session_start();
include "dbconfig.php";

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user inputs
    $userId = $_SESSION['user_id'];
    $userEmail = $_POST['userEmail'];
    $clientCode = $_POST['clientCode'];
    $apiKey = $_POST['apiKey'];
    $password = $_POST['password'];
    $totp = $_POST['totp'];

    // Define the SmartAPI login URL
    $smartApiLoginUrl = 'https://apiconnect.angelbroking.com/rest/auth/angelbroking/user/v1/loginByPassword';

    // Prepare the data to be sent
    $data = array(
        'clientcode' => $clientCode,
        'password' => $password,
        'totp' => $totp
    );

    // Set cURL options
    $ch = curl_init($smartApiLoginUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Set cURL headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json',
        'X-UserType: USER',
        'X-SourceID: WEB',
        'X-ClientLocalIP: CLIENT_LOCAL_IP', // Replace with actual IP
        'X-ClientPublicIP: CLIENT_PUBLIC_IP', // Replace with actual IP
        'X-MACAddress: MAC_ADDRESS', // Replace with actual MAC address
        'X-PrivateKey: ' . $apiKey
    ));

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        echo "cURL error: " . curl_error($ch);
    } else {
        // Parse the JSON response
        $responseData = json_decode($response, true);

        if (isset($responseData['status']) && $responseData['status'] === true) {
            // Login successful
            echo "Login Successful...!";
            echo "Auth Tokens Received";

            $jwtToken = $responseData['data']['jwtToken'];
            $refreshToken = $responseData['data']['refreshToken'];
            $feedToken = $responseData['data']['feedToken'];

            // Store tokens or process them as needed
            // You can also redirect the user to another page with the tokens

            // Insert or update the login details into the angel_login table
            $upsertQuery = "INSERT INTO angel_login (id, email, clientCode, apiKey, jwtToken, refreshToken, feedToken)
                            VALUES (?, ?, ?, ?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE
                            email = VALUES(email),
                            apiKey = VALUES(apiKey),
                            jwtToken = VALUES(jwtToken),
                            refreshToken = VALUES(refreshToken),
                            feedToken = VALUES(feedToken)";
            $stmt = $conn->prepare($upsertQuery);
            $stmt->bind_param("issssss", $userId, $userEmail, $clientCode, $apiKey, $jwtToken, $refreshToken, $feedToken);

            if ($stmt->execute()) {
                // Insertion or update was successful
                echo "Login details inserted/updated in angel_login table.";

                $_SESSION['angel_login'] = true;
                header("Location: dashboard");
            } else {
                // Insertion or update failed
                echo "Failed to insert/update login details in angel_login table.";
            }
        } else {
            // Login failed
            echo "Login Failed: " . $responseData['message'];
        }
    }

    // Close the cURL session
    curl_close($ch);
} else {
    // Redirect to a login page or show an error message for non-POST requests
    header("Location: trade");
}
?>
