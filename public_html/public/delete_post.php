<?php
require __DIR__.'/../core/config.php';
require __DIR__.'/../core/Database.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$db = new Database();
$post_id = (int)$_GET['id'];

// Проверка прав
$post = $db->query(
    "SELECT user_id FROM posts WHERE id = ?",
    [$post_id]
)->fetch();

if ($post && ($post['user_id'] == $_SESSION['user_id'] || ($_SESSION['is_admin'] ?? false))) {
    // Удаляем связанные данные (реакции, комментарии)
    $db->query("DELETE FROM post_reactions WHERE post_id = ?", [$post_id]);
    $db->query("DELETE FROM comments WHERE post_id = ?", [$post_id]);
    
    // Удаляем сам пост
    $db->query("DELETE FROM posts WHERE id = ?", [$post_id]);
}

// Возвращаем JSON для AJAX или редирект
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
}
exit;