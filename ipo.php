<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPO</title>
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="images/site.webmanifest">
    <style>
        body{
            background-color: rgb(255, 255, 255);
            color: black;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" integrity="sha512-17EgCFERpgZKcm0j0fEq1YCJuyAWdz9KUtv1EjVuaOz8pDnh/0nZxmU6BBXwaaxqoi9PQXnRWqlcDB027hgv9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
                .image-container {
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden; /* Crop the image if needed */
            height: 100%; /* Match the column height */
        }

        .image-container img {
            object-fit: cover; /* Crop and center the image */
            max-width: 100%; /* Ensure the image doesn't exceed its container */
            max-height: 100%; /* Ensure the image doesn't exceed its container */
        }
    </style>
</head>
<body>
<?php
  include 'sidebar.php';
  ?>
  <div class="content" id="dynamic-content">
  
        <div class="open">

            <h2>Open</h2>

            <div class="open-wrapper">
            <?php include 'open_ipo.php'; ?>
            </div>
        </div> 

        <div class="upcoming">

            <h2>Upcoming</h2>

            <div class="upcoming-wrapper">
            <?php include 'upcoming_ipo.php'; ?>
            </div>
        </div> 

        <div class="closed">

            <h2>Closed</h2>

            <div class="closed-wrapper">
            <?php include 'closed_ipo.php'; ?>
            </div>
        </div> 
  </div> 
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"
        integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"
        integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script type="text/javascript">
            $(document).ready(function(){
              $('.open-wrapper').slick({
                infinite: false,
                arrows: false,
              });
            });
            $(document).ready(function(){
              $('.upcoming-wrapper').slick({
                infinite: false,
                arrows: false,
              });
            });
            $(document).ready(function(){
              $('.closed-wrapper').slick({
                infinite: false,
                arrows: false,
              });
            });
          </script>

</body>
</html>