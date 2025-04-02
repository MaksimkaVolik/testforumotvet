<?php
require __DIR__.'/../core/config.php';
require __DIR__.'/../core/Database.php';
require __DIR__.'/../core/User.php';

$db = new Database();
$user = new User();

// Получаем ID профиля из URL
$profile_id = isset($_GET['id']) ? (int)$_GET['id'] : ($_SESSION['user_id'] ?? 0);

// Получаем данные пользователя
$profile_data = $db->query(
    "SELECT id, username, email, bio, avatar, created_at 
     FROM users WHERE id = ?", 
    [$profile_id]
)->fetch();

if (!$profile_data) {
    header('Location: /');
    exit;
}

// Получаем посты пользователя
$posts = $db->query(
    "SELECT p.*, COUNT(c.id) as comments_count 
     FROM posts p
     LEFT JOIN comments c ON p.id = c.post_id
     WHERE p.user_id = ?
     GROUP BY p.id
     ORDER BY p.created_at DESC",
    [$profile_id]
)->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Профиль <?= htmlspecialchars($profile_data['username']) ?> | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php require __DIR__.'/../templates/header.php'; ?>
    
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="<?= $profile_data['avatar'] ?? '/assets/default-avatar.png' ?>" 
                     alt="<?= htmlspecialchars($profile_data['username']) ?>">
                <?php if ($profile_id == ($_SESSION['user_id'] ?? 0)): ?>
                <button id="change-avatar-btn">Изменить фото</button>
                <?php endif; ?>
            </div>
            
            <div class="profile-info">
                <h1><?= htmlspecialchars($profile_data['username']) ?></h1>
                <p class="profile-bio"><?= nl2br(htmlspecialchars($profile_data['bio'] ?? 'Пока ничего не рассказал о себе')) ?></p>
                <p class="profile-stats">
                    <span>📝 Постов: <?= count($posts) ?></span>
                    <span>👥 Участник с: <?= date('d.m.Y', strtotime($profile_data['created_at'])) ?></span>
                </p>
            </div>
        </div>
        
        <div class="profile-content">
            <h2>Последние публикации</h2>
            <div class="user-posts">
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <?php include __DIR__.'/../templates/post.php'; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-posts">Пользователь пока не опубликовал ни одного поста</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="/assets/profile.js"></script>
</body>
</html>