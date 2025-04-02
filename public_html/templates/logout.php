<?php
require __DIR__.'/../core/config.php';
session_start();
session_destroy();
header('Location: /');
exit;