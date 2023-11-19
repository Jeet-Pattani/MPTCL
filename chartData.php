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

// Continue with the live market data request (unchanged from the previous code)
/* 
$dataLive = json_encode([
    "mode" => "FULL",
    "exchangeTokens" => [
        "NSE" => ["3045"]
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

// Close the cURL session for live market data
curl_close($chLive);

// Decode the JSON response
$dataLive = json_decode($responseLive, true);

// Check if the request was successful
if ($dataLive['status'] === true) {
    // Echo the response for live market data
    echo "Live Market Data:<br><pre>";
    echo json_encode($dataLive['data']['fetched'], JSON_PRETTY_PRINT);
    echo "</pre>"; // You can customize how you want to display the data
} else {
    // Handle the case where the request was not successful
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Error fetching live market data']);
    exit;
} */
?>

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

        .chartContainer{
            height: 400px;
            width: 800px;
        }
    </style>
    

    
    <?php include 'getChartData.php';?>
    
    <!-- HTML -->
    <div class="chartContainer">
        <div id="chartcontrols"></div>
        <div id="chartdiv"></div>
    </div>
