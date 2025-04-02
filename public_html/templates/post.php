<div class="post" id="post-<?= $post['id'] ?>">
    <div class="post-header">
        <div class="post-author">
            <img src="<?= $post['avatar'] ?? '/assets/default-avatar.png' ?>" class="author-avatar">
            <div class="author-info">
                <a href="/profile.php?id=<?= $post['user_id'] ?>" class="author-name"><?= htmlspecialchars($post['username']) ?></a>
                <span class="post-date"><?= date('d.m.Y H:i', strtotime($post['created_at'])) ?></span>
            </div>
        </div>
        
        <?php if (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == $post['user_id'] || $_SESSION['is_admin'])): ?>
        <div class="post-actions">
            <button class="action-btn edit-post" data-post-id="<?= $post['id'] ?>">✏️</button>
            <button class="action-btn delete-post" data-post-id="<?= $post['id'] ?>">🗑️</button>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="post-content">
        <h2 class="post-title"><?= htmlspecialchars($post['title']) ?></h2>
        <div class="post-text"><?= nl2br(htmlspecialchars($post['content'])) ?></div>
        
        <?php if (!empty($post['image'])): ?>
        <div class="post-image-container">
            <img src="<?= $post['image'] ?>" class="post-image" loading="lazy">
        </div>
        <?php endif; ?>
    </div>
    
    <div class="post-footer">
        <div class="post-stats">
            <span class="views-count">👁️ <?= $post['views'] ?> просмотров</span>
            <span class="comments-count">💬 <?= $post['comments_count'] ?? 0 ?> комментариев</span>
        </div>
        
        <div class="post-reactions">
            <button class="reaction-btn like-btn <?= $post['user_reaction'] === 'like' ? 'active' : '' ?>" 
                    data-post-id="<?= $post['id'] ?>">
                👍 <span class="count"><?= $post['reactions']['likes'] ?? 0 ?></span>
            </button>
            <button class="reaction-btn dislike-btn <?= $post['user_reaction'] === 'dislike' ? 'active' : '' ?>" 
                    data-post-id="<?= $post['id'] ?>">
                👎 <span class="count"><?= $post['reactions']['dislikes'] ?? 0 ?></span>
            </button>
        </div>
    </div>
</div>