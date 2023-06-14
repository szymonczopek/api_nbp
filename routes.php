<?php
require 'controllers/Database.php';
require 'controllers/NBPAPI.php';
require 'controllers/Calculator.php';

$serverName = "eu-cdbr-west-03.cleardb.net";
$userName = "b919ae985fbd50";
$password = "f02c11f7";
$dbName = "heroku_7cc51f4f51c7b98";

/*$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "api_nbp";*/

$database = new Database($serverName, $userName, $password, $dbName);

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

    if($history === null){
        $history[] = ['message'=> 'History is empty.'];
    }
    header('Content-Type: application/json');
    echo json_encode($history);
}

$database->closeConnection();



?>