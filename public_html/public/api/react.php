<?php
require __DIR__.'/../../core/config.php';
require __DIR__.'/../../core/Reaction.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Only POST allowed']));
}

$data = json_decode(file_get_contents('php://input'), true);
$post_id = (int)($data['post_id'] ?? 0);
$type = in_array($data['type'] ?? '', ['like', 'dislike']) ? $data['type'] : null;

if (!$post_id || !$type) {
    http_response_code(400);
    die(json_encode(['error' => 'Invalid data']));
}

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['error' => 'Unauthorized']));
}

$reaction = new Reaction();
$result = $reaction->toggleReaction($post_id, $_SESSION['user_id'], $type);

echo json_encode(['status' => $result]);