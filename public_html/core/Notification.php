<?php
class Notification {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($user_id, $type, $source_id) {
        $this->db->query(
            "INSERT INTO notifications (user_id, type, source_id) VALUES (?, ?, ?)",
            [$user_id, $type, $source_id]
        );
    }

    public function getUnreadCount($user_id) {
        return $this->db->query(
            "SELECT COUNT(*) FROM notifications 
            WHERE user_id = ? AND is_read = FALSE",
            [$user_id]
        )->fetchColumn();
    }

    public function markAsRead($notification_id) {
        $this->db->query(
            "UPDATE notifications SET is_read = TRUE WHERE id = ?",
            [$notification_id]
        );
    }
}