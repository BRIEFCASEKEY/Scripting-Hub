<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['firstName'] = $user['firstName'];
            header("Location: ../mainproject/menu.html");
            exit();
        } else {
            header("Location: index.php?error=incorrect");
            exit();
        }
    } else {
        header("Location: index.php?error=notfound");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
