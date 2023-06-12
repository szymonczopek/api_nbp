<?php

 class NBPAPI {
     private $urlRatesAll = 'http://api.nbp.pl/api/exchangerates/tables/A?format=json';

     public function getAll() {
         $url = $this->urlRatesAll;

         try {
             $response = file_get_contents($url);

             if (!$response) {
                 throw new Exception('Failed to fetch data from URL.');
             }
             $data = json_decode($response, true);
             if (!$data) {
                 throw new Exception('Failed to decode JSON data.');
             }
             return $data;
         } catch (Exception $e) {
             return 'Errooor: ' . $e->getMessage();
         }
     }

 }

?>