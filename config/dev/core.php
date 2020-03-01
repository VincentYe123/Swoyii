<?php

// Notice!!!
// Be careful if you wanna edit this file

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

defined('APP_ID') or define('APP_ID', 10000);
defined('APP_NAME') or define('APP_NAME', 'swoyii');

defined('HASH_ID_KEY') or define('HASH_ID_KEY', 'swoyii');
defined('HASH_ID_LENGTH') or define('HASH_ID_LENGTH', 10); //unit:second

defined('JWT_KEY') or define('JWT_KEY', 'swoyii');
defined('JWT_DURATION') or define('JWT_DURATION', 3600 * 7 * 24); //unit:second
