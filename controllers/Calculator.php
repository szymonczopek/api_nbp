<?php

class Calculator
{
    private $currency1;
    private $currency2;
    private $input;

    public function __construct($currency1, $currency2, $input){
        $this->currency1 = $currency1;
        $this->currency2 = $currency2;
        $this->input = $input;
    }
    function convert() {
        if($this->input > 0) {
            if ($this->currency2 != 0) {
                return $this->input * ($this->currency1 / $this->currency2);
            } else {
                throw new Exception("Error: Division by 0.");
            }
        } else {
            throw new Exception("Error: Input must be greater than 0");
        }
    }

}