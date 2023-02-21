<?php
declare(strict_types=1);

// load env variables
$env = parse_ini_file('.env');
foreach ($env as $key => $value) {
    putenv("$key=$value");
}

// load config
require_once 'config.php';

// load all files
require_all('src');

function require_all($dir, $depth=0, $maxDepth = 5) {
    if ($depth > $maxDepth) {
        return;
    }

    // require all php files
    $scan = glob("$dir/*");
    foreach ($scan as $path) {
        if (preg_match('/\.php$/', $path)) {
            require_once $path;
        } elseif (is_dir($path)) {
            require_all($path, $depth+1);
        }
    }
}