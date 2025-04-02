<?php
require __DIR__.'/../core/config.php';
require __DIR__.'/../core/Database.php';

$db = new Database();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Проверка существования пользователя
    $userExists = $db->query(
        "SELECT id FROM users WHERE email = ? OR username = ?",
        [$email, $username]
    )->fetch();

    if (!$userExists) {
        $db->query(
            "INSERT INTO users (username, email, password) VALUES (?, ?, ?)",
            [$username, $email, $password]
        );
        header('Location: /login.php');
        exit;
    } else {
        $error = "Пользователь уже существует";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Регистрация | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Логин" required minlength="3">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required minlength="6">
            <button type="submit">Зарегистрироваться</button>
        </form>
        <p>Уже есть аккаунт? <a href="/login.php">Войти</a></p>
    </div>
</body>
</html>