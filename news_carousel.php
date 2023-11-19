<?php

include 'dbconfig.php'; // Include your database configuration

// SQL query to fetch data from the "news" table
$sql = "SELECT * FROM news";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // Generate a carousel item for each row
        echo '<div class="col">';
        echo '<div class="card h-100">';
        echo '<img src="' . $row["image_link"] . '" class="card-img-top" style="object-fit:cover;height:200px" alt="...">';
        echo ' <div class="card-body">';
        echo '<h5 class="card-title truncateHeading">' . $row["headline"] . '</h5>';
        echo '<p class="card-text truncatePara">' . $row["intro_para"] . '</p>';
        echo '<a href="full_news.php?id=' . $row["id"] . '" class="btn btn-primary">Read More</a>';
        echo ' </div>';
        echo '<div class="card-footer">';
        echo ' <small class="text-body-secondary">Updated at: ' .$row["time_added"]  .'</small>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo "No news articles found.";
}

$conn->close();
?>