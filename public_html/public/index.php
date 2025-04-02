<?php
require __DIR__.'/../core/config.php';
require __DIR__.'/../core/Database.php';
require __DIR__.'/../core/Reaction.php';

$db = new Database();
$reaction = new Reaction();

// Получение постов
$posts = $db->query("
    SELECT p.*, u.username 
    FROM posts p
    JOIN users u ON p.user_id = u.id
    ORDER BY p.created_at DESC
")->fetchAll();

// В начале файла, после получения поста:
$db->query("UPDATE posts SET views = views + 1 WHERE id = ?", [$post['id']]);

// Получение реакций
foreach ($posts as &$post) {
    $post['reactions'] = $reaction->getReactionCounts($post['id']);
    if (isset($_SESSION['user_id'])) {
        $post['user_reaction'] = $reaction->getUserReaction($post['id'], $_SESSION['user_id']);
    }
}

require __DIR__.'/../templates/home.php';