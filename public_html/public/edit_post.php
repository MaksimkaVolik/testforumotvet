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
$error = '';

// Проверка прав доступа
$post = $db->query(
    "SELECT * FROM posts WHERE id = ? AND user_id = ?",
    [$post_id, $_SESSION['user_id']]
)->fetch();

if (!$post) {
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (!empty($title) && !empty($content)) {
        $db->query(
            "UPDATE posts SET title = ?, content = ? WHERE id = ?",
            [$title, $content, $post_id]
        );
        header('Location: /');
        exit;
    } else {
        $error = "Заполните все поля!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Редактировать пост | <?= SITE_NAME ?></title>
    <!-- TinyMCE и стили как в create_post.php -->
</head>
<body>
    <form method="POST">
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
        <textarea id="post-content" name="content"><?= htmlspecialchars($post['content']) ?></textarea>
        <button type="submit">Сохранить</button>
    </form>
</body>
</html>