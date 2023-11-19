<?php
session_start();
include "dbconfig.php";

// Check if the user is logged in
if (!isset($_SESSION['angel_login']) || $_SESSION['angel_login'] !== true) {
    include 'sidebar.php';
    echo '<div class="content"><h1>Login To Access</h1></div>';
    exit; // Stop executing the rest of the code
} else {
    $clientCode = $_SESSION['clientcode'];
    $sql = "SELECT * FROM angel_login WHERE clientcode = '$clientCode'";
    // Execute the query
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $jwtToken = $row['jwttoken'];
    $apiKey = $row['apikey'];
    $feedToken = $row['feedtoken'];
    $_SESSION['jwtToken'] = $jwtToken;
    $_SESSION['apiKey'] = $apiKey;
    $_SESSION['feedToken'] = $feedToken;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.socket.io/4.1.2/socket.io.min.js"></script>
  <title>WebSocket Client</title>
</head>
<body>
<script>
        // Replace these values with your actual credentials
        const jwtToken = '<?php echo $jwtToken?>';
        const apiKey = '<?php echo $apiKey?>';
        const clientCode = '<?php echo $clientCode?>';
        const feedToken = '<?php echo $feedToken?>';

        // Connect to the Socket.IO server
        const socket = io('https://smartapisocket.angelone.in', {
            auth: {
                token: jwtToken,
                apiKey: apiKey,
                clientCode: clientCode,
                feedToken: feedToken
            }
        });

        // Handle events
        socket.on('connect', () => {
            console.log('Connected to SmartAPI WebSocket');

            // Send a subscription request
            const subscriptionRequest = {
                action: 1,
                params: {
                    mode: 1, // Replace with the desired mode (1 for LTP, 2 for Quote, 3 for Snap Quote)
                    tokenList: [
                        {
                            exchangeType: 1, // Replace with the desired exchange type
                            tokens: ['10626', '5290'], // Replace with the actual tokens you want to subscribe to
                        },
                        {
                            exchangeType: 5, // Replace with the desired exchange type
                            tokens: ['234230', '234235', '234219'], // Replace with the actual tokens you want to subscribe to
                        },
                    ],
                },
            };

            socket.emit('subscribe', subscriptionRequest);
        });

        socket.on('message', (data) => {
            // Handle the received data
            console.log('Received data:', data);
        });

        socket.on('disconnect', () => {
            console.log('Disconnected from SmartAPI WebSocket');
        });

        // Send heartbeat message every 30 seconds to keep the connection alive
        setInterval(() => {
            if (socket.connected) {
                socket.send('ping');
            }
        }, 30000);
    </script>

</body>
</html>
</body>
</html>
