<?php
include_once('lib.php');

if (!is_auth()) {

    $code = isset($_GET['code']) ? $_GET['code'] : null;

    if ($code) {
        auth($code);
    }
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
        <script class="amocrm_oauth" charset="utf-8" data-client-id="3894b092-bee0-493e-96f9-3bf53f614b6d" data-title="Button" data-compact="false" data-class-name="className" data-color="default" data-state="state" data-error-callback="functionName" data-mode="post_message" src="https://www.amocrm.ru/auth/button.min.js"></script>
    <?php endif; ?>
</body>

</html>