<?php
session_start();
include "dbconfig.php";

// Check if the user is logged in
if (!isset($_SESSION['angel_login']) || $_SESSION['angel_login'] !== true) {
    include 'sidebar.php';
    echo '<div class="content"><h1>Login To Access</h1></div>';
    exit; // Stop executing the rest of the code
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <style>
        body {
            background-color: #f0f0f0;
            color: #000;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            background-color: #333;
            color: #fff;
            padding: 15px;
            width: 250px;
            position: fixed;
            height: 100%;
        }

        .content {
            margin-left: 280px;
            padding: 20px;
        }

        h1.page-heading {
            color: #4CAF50; /* Green color */
        }

        h2.page-sub-heading {
            color: #333;
            margin-top: 1rem;
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            /* background-color: #4CAF50; Green button color */
            /* color: #fff; */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button#searchButton {
            background-color: #4CAF50; /* Green button for search */
            color: #fff;
        }
        button#clearButton {
            color: black;
            background-color: #00bcd4; /* Cyan button for Clear */
        }

        button.addToWatchlist{
            background-color: #00bcd4;
            margin-left: 0.5rem;
        }

        .trade-button {
            background-color: #00bcd4; /* Cyan color for "Trade" */
            text-decoration: none;
            color: black;
            padding: 8px 15px;
            border-radius: 8px;
            margin-left: 0.5rem;
        }

        button.remove-button {
            background-color: #ff5722; /* Red color for "Remove" */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
        ul li{
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
<?php
include 'sidebar.php';
?>

<div class="content">   
    <?php
    $clientcode = $_SESSION['clientcode'];
    $nameQuery = "SELECT * FROM users WHERE clientcode = ?";
    $stmt1 = $conn->prepare($nameQuery);
    $stmt1->bind_param("s", $clientcode);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $stmt1->close();

    $row1 = $result1->fetch_assoc();
    
    ?>
    <h1 class='page-heading'>Welcome <?php echo $row1['name'];?> !</h1>
    <h2 class="page-sub-heading">Instrument Search</h2>
    <br>
    <div>
        <label for="searchTerm">Enter Search Term:</label>
        <input type="text" id="searchTerm" placeholder="Search term..." style='margin:0.5rem auto;'>
        <button id="searchButton">Search</button>
        <button id="clearButton">Clear</button>
    </div>
    <div id="results">
        <!-- <h2 class="page-sub-heading">Search Results:</h2> -->
        <ul id="resultsList">
        </ul>
    </div>
    <h1 style="margin: 1.3rem auto 0.2rem;">Watchlist</h1>
    <?php
    // Fetch the user's watchlist based on their clientcode
    if (isset($_SESSION['clientcode'])) {

        // Prepare and execute a SQL query to get the watchlist data
        $watchlistQuery = "SELECT * FROM angel_watchlist WHERE clientcode = ?";
        $stmt = $conn->prepare($watchlistQuery);
        $stmt->bind_param("s", $clientcode);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();


        // Display the watchlist in a table
        if ($result->num_rows > 0) {
            echo '<table border="1">';
            echo '<tr><th>Symbol</th><th>Name</th><th>Token</th><th>Exchange Segment</th><th>Action</th></tr>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['symbol'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['token'] . '</td>';
                echo '<td>' . $row['exchange'] . '</td>';
                echo '<td>';
                echo '<button data-token="' . $row['token'] . '" class="remove-button">Remove</button>';
                echo '<a href="trade?token=' . $row['token'] . '&name=' . $row['name'] . '&symbol=' . $row['symbol'] . '&exchange=' . $row['exchange'] . '" class="trade-button">Trade</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p>Your watchlist is empty.</p>';
        }
    } else {
        echo '<p>Invalid user session.</p>';
    }
    ?>
</div>
<script src="js/frontend_search.js"></script>
<script src="js/remove_from_watchlist.js"></script>

</body>
</html>
