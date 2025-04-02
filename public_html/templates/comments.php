<div class="comments-section">
    <h3>Комментарии</h3>
    
    <?php if (isset($_SESSION['user_id'])): ?>
    <form class="comment-form" method="POST" action="/api/add_comment.php">
        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
        <textarea name="text" placeholder="Ваш комментарий..." required></textarea>
        <button type="submit">Отправить</button>
    </form>
    <?php endif; ?>

    <div class="comments-list">
        <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <div class="comment-header">
                <img src="<?= $comment['avatar'] ?? '/assets/default-avatar.jpg' ?>" class="comment-avatar">
                <span class="comment-author"><?= htmlspecialchars($comment['username']) ?></span>
                <span class="comment-date"><?= date('d.m.Y H:i', strtotime($comment['created_at'])) ?></span>
            </div>
            <div class="comment-text"><?= nl2br(htmlspecialchars($comment['text'])) ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</div>