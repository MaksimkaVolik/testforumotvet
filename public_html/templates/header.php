<div class="header">
    <div class="header-container">
        <!-- Логотип и навигация -->
        <div class="header-left">
            <a href="/" class="logo">
                <span class="logo-icon">🗨️</span>
                <span class="logo-text"><?= SITE_NAME ?></span>
            </a>
            <nav class="main-nav">
                <a href="/" class="nav-link">Главная</a>
                <a href="/popular.php" class="nav-link">Популярное</a>
                <a href="/categories.php" class="nav-link">Категории</a>
            </nav>
        </div>

        <!-- Правая часть (поиск + пользователь) -->
        <div class="header-right">
            <div class="search-box">
                <form action="/search.php" method="GET">
                    <input type="text" name="q" placeholder="Поиск..." class="search-input">
                    <button type="submit" class="search-btn">🔍</button>
                </form>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="user-actions">
                    <a href="/create_post.php" class="create-post-btn">+ Создать пост</a>
                    <div class="user-dropdown">
                        <div class="user-avatar-wrapper">
                            <img src="<?= $_SESSION['avatar'] ?? '/assets/default-avatar.png' ?>" 
                                 class="user-avatar" 
                                 alt="<?= htmlspecialchars($_SESSION['username']) ?>">
                            <?php if ($unread_count > 0): ?>
                                <span class="notification-badge"><?= $unread_count ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="dropdown-menu">
                            <a href="/profile.php" class="dropdown-item">👤 Профиль</a>
                            <a href="/messages.php" class="dropdown-item">✉️ Сообщения</a>
                            <a href="/notifications.php" class="dropdown-item">🔔 Уведомления</a>
                            <a href="/logout.php" class="dropdown-item">🚪 Выйти</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="/login.php" class="login-btn">Войти</a>
                    <a href="/register.php" class="register-btn">Регистрация</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>