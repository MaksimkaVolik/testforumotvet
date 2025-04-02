<?php
require __DIR__.'/../core/config.php';
require __DIR__.'/../core/Database.php';
require __DIR__.'/../core/Notification.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$db = new Database();
$notification = new Notification();

// Помечаем все как прочитанные
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->query(
        "UPDATE notifications SET is_read = TRUE WHERE user_id = ?",
        [$_SESSION['user_id']]
    );
}

// Получаем уведомления
$notifications = $db->query(
    "SELECT * FROM notifications 
    WHERE user_id = ? 
    ORDER BY created_at DESC 
    LIMIT 50",
    [$_SESSION['user_id']]
)->fetchAll();

require __DIR__.'/../templates/notifications.php';