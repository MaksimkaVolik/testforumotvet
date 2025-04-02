<?php
require __DIR__.'/../core/config.php';
require __DIR__.'/../core/Database.php';
require __DIR__.'/../core/User.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$user = new User();

// Отправка сообщения
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_id = (int)$_POST['recipient_id'];
    $message = trim($_POST['message']);
    
    if (!empty($message)) {
        $db->query(
            "INSERT INTO private_messages (sender_id, recipient_id, message) 
            VALUES (?, ?, ?)",
            [$_SESSION['user_id'], $recipient_id, $message]
        );
    }
}

// Получение списка сообщений
$messages = $db->query("
    SELECT m.*, u.username as sender_name 
    FROM private_messages m
    JOIN users u ON m.sender_id = u.id
    WHERE m.recipient_id = ?
    ORDER BY m.created_at DESC
", [$_SESSION['user_id']])->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Личные сообщения | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Мои сообщения</h1>
        <div class="messages">
            <?php foreach ($messages as $msg): ?>
                <div class="message <?= $msg['is_read'] ? 'read' : 'unread' ?>">
                    <p><strong><?= htmlspecialchars($msg['sender_name']) ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                    <small><?= date('d.m.Y H:i', strtotime($msg['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>