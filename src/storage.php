<?php
declare(strict_types=1);

/**
 * @param PDO $connection
 * @return array
 */
function getConfirmedButUncheckedValidEmail(PDO $connection): array
{
    $query = "SELECT u.username, e.id, e.email FROM users u JOIN emails e ON u.email_id = e.id WHERE u.confirmed = 1 AND e.checked = 0";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param PDO $connection
 * @param int $id
 * @return void
 */
function setCheckedForEmailById(PDO $connection, int $id, int $isValid): void
{
    $query = "UPDATE emails SET checked = 1, valide = :is_valid WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':is_valid', $isValid);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

/**
 * @param PDO $connection
 * @param int $sendNotificationDaysCount
 * @return array
 */
function getUsersToSendNotification(PDO $connection, int $sendNotificationDaysCount): array
{
    $query = "
            SELECT 
                u.id,
                u.username, 
                e.email 
            FROM 
                users u 
            JOIN emails e ON u.email_id = e.id 
            WHERE 
                e.valid = 1 
              AND u.is_email_send=0 
              AND u.valid_ts BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL :days_count DAY)";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':days_count', $sendNotificationDaysCount);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * @param PDO $connection
 * @param int $id
 * @return void
 */
function setEmailSentForUserById(PDO $connection, int $id): void
{
    $query = "UPDATE users SET is_email_send = 1 WHERE id = :id";
    $stmt = $connection->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

/**
 * @param PDO $connection
 * @param string $scriptName
 * @return bool|null
 */
function isScriptAlreadyRunning(PDO $connection, string $scriptName): ?bool
{
    $stmt = $connection->prepare("SELECT is_running FROM scripts WHERE name = :name");
    $stmt->bindParam(':name', $scriptName);
    $stmt->execute();

    if ($stmt->fetchColumn() === false) {
        return null;
    }

    return (bool)$stmt->fetchColumn();
}

/**
 * @param PDO $connection
 * @param string $scriptName
 * @param int $isRunning
 * @return void
 */
function updateScriptIsRunningStatus(PDO $connection, string $scriptName, int $isRunning): void
{
    $stmt = $connection->prepare("UPDATE scripts SET is_running = :is_running WHERE name = :name");
    $stmt->bindParam(':name', $scriptName);
    $stmt->bindParam(':is_running', $isRunning);
    $stmt->execute();
}