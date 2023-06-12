<?php
require 'controllers/Database.php';
require 'controllers/NBPAPI.php';
require 'controllers/Calculator.php';

$database = new Database("localhost", "root", "", "api_nbp");

if(filter_input(INPUT_GET, 'page') === 'getAll'){
    $nbpApi = new NBPAPI();
    $ratesAll = $nbpApi->getAll();
    $database->addOrUpdateRecord($ratesAll[0]['rates']);
    $database->addPln();
    $ratesAll[] = ['message'=> 'Successfully import.'];

    header('Content-Type: application/json');
    echo json_encode($ratesAll);
}
if(filter_input(INPUT_GET, 'page') === 'getCodes'){
    $codes[0] = $database->getRatesCodes();

    header('Content-Type: application/json');
    echo json_encode($codes);
}
if(filter_input(INPUT_GET, 'page') === 'convert') {
    $inputCalculatorFloat = filter_input(INPUT_GET, 'inputCalculator', FILTER_VALIDATE_FLOAT);

    if($inputCalculatorFloat > 0 && $inputCalculatorFloat !== false){
        $database = new Database("localhost", "root", "", "api_nbp");
        $mid1 = $database->getRateMid($_GET['code1']);
        $mid2 = $database->getRateMid($_GET['code2']);

        $id1 = $database->getRateId($_GET['code1']);
        $id2 = $database->getRateId($_GET['code2']);

        $calculator = new Calculator($inputCalculatorFloat,$mid1, $mid2);

        try {
            $result = $calculator->convert();
            if (is_float($result)) {
                $database->addHistoryRow($inputCalculatorFloat, $result,$id1, $id2);
            }
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    } else {
        $result = 'Input must be number > 0';
    }

    header('Content-Type: application/json');
    echo json_encode($result);
}

if(filter_input(INPUT_GET, 'page') === 'getHistory') {
    $history = $database->getHistory();

    header('Content-Type: application/json');
    echo json_encode($history);
}

$database->closeConnection();



?>