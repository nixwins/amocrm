<?php
include_once('lib.php');
$configs = include_once('config.php');
$contactID = $_GET['contact_id'];

$contactResponse = apiCall('GET', $configs['API_URL'] . '/api/v4/contacts/' . $contactID . '?with=catalog_elements', false, array('Authorization: Bearer ' . $_COOKIE['access_token']));
echo "<pre>";
print_r($contactResponse);
echo  "</pre>";
