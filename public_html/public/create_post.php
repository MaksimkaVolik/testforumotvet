<?php
require __DIR__.'/../core/config.php';
require __DIR__.'/../core/Database.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit;
}

$db = new Database();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    // Проверка заполнения полей
    if (empty($title) || empty($content)) {
        $error = "Заполните заголовок и текст поста!";
    } else {
        // Обработка изображения
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__.'/uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            
            if (array_key_exists($fileType, $allowedTypes)) {
                $ext = $allowedTypes[$fileType];
                $filename = uniqid().'.'.$ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir.$filename);
                $imagePath = '/public/uploads/'.$filename;
            }
        }
        
        // Сохранение поста
        $db->query(
            "INSERT INTO posts (user_id, title, content, image) VALUES (?, ?, ?, ?)",
            [$_SESSION['user_id'], $title, $content, $imagePath]
        );
        header('Location: /');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Новый пост | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php require __DIR__.'/../templates/header.php'; ?>
    
    <div class="container">
        <h1>Создать новый пост</h1>
        
        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data" class="post-form">
            <input type="text" name="title" placeholder="Заголовок поста" required>
            <textarea name="content" placeholder="Текст поста..." rows="10" required></textarea>
            <label for="image">Изображение (необязательно):</label>
            <input type="file" name="image" id="image" accept="image/*">
            <button type="submit" class="btn-submit">Опубликовать</button>
        </form>
    </div>
</body>
</html>