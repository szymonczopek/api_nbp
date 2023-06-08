<?php

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
$ratesAll[] = array('message'=> 'Zaimportowano pomyślnie.');
header('Content-Type: application/json');

echo json_encode($ratesAll);

?>