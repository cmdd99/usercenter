<?php
/*
 *配置文件
 */
return [
	'appid'          => getenv('UC_APP_ID'),
	'appsecret'      => getenv('UC_APP_SECRET'),
	'url'            => getenv('UC_URL'),
	'debug'          => getenv('UC_DEBUG'),
	'log_path'       => getenv('UC_LOG_PATH'),
    'log_file_name'  => getenv('UC_LOG_FILE_NAME'),
    'redis_host'     => getenv('UC_REDIS_HOST') ? : '127.0.0.1',
    'redis_password' => getenv('UC_REDIS_PASSWORD') ? : '',
    'redis_port'     => getenv('UC_REDIS_PORT') ? : 6379,
    'redis_database' => getenv('UC_REDIS_DATABASE') ? : 0,
];