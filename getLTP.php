<?php


// Data to send
$data = json_encode(array(
    "exchange" => $_GET['exchange'],
    "tradingsymbol" => $_GET['symbol'],
    "symboltoken" => $_GET['token'],
));

$url = 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/order/v1/getLtpData';

// Set the request headers
$headers = array(
    'X-PrivateKey: ' . $apiKey,
    'Accept: application/json',
    'X-SourceID: WEB',
    'X-ClientLocalIP: CLIENT_LOCAL_IP',
    'X-ClientPublicIP: CLIENT_PUBLIC_IP',
    'X-MACAddress: MAC_ADDRESS',
    'X-UserType: USER',
    'Authorization: Bearer ' . $jwtToken,
    'Content-Type: application/json',
);

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

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
}

// Output the API response
// if ($response) {
//     echo $response;
// } else {
//     echo "No response from the API.";
// }

// Decode JSON to an associative array
$data = json_decode($response, true);

// Access specific data
// $exchange = $data['data']['exchange'];
// $tradingsymbol = $data['data']['tradingsymbol'];
// $symboltoken = $data['data']['symboltoken'];
$open_price = $data['data']['open'];
$high_price = $data['data']['high'];
$low_price = $data['data']['low'];
$close_price = $data['data']['close'];
$ltp = $data['data']['ltp'];

// Close the cURL session
curl_close($ch);
?>