<?php
require 'controllers/Database.php';
require 'controllers/NBPAPI.php';

if(filter_input(INPUT_GET, 'page') === 'getAll'){
    $nbpApi = new NBPAPI();
    $ratesAll = $nbpApi->getAll();

    $database = new Database("localhost", "root", "", "api_nbp");
    $database->addOrUpdateRecord($ratesAll[0]['rates']);
    $database->closeConnection();

    $ratesAll[] = ['message'=> 'Successfully import.'];
    header('Content-Type: application/json');
    echo json_encode($ratesAll);
}
if(filter_input(INPUT_GET, 'page') === 'getAllCodes'){
    $database = new Database("localhost", "root", "", "api_nbp");
    $codes[0] = $database->getAllCodes();
    $codes[1] = ['message'=> 'Successfully import.'];
    header('Content-Type: application/json');
    echo json_encode($codes);
}
if(filter_input(INPUT_GET, 'page') === 'convert') {

    header('Content-Type: application/json');
    echo json_encode($_GET['currency1'].' '.$_GET['currency2'].' '.$_GET['inputCalculator']);
}



?>