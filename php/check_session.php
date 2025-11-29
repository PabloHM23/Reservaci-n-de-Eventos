<?php
session_start();

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    return [
        'id' => $_SESSION['user_id'],
        'nombre' => $_SESSION['user_name'],
        'email' => $_SESSION['user_email']
    ];
}

function requireAuth() {
    $user = checkAuth();
    if (!$user) {
        header('Location: /index.php');
        exit();
    }
    return $user;
}
?>