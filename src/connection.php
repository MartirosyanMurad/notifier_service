<?php
declare(strict_types=1);

/**
 * @param int $tryCount
 * @return PDO
 */
function getPDOConnection(int $tryCount = 0): PDO
{
    try {
        $host = HOST_NAME;
        $port = PORT;
        $dbname = DB_NAME;
        switch (TYPE) {
            case MYSQL:
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
                break;
            case PGSQL:
                $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
                break;
            case MSSQL:
                $dsn = "sqlsrv:Server=$host,$port;Database=$dbname";
                break;
            default:
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
        };

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        return new PDO($dsn, USER_NAME, PASSWORD, $options);
    } catch (PDOException $e) {
        $tryCount++;
        if ($tryCount < 3) {
            return getPDOConnection($tryCount);
        }

        throw $e;
    }
}

