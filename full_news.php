<?php
session_start();

include 'dbconfig.php'; // Include your database configuration

// Get the news id from the query string
$newsId = $_GET["id"];

// SQL query to fetch the full news details
$sql = "SELECT * FROM news WHERE id = $newsId";

// Execute the SQL query and fetch the results
$result = $conn->query($sql);
// Define the $row variable
$row = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MPTCL News</title>
<link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href "images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
<link rel="stylesheet" href="css/fullnews.css">

</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="content">
<div class="main-news-container">
<?php
// Check if any results were returned
if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();
// Get the text from the database
$text = $row["main_article"];

// Convert the line breaks to enter keys
$text = str_replace("\n", "<br />\n", $text);
echo '<h1>' . $row["headline"] . '</h1><br>';
echo '<p class="card-text">' . $row["writer_name"] . ' | Rajkot, Gujarat</p>';
echo '<p class="card-text">Last updated : <strong>' . $row["time_added"] . '</strong></p><br>';
echo '<img src="' . $row["image_link"] . '" class="img-fluid rounded-start" style="position: relative;
left: 50%;
transform: translateX(-50%);" alt="" />';
echo '<div class="news-body">' . $text . '</div>';
  


} else {
    echo "No news article found.";
}

// Close the database connection
$conn->close();
?>
</div>
</div>


</body>
</html>
