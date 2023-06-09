<?php
require 'controllers/Database.php';
require 'controllers/NBPAPI.php';

$page = filter_input(INPUT_GET, 'page');

    try {
        switch ($page) {
            case 'getAll':
                $nbpApi = new NBPAPI();
                $ratesAll = $nbpApi->getAll();

                $database = new Database("localhost", "root", "", "api_nbp");
                $database->addOrUpdateRecord($ratesAll[0]['rates']);
                $database->closeConnection();

                $ratesAll[] = ['message'=> 'Successfully import.'];
                header('Content-Type: application/json');
                echo json_encode($ratesAll);
                break;
            case 'getAllCodes':
                $database = new Database("localhost", "root", "", "api_nbp");
                $codes[0] = $database->getAllCodes();
                $codes[1] = ['message'=> 'Successfully import.'];
                header('Content-Type: application/json');
                echo json_encode($codes);
                break;

            default:
                throw new Exception('Invalid endpoint.');
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        }




?>