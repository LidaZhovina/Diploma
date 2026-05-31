<?php

// Автоматически определяем, где запущен сайт: на ПК или на сервере Beget
if (in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || $_SERVER['SERVER_NAME'] === 'localhost') {
    
    // 1. НАСТРОЙКИ ДЛЯ ВАШЕГО КОМПЬЮТЕРА (Локальные)
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=Sanatorium',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ];

} else {
    
    // 2. НАСТРОЙКИ ДЛЯ СЕРВЕРА BEGET (Рабочие)
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=127.0.0.1;dbname=lidia20_sanatori',
        'username' => 'lidia20_sanatori',
        'password' => 'lidia20_sanatori_!@!',
        'charset' => 'utf8',

        // Включаем кэширование схемы для боевого сервера (ускоряет работу Yii2)
        'enableSchemaCache' => true,
        'schemaCacheDuration' => 60,
        'schemaCache' => 'cache',
    ];
}
