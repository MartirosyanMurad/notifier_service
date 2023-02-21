<?php
declare(strict_types=1);

function handelUnconfirmedEmails(PDO $connection): void
{
    $unConfirmedEmails = getConfirmedButUncheckedValidEmail($connection);
    $totalCount = count($unConfirmedEmails);
    echo "Total unchecked emails count $totalCount." . PHP_EOL;
    $handledCount = 0;
    foreach ($unConfirmedEmails as $confirmedEmail) {
        $isEmailValid = checkEmail($confirmedEmail['email']);
        setCheckedForEmailById($connection, $confirmedEmail['id'], $isEmailValid);
        echo "For {$confirmedEmail['username']} email {$confirmedEmail['email']} is " . ($isEmailValid ? 'valid.' : 'invalid.') . PHP_EOL;
        $handledCount++;
        if ($handledCount % 100) {
            $remain = $totalCount - $handledCount;
            echo "Total: $totalCount, Handle: $handledCount, Remain: $remain." . PHP_EOL;
        }
    }

    $remain = $totalCount - $handledCount;
    echo "Total: $totalCount, Handle: $handledCount, Remain: $remain." . PHP_EOL;
    echo "Success finished check emails process." . PHP_EOL;
}

//check_email( $email )
/**
 * @param string $email
 * @return int
 */
function checkEmail(string $email): int
{
    // email verification stub
    sleep(mt_rand(1, 60));
    return mt_rand(0, 1);
}

/**
 * @param PDO $connection
 * @param string $fromEmail
 * @return void
 */
function handelNotNotifiedUsers(PDO $connection, string $fromEmail): void
{
    $notNotifiedUsers = getUsersToSendNotification($connection, SEND_NOTIFICATION_DAYS_COUNT);
    $totalCount = count($notNotifiedUsers);
    echo "Total unchecked emails count $totalCount." . PHP_EOL;
    $handledCount = 0;
    foreach ($notNotifiedUsers as $notNotifiedUser) {
        sendEmail($notNotifiedUser['email'], $fromEmail, 'End of subscription', "{$notNotifiedUser['username']}, your subscription is expiring soon.");
        setEmailSentForUserById($connection, $notNotifiedUsers['id']);
        echo "For {$notNotifiedUser['username']} send email to {$notNotifiedUser['email']} address.". PHP_EOL;
        if ($handledCount % 100) {
            $remain = $totalCount - $handledCount;
            echo "Total: $totalCount, Handle: $handledCount, Remain: $remain." . PHP_EOL;
        }
    }

    $remain = $totalCount - $handledCount;
    echo "Total: $totalCount, Handle: $handledCount, Remain: $remain." . PHP_EOL;
    echo "Success finished notified process." . PHP_EOL;

}

//send_email( $email, $from, $to, $subj, $body)
/**
 * @param string $email
 * @param string $fromEmail
 * @param string $subj
 * @param string $body
 * @return void
 */
function sendEmail(string $email, string $fromEmail, string $subj, string $body): void
{
    // email verification stub
    sleep(mt_rand(1, 10));
}

/**
 * @param PDO $connection
 * @param string $scriptName
 * @return bool|null
 */
function isScriptLaunched(PDO $connection, string $scriptName): ?bool
{
    return isScriptAlreadyRunning($connection, $scriptName);
}

/**
 * @param PDO $connection
 * @param string $scriptName
 * @return void
 */
function reservingScript(PDO $connection, string $scriptName): void
{
    updateScriptIsRunningStatus($connection, $scriptName, 1);
}

/**
 * @param PDO $connection
 * @param string $scriptName
 * @return void
 */
function removeReserveForScript(PDO $connection, string $scriptName): void
{
    updateScriptIsRunningStatus($connection, $scriptName, 0);
}