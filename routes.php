<?php
include 'controllers/Database.php';
include 'controllers/NBPAPI.php';

if (filter_input(INPUT_GET, 'page', FILTER_SANITIZE_STRING) === 'getAll') {
    $nbpApi = new NBPAPI();
    $ratesAll = $nbpApi->getAll();

    $db = new Database("localhost", "root", "", "api_nbp");

    $db->addOrUpdateRecord($ratesAll[0]['rates']);

    $db->closeConnection();

    $ratesAll[] = ['message'=> 'Successfully import.'];
    header('Content-Type: application/json');

    echo json_encode($ratesAll);
}
?>