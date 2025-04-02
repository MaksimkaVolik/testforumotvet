<?php
class Comment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addComment($post_id, $user_id, $text) {
        $this->db->query(
            "INSERT INTO comments (post_id, user_id, text) VALUES (?, ?, ?)",
            [$post_id, $user_id, $text]
        );
        return $this->db->lastInsertId();
    }

    public function getComments($post_id) {
        return $this->db->query(
            "SELECT c.*, u.username, u.avatar 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_id = ?
            ORDER BY c.created_at DESC",
            [$post_id]
        )->fetchAll();
    }

    public function deleteComment($comment_id, $user_id) {
        return $this->db->query(
            "DELETE FROM comments WHERE id = ? AND user_id = ?",
            [$comment_id, $user_id]
        )->rowCount();
    }
}
public function addComment($post_id, $user_id, $text) {
    // Добавляем комментарий
    $this->db->query(
        "INSERT INTO comments (post_id, user_id, text) VALUES (?, ?, ?)",
        [$post_id, $user_id, $text]
    );
    
    // Отправляем уведомление автору поста
    $author_id = $this->db->query(
        "SELECT user_id FROM posts WHERE id = ?",
        [$post_id]
    )->fetchColumn();
    
    if ($author_id != $user_id) {
        $notification = new Notification();
        $notification->create($author_id, 'new_comment', $this->db->lastInsertId());
    }
}