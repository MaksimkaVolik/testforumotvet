<?php
// Настройки БД
define('DB_HOST', 'localhost');
define('DB_USER', 'mvolikfg_2');
define('DB_PASS', 'Mvolik683');
define('DB_NAME', 'mvolikfg_2');

// Настройки сайта
define('SITE_URL', 'https://otvetforum.ru');
define('SITE_NAME', 'Ответ Форум');

// Автозагрузка классов
spl_autoload_register(function ($class) {
    require __DIR__ . '/' . $class . '.php';
});

session_start();
// Логирование ошибок
ini_set('log_errors', 1);
ini_set('error_log', __DIR__.'/../error_log.txt');