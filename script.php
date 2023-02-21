<?php
declare(strict_types=1);

require 'bootstrap.php';

if (!array_key_exists(1, $argv)) {
    echo "Please pass the script name." . PHP_EOL;
    exit(1);
}

if (!in_array($argv[1], SCRIPTS)) {
    echo "Please pass the correct script name. Passed {$argv[1]}." . PHP_EOL;
    exit(2);
}

$connection = getPDOConnection();

$isScriptLaunched = isScriptLaunched($connection, $argv[1]);

if ($isScriptLaunched === null) {
    echo "Script {$argv[1]} not found in database." . PHP_EOL;
    exit(3);
}

if ($isScriptLaunched === true) {
    echo "Script {$argv[1]} is already running." . PHP_EOL;
    exit(4);
}

try {
    reservingScript($connection, $argv[1]);

    if ($argv[1] === CHECK_USERS_EMAIL_SCRIPT) {
        handelUnconfirmedEmails($connection);
    } elseif ($argv[1] === SEND_SUBSCRIPTION_END_NOTIFICATION_FOR_USERS_SCRIPT) {
        handelNotNotifiedUsers($connection, EMAIL_FROM);
    }
} catch (PDOException $e) {
    echo "Get exception during running script {$argv[1]}. Code: {$e->getCode()} Message: {$e->getMessage()}."  . PHP_EOL;
} finally {
    removeReserveForScript($connection, $argv[1]);
}


