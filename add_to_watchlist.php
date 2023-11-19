<?php
session_start();
include "dbconfig.php";

if (isset($_POST['symbol'], $_POST['name'], $_POST['token'], $_POST['exchange'])) {
    // Get the clientcode from the session
    $clientcode = $_SESSION['clientcode'];

    // Check if the token already exists in the watchlist for the same clientcode
    $checkQuery = "SELECT COUNT(*) FROM angel_watchlist WHERE clientcode = ? AND token = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ss", $clientcode, $_POST['token']);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // Token already exists in the watchlist, return a response indicating it
        echo json_encode(array("success" => false, "error" => "Token already exists in the watchlist."));
    } else {
        // Token does not exist, proceed to insert the record
        $symbol = $_POST['symbol'];
        $name = $_POST['name'];
        $token = $_POST['token'];
        $exchange = $_POST['exchange'];

        // Prepare and execute the SQL query to insert data into the angel_watchlist table
        $stmt = $conn->prepare("INSERT INTO angel_watchlist (clientcode, token, symbol, name, exchange) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $clientcode, $token, $symbol, $name, $exchange);

        if ($stmt->execute()) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false, "error" => $stmt->error));
        }

        $stmt->close();
    }
} else {
    echo json_encode(array("success" => false, "error" => "Incomplete data provided."));
}
