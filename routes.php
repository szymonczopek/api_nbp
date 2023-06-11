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
    $codes[0] = $database->getCodes();

    header('Content-Type: application/json');
    echo json_encode($codes);
}
if(filter_input(INPUT_GET, 'page') === 'convert') {
    $inputCalculatorFloat = filter_input(INPUT_GET, 'inputCalculator', FILTER_VALIDATE_FLOAT);

    if($inputCalculatorFloat > 0 && $inputCalculatorFloat !== false){
        $database = new Database("localhost", "root", "", "api_nbp");
        $mid1 = $database->getMid($_GET['code1']);
        $mid2 = $database->getMid($_GET['code2']);

        $id1 = $database->getId($_GET['code1']);
        $id2 = $database->getId($_GET['code2']);

        $calculator = new Calculator($inputCalculatorFloat,$mid1, $mid2);

        try {
            $result = $calculator->convert();
            if (is_float($result)) {
                $database->addHistory($inputCalculatorFloat, $result,$id1, $id2);
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
$database->closeConnection();



?>