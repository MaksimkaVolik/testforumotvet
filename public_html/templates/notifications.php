<!DOCTYPE html>
<html>
<head>
    <title>Уведомления | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <?php require __DIR__.'/header.php'; ?>
    
    <div class="container">
        <h1>Уведомления</h1>
        
        <form method="POST">
            <button type="submit">Пометить все как прочитанные</button>
        </form>
        
        <div class="notifications-list">
            <?php foreach ($notifications as $notif): ?>
                <div class="notification <?= $notif['is_read'] ? 'read' : 'unread' ?>">
                    <?php switch ($notif['type']):
                        case 'new_comment': ?>
                            <p>Новый комментарий к вашему посту</p>
                            <?php break;
                        case 'like': ?>
                            <p>Кто-то лайкнул ваш пост</p>
                            <?php break;
                    endswitch; ?>
                    <small><?= date('d.m.Y H:i', strtotime($notif['created_at'])) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>