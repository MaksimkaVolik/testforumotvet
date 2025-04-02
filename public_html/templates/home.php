<div class="post">
    <h2><?= htmlspecialchars($post['title']) ?></h2>
    <div class="post-meta">
        Автор: <?= htmlspecialchars($post['username']) ?> | 
        Просмотры: <?= $post['views'] ?> | 
        <?= date('d.m.Y H:i', strtotime($post['created_at'])) ?>
        
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
            <a href="/edit_post.php?id=<?= $post['id'] ?>" class="edit-btn">✏️</a>
            <a href="/delete_post.php?id=<?= $post['id'] ?>" class="delete-btn" onclick="return confirm('Удалить пост?')">🗑️</a>
        <?php endif; ?>
    </div>
    <!-- Остальное содержимое поста -->
</div>
<!DOCTYPE html>
<html>
<head>
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/assets/style.css">
    <script src="/assets/script.js" defer></script>
</head>
<body>
    <?php require __DIR__.'/header.php'; ?>

    <div class="content">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['title']) ?></h2>
                <?php if ($post['image']): ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>" class="post-image">
                <?php endif; ?>
                <div class="reactions">
                    <button class="like-btn <?= $post['user_reaction'] === 'like' ? 'active' : '' ?>" 
                            data-post-id="<?= $post['id'] ?>">
                        👍 <span class="count"><?= $post['reactions']['likes'] ?? 0 ?></span>
                    </button>
                    <button class="dislike-btn <?= $post['user_reaction'] === 'dislike' ? 'active' : '' ?>" 
                            data-post-id="<?= $post['id'] ?>">
                        👎 <span class="count"><?= $post['reactions']['dislikes'] ?? 0 ?></span>
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>