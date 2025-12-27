<?php
session_start();
require_once "../project-folder/connect.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../project-folder/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Delete user record from DB
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    // Destroy session and redirect to register/login page
    session_destroy();
    header("Location: ../project-folder/index.php");
    exit();
} else {
    echo "Error deleting account: " . $conn->error;
}
?>
