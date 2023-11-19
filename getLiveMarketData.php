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
    $sql = "SELECT jwttoken, apikey FROM angel_login WHERE clientcode = '$clientCode'";
    // Execute the query
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $jwtToken = $row['jwttoken'];
    $apiKey = $row['apikey'];
    $_SESSION['jwtToken'] = $jwtToken;
    $_SESSION['apiKey'] = $apiKey;
}

// Data to be sent in the request
$data = json_encode([
    "mode" => "FULL",
    "exchangeTokens" => [
        $_GET['exchange'] => [$_GET['token']]
    ]
]);
?>

<?php

// URL for the API
$url = 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/market/v1/quote/';

// Set the request headers
$headers = [
    'Authorization: Bearer ' . $jwtToken,
    'Content-Type: application/json',
    'Accept: application/json',
    'X-UserType: USER',
    'X-SourceID: WEB',
    'X-ClientLocalIP: CLIENT_LOCAL_IP',
    'X-ClientPublicIP: CLIENT_PUBLIC_IP',
    'X-MACAddress: MAC_ADDRESS',
    'X-PrivateKey: ' . $apiKey,
];

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute the cURL request
$response = curl_exec($ch);
// echo $response;

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
}

// Decode the JSON response
$data = json_decode($response, true);
$stockData = $data['data']['fetched'][0];

$trend = "Neutral";

if($stockData['ltp']>$stockData['open']){
    $trend = "Bullish";
} elseif ($stockData['ltp'] < $stockData['open']) {
    $trend = "Bearish";
}

// Close the cURL session
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image.png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <style>
        body {
            background-color: #f0f0f0;
            color: #333;
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

        .content h1 {
            /* color: #4CAF50; Green color */
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 16px;
            margin: 10px 0;
        }

        .content form {
            display: inline-block;
            margin: 10px 0;
        }

        .content button {
            background-color: #4CAF50; /* Green button color */
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .content form input[type="hidden"] {
            display: none;
        }

        .content .buy-button {
            background-color: #00bcd4; /* Cyan color for "Buy" */
        }

        .content .sell-button {
            background-color: #ff5722; /* Red color for "Sell" */
        }

        .stock-info {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }

        .stock-data {
            font-size: 16px;
            margin: 10px 0;
        }

        .table-container {
            margin-top: 20px;
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
        /* Green for Bullish trend */
.bullish {
    color: green;
}

/* Red for Bearish trend */
.bearish {
    color: #ff5722;
}

/* Dark gray for Neutral trend */
.neutral {
    color: darkgray;
}

    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="content">
    <h1 class="<?php echo strtolower($trend);?>">It seems <?php echo $trend;?></h1>

    <?php
    if (isset($_GET['token']) && isset($_GET['name']) && isset($_GET['symbol']) && isset($_GET['exchange'])) {
        $token = $_GET['token'];
        $name = $_GET['name'];
        $symbol = $_GET['symbol'];
        $exchange = $_GET['exchange'];

        if ($data['status']) {
            echo "<h2 class='stock-info'>Stock Information</h2>";
            echo "<table class='stock-data'>"; // Start a table
            echo "<tr><td><strong>Token:</strong></td><td>$token</td></tr>";
            echo "<tr><td><strong>Name:</strong></td><td>$name</td></tr>";
            echo "<tr><td><strong>Symbol:</strong></td><td>$symbol</td></tr>";
            echo "<tr><td><strong>Exchange:</strong></td><td>$exchange</td></tr>";
            echo "</table>"; // End the table

            echo "<h2 class='stock-info'>Stock Price Data</h2>";
            echo "<table class='stock-data'>"; // Start a table
            
            echo "<tr>";
                echo "<th>LTP</th>";
                echo "<th>Open</th>";
                echo "<th>High</th>";
                echo "<th>Low</th>";
                echo "<th>Close</th>";
                echo "<th>Volume</th>";
                echo "<th>Percent Change</th>";
                echo "<th>Change</th>";
            echo "</tr>";
            echo "<tr>";
                echo "<td>{$stockData['ltp']}</td>";
                echo "<td>{$stockData['open']}</td>";
                echo "<td>{$stockData['high']}</td>";
                echo "<td>{$stockData['low']}</td>";
                echo "<td>{$stockData['close']}</td>";
                $tradeVolume = number_format($stockData['tradeVolume']);
                echo "<td>{$tradeVolume}</td>";
                echo "<td>{$stockData['percentChange']}</td>";
                echo "<td>{$stockData['netChange']}</td>";
            echo "</tr>";
            echo "</table>";
            echo "<table>";
            echo "<tr>";
                echo "<th>Last Trade Quantity</th>";
                echo "<th>Average Price</th>";
                echo "<th>Upper Circuit</th>";
                echo "<th>Lower Circuit</th>";
                echo "<th>52-Week Low</th>";
                echo "<th>52-Week High</th>";
                echo "<th>Exchange Feed Time</th>";
                echo "<th>Exchange Trade Time</th>";
            echo "</tr>";
            echo "<tr>";
                echo "<td>{$stockData['lastTradeQty']}</td>";
                echo "<td>{$stockData['avgPrice']}</td>";
                echo "<td>{$stockData['upperCircuit']}</td>";
                echo "<td>{$stockData['lowerCircuit']}</td>";
                echo "<td>{$stockData['52WeekLow']}</td>";
                echo "<td>{$stockData['52WeekHigh']}</td>";
                echo "<td>{$stockData['exchFeedTime']}</td>";
                echo "<td>{$stockData['exchTradeTime']}</td>";
            echo "</tr>";
            
            echo "</table>"; // End the table

            // Depth table
            $depthData = $stockData['depth'];
            echo "<div class='table-container'>";
            echo "<h2 class='stock-info'>Depth</h2>";
            echo "<table>";
            echo "<tr><th>Buy Price</th><th>Buy Quantity</th><th>Buy Orders</th><th>Sell Price</th><th>Sell Quantity</th><th>Sell Orders</th></tr>";
            foreach ($depthData['buy'] as $buy) {
                echo "<tr><td>{$buy['price']}</td><td>{$buy['quantity']}</td><td>{$buy['orders']}</td><td></td><td></td><td></td></tr>";
            }
            foreach ($depthData['sell'] as $sell) {
                echo "<tr><td></td><td></td><td></td><td>{$sell['price']}</td><td>{$sell['quantity']}</td><td>{$sell['orders']}</td></tr>";
            }
            echo "</table>";
            echo "</div>";

            // Add the Buy and Sell buttons within a form
            echo "<form action='buy_sell_handler.php' method='post'>";
            echo "<input type='hidden' name='token' value='$token'>";
            echo "<input type='hidden' name='name' value='$name'>";
            echo "<input type='hidden' name='symbol' value='$symbol'>";
            echo "<input type='hidden' name='exchange' value='$exchange'>";
            echo "<button class='buy-button' type='submit' name='action' value='buy'>Buy</button>";
            echo "<button class='sell-button' type='submit' name='action' value='sell'>Sell</button>";
            echo "</form>";
        } else {
            echo "<p class='stock-info' style='color: red;'>Error: {$data['message']}</p>";
        }
    } else {
        echo "<p class='stock-info' style='color: red;'>Invalid or incomplete URL parameters.</p>";
    }
    ?>
</div>
</body>
</html>

