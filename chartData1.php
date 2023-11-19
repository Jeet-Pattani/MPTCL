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

$dataHistorical = json_encode([
    'exchange' => 'NSE',
    'symboltoken' => '3045',
    'interval' => 'ONE_MINUTE',
    'fromdate' => '2023-11-16 09:15',
    'todate' => '2023-11-16 11:12'
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

curl_setopt($chHistorical, CURLOPT_URL, 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/historical/v1/getCandleData');
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
$dataHistorical = json_decode($responseHistorical, true);

// Convert historical data to the desired format
$chartData = [];
foreach ($dataHistorical['data'] as $candle) {
    $chartData[] = [
        'time' => strtotime($candle[0]) * 1000, // Convert to milliseconds
        'open' => $candle[1],
        'high' => $candle[2],
        'low' => $candle[3],
        'close' => $candle[4],
    ];
}

// Now, store historical data in $fetchedData variable for later use
$fetchedData = $chartData;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradingView Lightweight Chart</title>
    <!-- Include the TradingView Lightweight Charts library -->
    <script type="text/javascript" src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
</head>
<body>

<div style="width: 100%; height: 500px;" id="chart"></div>

<script>
    // Assuming you have the JSON data available in the variable 'chartData'
    var chartData = <?php echo json_encode($fetchedData); ?>;

    // Create a new TradingView chart
    var chart = LightweightCharts.createChart(document.getElementById('chart'), {
        width: 1000,
        height: 500,
        timeScale: {
            timeVisible: true,
            secondsVisible: false,
        },
    });

    // Add a candlestick series to the chart
    var candlestickSeries = chart.addCandlestickSeries();

    // Load the chart data
    candlestickSeries.setData(chartData);
</script>

</body>
</html>

<?php

$dataLive = json_encode([
    "mode" => "FULL",
    "exchangeTokens" => [
        "NSE" => "3045"
    ]
]);

// URL for the API
$urlLive = 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/market/v1/quote/';

// Set the request headers for live market data
$headersLive = [
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

// Initialize cURL session for live market data
$chLive = curl_init();

// Set cURL options for live market data
curl_setopt($chLive, CURLOPT_URL, $urlLive);
curl_setopt($chLive, CURLOPT_RETURNTRANSFER, true);
curl_setopt($chLive, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($chLive, CURLOPT_POSTFIELDS, $dataLive);
curl_setopt($chLive, CURLOPT_HTTPHEADER, $headersLive);

// Execute the cURL request for live market data
$responseLive = curl_exec($chLive);

// Check for cURL errors for live market data
if (curl_errno($chLive)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Error fetching live market data']);
    exit;
}

// Echo the response for live market data
echo $responseLive;

// Close the cURL session for live market data
curl_close($chLive);
?>
