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

// Automate historical data request date and time
$currentDate = date('Y-m-d');
$fromDateTime = $currentDate . ' 09:15';
$toDateTime = date('Y-m-d H:i', strtotime('270 minute'));

$urlHistorical = 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/historical/v1/getCandleData';

$dataHistorical = json_encode([
    'exchange' => 'NSE',
    'symboltoken' => '3045',
    'interval' => 'FIVE_MINUTE',
    'fromdate' => '2023-11-17 09:15',
    'todate' => '2023-11-17 15:30'
]);

$headersHistorical = [
    'Authorization: Bearer ' . $jwtToken,
    'Content-Type: application/json',
    'Accept: application/json',
    'X-UserType: USER',
    'X-SourceID: WEB',
    'X-ClientLocalIP: CLIENT_LOCAL_IP',
    'X-ClientPublicIP: CLIENT_PUBLIC_IP',
    'X-MACAddress: MAC_ADDRESS',
    'X-PrivateKey: ' . $apiKey
];

$chHistorical = curl_init();

curl_setopt($chHistorical, CURLOPT_URL, $urlHistorical);
curl_setopt($chHistorical, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($chHistorical, CURLOPT_POST, 1);
curl_setopt($chHistorical, CURLOPT_POSTFIELDS, $dataHistorical);
curl_setopt($chHistorical, CURLOPT_HTTPHEADER, $headersHistorical);

$responseHistorical = curl_exec($chHistorical);

if (curl_errno($chHistorical)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Error fetching historical data']);
    exit;
}

curl_close($chHistorical);
// Decode the historical data response
$historicalData = json_decode($responseHistorical, true);

// Transform the data for amCharts
$amChartsData = [];
foreach ($historicalData['data'] as $item) {
    $timestamp = strtotime($item[0]) * 1000;
    $amChartsData[] = [
        'Date'   => $timestamp,
        'Open'   => $item[1],
        'High'   => $item[2],
        'Low'    => $item[3],
        'Close'  => $item[4],
        'Volume' => $item[5],
    ];
}

include 'getChartData.php';
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
        <link rel="stylesheet" href="css/trade.css">
        <!-- Resources -->
        <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/stock.js"></script>
        <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<!-- Styles -->
<style>
    #chartcontrols {
        height: auto;
        padding: 5px 5px 0 16px;
        max-width: 100%;
    }

    #chartdiv {
        width: 100%;
        /* height: 600px; */
        max-width: 100%;
    }

    .chartContainer {
        height: 400px;
        width: 800px;
        margin: 1rem auto 4rem;
    }
</style>
    </head>
    <body>
    <?php include 'sidebar.php'; ?>
        <div class="content">
        <!-- HTML -->
        <div class="chartContainer">
                <div id="chartcontrols"></div>
                <div id="chartdiv"></div>
            </div>
        </div>
        
    </body>
</html>

