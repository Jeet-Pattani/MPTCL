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

 
    $data = json_encode([
        'exchange' => 'NSE',
        'symboltoken' => '3045',
        'interval' => 'ONE_MINUTE',
        'fromdate' => '2023-11-09 09:15',
        'todate' => '2023-11-09 09:45'
    ]);
    
    $headers = [
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
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/historical/v1/getCandleData');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => 'Error fetching historical data']);
        exit;
    }
    
    curl_close($ch);
    $data = json_decode($response, true);
    
    // Extract data array from the response
    $candleData = $data['data'];
    
    // Convert data to the desired format
    $convertedData = array_map(function ($candle) {
        return [
            'time' => date('Y-m-d H:i:s', strtotime($candle[0])),
            'open' => $candle[1],
            'high' => $candle[2],
            'low' => $candle[3],
            'close' => $candle[4],
        ];
    }, $candleData);
    
    // Return the converted data as JSON
    header('Content-Type: application/json');
    echo json_encode($convertedData);
    exit;

?>
