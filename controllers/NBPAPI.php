<?php
require '../controllers/Database.php';

class NBPAPI {
    private $urlRatesAll = 'http://api.nbp.pl/api/exchangerates/tables/A?format=json';

    public function getAll() {
        $url = $this->urlRatesAll;
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        return $data;
    }
}

    $nbpApi = new NBPAPI();
    $ratesAll = $nbpApi->getAll();

    $db = new Database("localhost", "root", "", "api_nbp");

    // Przykładowe dane
    $data = array(
        array('currency' => 'dolar', 'code' => 'usa','mid'=>11.52),
        array('currency' => 'dolar aust', 'code' => 'aus','mid'=>0.30),

    );

    // Wywołanie metody addOrUpdateRecord()
    $db->addOrUpdateRecord($ratesAll[0]['rates']);

    // Zamknięcie połączenia
    $db->closeConnection();


    $ratesAll[] = ['message'=> 'Zaimportowano pomyślnie.'];
    header('Content-Type: application/json');

    echo json_encode($ratesAll);

?>