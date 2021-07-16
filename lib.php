<?php
function apiCall($method, $url, $params, $headers = null)
{
    $ch = curl_init();

    switch ($method) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, false);
            if ($params)   curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            break;
    }

    if ($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $api_response_json = curl_exec($ch);

    curl_close($ch);

    return json_decode($api_response_json);
}

function auth($code)
{
    $configs = include_once('config.php');

    $params = array(
        'client_id' => $configs['CLIENT_ID'],
        'client_secret' => $configs['CLIENT_SECRET'],
        'grant_type' => $configs['GRANT_TYPE'],
        'code' => $code,
        'redirect_uri' => $configs['REDIRECT_URI']
    );
    return apiCall('POST', $configs['API_URL'] . '/oauth2/access_token', $params);
}
function is_auth()
{
    return isset($_COOKIE['access_token']) ? true : false;
}

function getAllContactWithoutLeads($onlyIds = false)
{
    $access_token = isset($_COOKIE['access_token']) ? $_COOKIE['access_token'] : null;

    $contacts = [];
    if ($access_token) {

        $contactsResponse = apiCall('GET',  'https://halaulilau.amocrm.ru/api/v4/contacts?with=leads', false, array('Authorization: Bearer ' . $access_token));
        foreach ($contactsResponse->_embedded->contacts as $contact) {

            $leads = $contact->_embedded->leads;
            if (count($leads) == 0 && !$onlyIds) {
                array_push($contacts, $contact);
            }

            if (count($leads) == 0 && $onlyIds) {
                array_push($contacts, extractIdFromContact($contact));
            }
        }
    }

    return $contacts;
}

function extractIdFromContact($contact)
{
    return $contact->id;
}

function createTaskWithContacts($contacts, $taskText, $complete_till)
{

    $access_token = isset($_COOKIE['access_token']) ? $_COOKIE['access_token'] : null;

    $headers = array('Authorization: Bearer ' . $access_token);

    $params = [];

    for ($i = 0; $i < count($contacts); $i++) {
        array_push(
            $params,
            array(
                "task_type_id" => 1,
                "text" => $taskText,
                "complete_till" => $complete_till,
                "entity_id" => $contacts[$i],
                'entity_type' => 'contacts'
            )
        );
    }

    $addTasksResponse = null;

    if ($access_token) {
        $addTasksResponse = apiCall('POST',  'https://halaulilau.amocrm.ru/api/v4/tasks', json_encode($params), $headers);
    }

    return $addTasksResponse;
}
