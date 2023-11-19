<?php
session_start();

// Check if the user is logged in and is the admin (user ID 6)
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 6) {
    header('Location: error'); // Redirect to an error page
    exit();
}

// Process the form submission and insert news details into the database
require_once 'dbconfig.php';

if (isset($_POST['submit'])) {
    $headline = isset($_POST['headline']) ? $_POST['headline'] : '';
    $writerName = isset($_POST['writer_name']) ? $_POST['writer_name'] : ''; // Set the writer name
    $mainArticle = isset($_POST['main_article']) ? $_POST['main_article'] : '';
    $tags = isset($_POST['tags']) ? $_POST['tags'] : '';

    $imageLink = ''; // Initialize image link variable

    // Check if an image was uploaded
    if (isset($_FILES['news_image']) && $_FILES['news_image']['error'] === 0) {
        $imageLink = uploadImage(); // Get the uploaded image path
    }

    $introPara = extractIntroParagraph($mainArticle); // Extract the first paragraph

    // Insert the news into the database
    $sql = "INSERT INTO news (headline, writer_name, image_link, main_article, intro_para, tags) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssss", $headline, $writerName, $imageLink, $mainArticle, $introPara, $tags);

        if ($stmt->execute()) {
            // News added successfully
            $_SESSION['news_success'] = "News added successfully.";
            header('Location: addnews.php'); // Redirect to the news page
            exit();
        } else {
            // Adding news failed
            $_SESSION['registration_error'] = "Failed to add news: " . $stmt->error;
            header('Location: addnews.php'); // Redirect back to the add news page
            exit();
        }

        $stmt->close();
    } else {
        // Error in preparing the statement
        $_SESSION['registration_error'] = "Error in database query: " . $conn->error;
        header('Location: addnews.php'); // Redirect back to the add news page
        exit();
    }
}

// Function to handle image upload with a unique random name
function uploadImage() {
    $targetDir = "news_images/";
    $imageExtension = pathinfo($_FILES["news_image"]["name"], PATHINFO_EXTENSION);
    $uniqueFileName = uniqid() . '_' . bin2hex(random_bytes(8)) . '.' . $imageExtension;
    $targetFilePath = $targetDir . $uniqueFileName;

    if (move_uploaded_file($_FILES["news_image"]["tmp_name"], $targetFilePath)) {
        return $targetFilePath;
    } else {
        return '';
    }
}

// Function to extract the first paragraph from the main article
function extractIntroParagraph($mainArticle) {
    // Split the main article into paragraphs
    $paragraphs = preg_split('/\r\n|\r|\n/', $mainArticle);

    // Get the first paragraph
    $introPara = trim($paragraphs[0]);

    return $introPara;
}

// Close the database connection
$conn->close();
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href "images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <link rel="stylesheet" href="css/addnews.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="content" id="dynamic-content">
    <div class="form-container" id="add-news-form">
        <h2>Add News</h2>
        <?php
if (isset($_SESSION['news_success'])) {
    echo '<div class="success-message">' . $_SESSION['news_success'] . '</div>';
    unset($_SESSION['news_success']);
}
?>

        <form method="post" action="addnews.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="headline">Headline</label>
                <input id="headline" type="text" name="headline" required>
            </div>
            <div class="form-group">
                <label for="writer-name">Writer Name</label>
                <input id="writer-name" type="text" name="writer_name" value="MPTCL admin" required>
            </div>
            <!-- <div class="form-group">
                <label for="image-link">Image Link</label>
                <input id="image-link" type="text" name="image_link">
            </div> -->
            <div class="form-group">
                <label for="news-image">Image Upload</label>
                <input type="file" name="news_image" id="news-image" accept="image/*" required>
            </div>

            <!-- <div class="form-group">
                <label for="intro-article">Intro Paragraph</label>
                <textarea id="intro-article" name="intro_article" required></textarea>
            </div> -->

            <div class="form-group">
                <label for="main-article">Main Article</label>
                <textarea id="main-article" name="main_article" required></textarea>
            </div>
            <div class="form-group">
                <label for="tags">Tags</label>
                <input id="tags" type="text" name="tags">
            </div>
            <div class="form-actions">
                <button type="submit" name="submit">Add News</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>


