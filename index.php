<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeMaster</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
<link rel="manifest" href="images/site.webmanifest">
</head>

<body>
    <div class="main-container">
    <?php
  include 'sidebar.php';
  header('location: market');

  ?>
        <div class="content" id="dynamic-content">
            <!-- Add your content here -->
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- <script>
        // Function to load content based on the clicked link
        function loadPage(pageName) {
            $.ajax({
                url: pageName,
                method: "GET",
                success: function (data) {
                    $("#dynamic-content").html(data);
                },
                error: function () {
                    $("#dynamic-content").html("<p>Error loading page.</p>");
                }
            });
        }

        // Handle link clicks in the sidebar
        $(document).ready(function () {
            // Load "market.html" content by default
            loadPage("market.php");

            $(".sidebar .links div a").click(function (e) {
                e.preventDefault(); // Prevent the default link behavior (page navigation)
                var pageToLoad = $(this).attr("href");
                loadPage(pageToLoad);
            });
        });
    </script> -->

    

</body>
</html>