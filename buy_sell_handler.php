<?php
// Include your database connection code from dbconfig.php
include "dbconfig.php";

 if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get input values from the form in trade.php
    $action = $_POST['action']; // "buy" or "sell"
    $token = $_POST['token'];
    $name = $_POST['name'];
    $symbol = $_POST['symbol'];
    $exchange = $_POST['exchange'];

    // // Your Angel Broking API credentials
    $jwtToken = $_SESSION['jwtToken'];
    $apiKey = $_SESSION['apiKey'];

    // Construct the request data for placing an order
    $orderData = [
        "variety" => "NORMAL", // You can change the variety as needed
        "tradingsymbol" => $symbol,
        "symboltoken" => $token,
        "transactiontype" => strtoupper($action), // "BUY" or "SELL"
        "exchange" => $exchange,
        "ordertype" => "MARKET", // You can change the order type as needed
        "producttype" => "DELIVERY", // You can change the product type as needed
        "duration" => "DAY", // You can change the duration as needed
        "price" => "0", // Since it's a market order
        "squareoff" => "0", // If applicable, set squareoff value
        "stoploss" => "0", // If applicable, set stoploss value
        "quantity" => 1 // Set the quantity as needed
    ];

    // Convert the order data to JSON
    $orderDataJson = json_encode($orderData);

    // URL for placing orders
    $orderUrl = 'https://apiconnect.angelbroking.com/rest/secure/angelbroking/order/v1/placeOrder';

    // Set the request headers
    $requestHeaders = [
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

    // Check if the order placement was successful
    if (false) {
        // Order placed successfully
        // Extract and store the order ID in the database
        $orderId = $orderResponse['data']['orderid'];
        // You can also store other order details in the database if needed

        // Insert order details into the angel_order table in the database
        $sql = "INSERT INTO angel_order (clientcode, variety, tradingsymbol, symboltoken, price, transactiontype, exchange, ordertype, producttype, duration, orderid) VALUES ('$clientCode', '{$orderData['variety']}', '$symbol', '$token', {$orderData['price']}, '{$orderData['transactiontype']}', '$exchange', '{$orderData['ordertype']}', '{$orderData['producttype']}', '{$orderData['duration']}', '$orderId')";

        if ($conn->query($sql) === true) {
            echo "Order placed successfully. Order ID: $orderId";
        } else {
            echo "Error placing order: " . $conn->error;
        }
    } else {
        // Order placement failed
        echo "Error placing order: "; //. $orderResponse['message'];
    }
} 
else{
    echo "Error placing order: ";
    echo '<h4>code: AG8001, desc: insufficent balance</h4>';
}
?>
