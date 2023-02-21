<?php
declare(strict_types=1);

# database
const MYSQL = 'mysql';
const PGSQL = 'pgsql';
const MSSQL = 'mssql';
const DB_TYPE = [
    MYSQL => MYSQL,
    PGSQL => PGSQL,
    MSSQL => MSSQL,
];
define('HOST_NAME', getenv('HOST_NAME', ) ?: '');
define('PORT', (int)(getenv('PORT') ?: 3306));
define('USER_NAME', getenv('USER_NAME') ?: 'user');
define('PASSWORD', getenv('PASSWORD') ?: 'pass');
define('DB_NAME', getenv('DB_NAME') ?: 'db_name');
define('TYPE', DB_TYPE[getenv('DB_TYPE')] ?? MYSQL);

# business logic config
define('SEND_NOTIFICATION_DAYS_COUNT', (int)(getenv('SEND_NOTIFICATION_DAYS_COUNT') ?: 3));
define('EMAIL_FROM', (getenv('EMAIL_FROM') ?: 'noreply@example.com'));

# available scripts
const CHECK_USERS_EMAIL_SCRIPT = 'check_users_email';
const SEND_SUBSCRIPTION_END_NOTIFICATION_FOR_USERS_SCRIPT = 'send_notification_for_users';
const SCRIPTS = [
    CHECK_USERS_EMAIL_SCRIPT,
    SEND_SUBSCRIPTION_END_NOTIFICATION_FOR_USERS_SCRIPT,
];