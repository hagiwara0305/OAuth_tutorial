<?php
include_once __DIR__ . './vendor/autoload.php';

session_start();

// googleのOAuthのアレ
$client = new Google_Client();
$client->setAuthConfig('client_credentials.json');
$client->addScope(Google_Service_Drive::DRIVE);

$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$client->setRedirectUri($redirect_uri);

// sessionを確立、もしくはOAuthを使うためのURLを生成
if (isset($_GET['code']) && !isset($_SESSION['login_token'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $_SESSION['login_token'] = $token;
    }
} else {
    $authUrl = $client->createAuthUrl();
}

// ログアウト
if (isset($_REQUEST['logout'])) {
    unset($_SESSION['login_token']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>OAuth_tutorial</title>
</head>

<body>
    <?php if (!isset($_SESSION['login_token'])) : ?>
        <div class="request">
            <a class='login' href='<?= $authUrl ?>'>Connect Me!</a>
        </div>
    <?php elseif (isset($_SESSION['login_token'])) : ?>
        <div>
            <p>success!!</p>
            <a href="?logout">Logout...</a>
            <p><?php echo json_encode($_SESSION['login_token']) ?></p>
        </div>
    <?php endif ?>
</body>

</html>