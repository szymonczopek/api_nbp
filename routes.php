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
if(filter_input(INPUT_GET, 'page') === 'getCodes'){
    $database = new Database("localhost", "root", "", "api_nbp");
    $codes[0] = $database->getAllCodes();
    $database->closeConnection();

    $codes[1] = ['message'=> 'Successfully import.'];
    header('Content-Type: application/json');
    echo json_encode($codes);
}
if(filter_input(INPUT_GET, 'page') === 'convert') {
    $inputCalculatorFloat = filter_input(INPUT_GET, 'inputCalculator', FILTER_VALIDATE_FLOAT);

    if($inputCalculatorFloat > 0 && $inputCalculatorFloat !== false){
        $database = new Database("localhost", "root", "", "api_nbp");
        $rate1 = $database->getRate($_GET['code1']);
        $rate2 = $database->getRate($_GET['code2']);
    } else {
        throw new Exception("Error: Input must be greater than 0");
    }

    header('Content-Type: application/json');
    echo json_encode($rate1.' '.$rate2.' '.$inputCalculatorFloat);
}



?>