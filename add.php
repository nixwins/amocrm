<?php

include_once('lib.php');

$contacts = getAllContactWithoutLeads(true);
$response = createTaskWithContacts($contacts, "Контакт без сделок", 1626762001);

$tasks = isset($response->_embedded->tasks) ? $response->_embedded->tasks : [];

if (count($tasks) > 0) {
    foreach ($tasks as $task) {
        $href = $task->_links->self->href;
        echo "<a href='" . $href . "'>" . $task->id . "</a>";
        echo "<br/>";
    }
} else {
    echo "<h2>Задачи не были созданы. Ошибка</h2>";
}
