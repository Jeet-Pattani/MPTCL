<?php

include 'dbconfig.php'; // Include your database configuration

// SQL query to fetch rows with status "open"
$sql = "SELECT * FROM ipo WHERE status = 'upcoming'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Generate a card for each row
        echo '<div class="card my-3">';
        echo '<div class="row g-0">';
        echo '<div class="col-md-2 d-flex align-items-center">';
        echo '<div class="image-container">';
        echo '<img src=" '. $row["company_logo"] . '" class="img-fluid" alt="...">';
        echo '</div>';
        echo '</div>';
        echo '<div class="col-md-10">';
        echo '<div class="card-body">';
        echo '<div class="row">';
        echo '<div class="col-md-6">';
        echo '<table class="table table-striped table-borderless">';
        echo '<tbody>';
        echo '<tr><th>Company Name</th><td>' . $row["company_name"] . '</td></tr>';
        echo '<tr><th>Open Date</th><td>' . $row["open_date"] . '</td></tr>';
        echo '<tr><th>Close Date</th><td>' . $row["close_date"] . '</td></tr>';
        echo '<tr><th>Listing Date</th><td>' . $row["listing_date"] . '</td></tr>';
        echo '<tr><th>Price Range</th><td>₹' . $row["price_range"] . '</td></tr>';
        echo '<tr><th>Min Investment</th><td>₹' . $row["min_investment"] . '</td></tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '<div class="col-md-6">';
        echo '<table class="table table-striped table-borderless">';
        echo '<tbody>';
        echo '<tr><th>Lot Size</th><td>' . $row["lot_size"] . '</td></tr>';
        echo '<tr><th>Issue Size</th><td>₹' . $row["issue_size"] . '</td></tr>';
        echo '<tr><th>Subscription</th><td>' . $row["subscription"] . 'x</td></tr>';
        echo '<tr><th>Exchange</th><td>' . $row["exchange"] . '</td></tr>';
        echo '<tr><th>Status</th><td>' . $row["status"] . '</td></tr>';
        echo '<tr><th>Recommendation</th><td>' . $row["recommendation"] . '</td></tr>';
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "No open IPOs found.";
}

$conn->close();
?>
