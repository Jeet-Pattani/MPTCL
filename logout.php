<?php
session_start();

// Check if the user is logged in, and if so, destroy the session
if (isset($_SESSION['user_id'])) {
    session_destroy();
    header('Location: login'); // Redirect to the login page after logging out
    exit();
} else {
    header('Location: login'); // Redirect to the login page if not logged in
    exit();
}
?>
