<?php
require 'controllers/Database.php';
require 'controllers/NBPAPI.php';

$page = filter_input(INPUT_GET, 'page');

if ($page !== null && $page === 'getAll') {
    $nbpApi = new NBPAPI();
    $ratesAll = $nbpApi->getAll();

    $database = new Database("localhost", "root", "", "api_nbp");
    $database->addOrUpdateRecord($ratesAll[0]['rates']);
    $database->closeConnection();

    $ratesAll[] = ['message'=> 'Successfully import.'];
    header('Content-Type: application/json');

    echo json_encode($ratesAll);
}

?>