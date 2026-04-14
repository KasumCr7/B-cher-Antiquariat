<?php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'books');
define('DB_USER', getenv('DB_USER') ?: 'rundb');
define('DB_PASS', getenv('DB_PASS') ?: 'runpass');
define('BASE_URL', getenv('BASE_URL') !== false ? getenv('BASE_URL') : '/Antiquariat-Kassius');
