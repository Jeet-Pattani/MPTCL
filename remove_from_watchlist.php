<?php
session_start();
include "dbconfig.php";

if (isset($_POST['token'])) {
    // Get the clientcode from the session
    $clientcode = $_SESSION['clientcode'];
    $token = $_POST['token'];

    // Prepare and execute the SQL query to remove the item from the watchlist
    $stmt = $conn->prepare("DELETE FROM angel_watchlist WHERE clientcode = ? AND token = ?");
    $stmt->bind_param("ss", $clientcode, $token);

    if ($stmt->execute()) {
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "error" => $stmt->error));
    }

    $stmt->close();
} else {
    echo json_encode(array("success" => false, "error" => "Incomplete data provided."));
}
?>
