<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function updateProfile($user_id, $username, $bio, $avatar = null) {
        if ($avatar) {
            // Удаляем старый аватар, если он есть
            $oldAvatar = $this->db->query(
                "SELECT avatar FROM users WHERE id = ?",
                [$user_id]
            )->fetchColumn();
            
            if ($oldAvatar) {
                $filePath = $_SERVER['DOCUMENT_ROOT'].$oldAvatar;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->db->query(
                "UPDATE users SET username = ?, bio = ?, avatar = ? WHERE id = ?",
                [$username, $bio, $avatar, $user_id]
            );
        } else {
            $this->db->query(
                "UPDATE users SET username = ?, bio = ? WHERE id = ?",
                [$username, $bio, $user_id]
            );
        }
    }

    public function getUserById($user_id) {
        return $this->db->query(
            "SELECT id, username, email, bio, avatar FROM users WHERE id = ?",
            [$user_id]
        )->fetch();
    }
}