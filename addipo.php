<?php
session_start();

// Check if the user is logged in and is the admin (user ID 6)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 6) {
    header('Location: error_page.php'); // Redirect to an error page
    exit();
}

// Process the form submission and insert IPO details into the database
require_once 'dbconfig.php';

if (isset($_POST['submit'])) {
    $companyName = isset($_POST['company_name']) ? $_POST['company_name'] : '';
    $companyLogo = ''; // We'll handle image upload separately
    $openDate = isset($_POST['open_date']) ? $_POST['open_date'] : '';
    $closeDate = isset($_POST['close_date']) ? $_POST['close_date'] : '';
    $listingDate = isset($_POST['listing_date']) ? $_POST['listing_date'] : '';
    $priceRange = isset($_POST['price_range']) ? $_POST['price_range'] : '';
    $minInvestment = isset($_POST['minimum_investment']) ? $_POST['minimum_investment'] : '';
    $lotSize = isset($_POST['lot_size']) ? $_POST['lot_size'] : '';
    $issueSize = isset($_POST['issue_size']) ? $_POST['issue_size'] : '';
    $subscription = isset($_POST['subscription']) ? $_POST['subscription'] : '';
    $exchange = isset($_POST['exchange']) ? $_POST['exchange'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $recommendation = isset($_POST['recommendation']) ? $_POST['recommendation'] : '';

    // Check if a company logo image was uploaded
    if (isset($_FILES['company_logo']) && $_FILES['company_logo']['error'] === 0) {
        $companyLogo = uploadImage('company_logo', $companyName);
    } else {
        $companyLogo = ''; // No image uploaded
    }

    // Insert IPO details into the database
    $sql = "INSERT INTO IPO (company_name, company_logo, open_date, close_date, listing_date, price_range, min_investment, lot_size, issue_size, subscription, exchange, status, recommendation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssssssssss", $companyName, $companyLogo, $openDate, $closeDate, $listingDate, $priceRange, $minInvestment, $lotSize, $issueSize, $subscription, $exchange, $status, $recommendation);

        if ($stmt->execute()) {
            // IPO details added successfully
            $_SESSION['ipo_success'] = "IPO details added successfully.";
            header('Location: addipo.php'); // Redirect to the IPO page
            exit();
        } else {
            // Adding IPO details failed
            $_SESSION['ipo_error'] = "Failed to add IPO details: " . $stmt->error;
            header('Location: addipo.php'); // Redirect back to the add IPO page
            exit();
        }

        $stmt->close();
    } else {
        // Error in preparing the statement
        $_SESSION['ipo_error'] = "Error in database query: " . $conn->error;
        header('Location: addipo.php'); // Redirect back to the add IPO page
        exit();
    }
}

// Function to handle image upload with a unique random name
function uploadImage($inputName, $companyName) {
    $targetDir = "ipo_images/";
    $imageExtension = pathinfo($_FILES[$inputName]["name"], PATHINFO_EXTENSION);
    
    // Replace spaces with underscores in the company name
    $companyName = str_replace(' ', '_', $companyName);
    
    $uniqueFileName = $companyName . '.' . $imageExtension;
    $targetFilePath = $targetDir . $uniqueFileName;

    if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFilePath)) {
        return $targetFilePath;
    } else {
        return '';
    }
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add IPO</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href "images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <link rel="stylesheet" href="css/addnews.css">
    <style>
                    .cbx {
                    display: flex;
                    align-items: center;
                    margin-bottom:0.5rem;
                }

                /* Add margin to the labels for spacing */
                .cbx label {
                    margin-right: 1.3rem;
                    margin-left: 0.2rem;
                    margin-bottom: 0;
                }

                /* Remove default list styling for checkboxes */
                .cbx input[type="checkbox"] {
                    list-style: none;
                    margin-right: 0.2rem;
                }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="content" id="dynamic-content">
    <div class="form-container" id="add-news-form">
        <h2>Add IPO</h2>
        <?php
        if (isset($_SESSION['ipo_success'])) {
            echo '<div class="success-message">' . $_SESSION['ipo_success'] . '</div>';
            unset($_SESSION['ipo_success']);
        }
        ?>
        <form method="post" action="addipo.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="company_name">Issuer Company</label>
                <input id="company_name" type="text" name="company_name" required>
            </div>
            <div class="form-group">
                <label for="company-logo">Company Logo</label>
                <input type="file" name="company_logo" id="company-logo" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="open-date">Open Date</label>
                <input id="open-date" type="text" name="open_date" required>
            </div>
            <div class="form-group">
                <label for="close-date">Close Date</label>
                <input id="close-date" type="text" name="close_date" required>
            </div>
            <div class="form-group">
                <label for="listing-date">Listing Date</label>
                <input id="listing-date" type="text" name="listing_date" required>
            </div>
            <div class="form-group">
                <label for="price-range">Price Range</label>
                <input id="price-range" type="text" name="price_range" required>
            </div>
            <div class="form-group">
                <label for="minimum-investment">Minimum Investment</label>
                <input id="minimum-investment" type="text" name="minimum_investment" required>
            </div>
            <div class="form-group">
                <label for="lot-size">Lot Size</label>
                <input id="lot-size" type="text" name="lot_size" required>
            </div>
            <div class="form-group">
                <label for="issue-size">Issue Size (Rs Cr.)</label>
                <input id="issue-size" type="text" name="issue_size" required>
            </div>
            <div class="form-group">
                <label for="subscription">Overall Subscription</label>
                <input id="subscription" type="text"  name="subscription" required>
            </div>

            <div class="form-group">
                <label for="Exchange">Exchange: </label>
                <div class="cbx">
                    <input type="radio" id="nse" name="exchange" value="NSE">
                    <label for="nse">NSE</label>
                    <input type="radio" id="bse" name="exchange" value="BSE">
                    <label for="bse">BSE</label>
                    <input type="radio" id="both" name="exchange" value="NSE, BSE">
                    <label for="both">Both</label>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status: </label>
                <div class="cbx">
                    <input type="radio" id="status1" name="status" value="Open">
                    <label for="status1">Open</label>
                    <input type="radio" id="status2" name="status" value="Closed">
                    <label for="status2">Closed</label>
                    <input type="radio" id="status3" name="status" value="Upcoming">
                    <label for="status3">Upcoming</label>
                </div>
            </div>    
            <div class="form-group">
                <label for="Recommendation">Recommendation: </label>
                <div class="cbx">
                    <input type="radio" id="rec1" name="recommendation" value="Apply">
                    <label for="rec1">Apply</label>
                    <input type="radio" id="rec2" name="recommendation" value="May Apply">
                    <label for="rec2">May Apply</label>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="submit">Add IPO</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>


