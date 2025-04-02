<?php
class Reaction {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function toggleReaction($post_id, $user_id, $type) {
        $existing = $this->db->query(
            "SELECT id, reaction FROM post_reactions 
             WHERE post_id = ? AND user_id = ?",
            [$post_id, $user_id]
        )->fetch();

        if ($existing) {
            if ($existing['reaction'] === $type) {
                $this->db->query(
                    "DELETE FROM post_reactions WHERE id = ?",
                    [$existing['id']]
                );
                return 'removed';
            } else {
                $this->db->query(
                    "UPDATE post_reactions SET reaction = ? WHERE id = ?",
                    [$type, $existing['id']]
                );
                return 'changed';
            }
        } else {
            $this->db->query(
                "INSERT INTO post_reactions (post_id, user_id, reaction) 
                 VALUES (?, ?, ?)",
                [$post_id, $user_id, $type]
            );
            return 'added';
        }
    }
}