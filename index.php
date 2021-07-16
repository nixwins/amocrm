<?php
include_once('lib.php');
$configs = include_once('config.php');
$code = isset($_GET['code']) ? $_GET['code'] : null;
$params = array(
    'client_id' => $configs['CLIENT_ID'],
    'client_secret' => $configs['CLIENT_SECRET'],
    'grant_type' => $configs['GRANT_TYPE'],
    'code' => $code,
    'redirect_uri' => $configs['REDIRECT_URI']
);

$oauthResponse = apiCall('POST', $configs['API_URL'] . '/oauth2/access_token', $params);

if (isset($oauthResponse->access_token)) {
    setcookie("access_token", $oauthResponse->access_token);
}

?>
<html>

<head>

</head>

<body>
    <?php if (is_auth()) : ?>

        <h1>Контакты без сделок</h1>
        <ul>
            <?php
            $contacts = getAllContactWithoutLeads();
            foreach ($contacts as $contact) {

                echo "<li> <a href='/contact.php?contact_id=" . $contact->id . "'>" . $contact->name . "</a></li>";
            }

            ?>
        </ul>
        <a href="/add.php">Добавить всем задачу</a>

    <?php else : ?>
        <h1>Для просмотра необходима авторизация</h1>
        <script class="amocrm_oauth" charset="utf-8" data-client-id="3894b092-bee0-493e-96f9-3bf53f614b6d" data-title="Button" data-compact="false" data-class-name="className" data-color="default" data-state="state" data-error-callback="functionName" data-mode="popup" src="https://www.amocrm.ru/auth/button.min.js"></script>
    <?php endif; ?>
</body>

</html>