<?php

class Calculator
{
    private $rate1;
    private $rate2;
    private $input;

    public function __construct($input, $rate1, $rate2){
        $this->input = $input;
        $this->rate1 = $rate1;
        $this->rate2 = $rate2;
    }

    function convert():float {
        if($this->input > 0) {
            if ($this->rate2 != 0) {
                 $result = $this->input * ($this->rate1 / $this->rate2);
                return number_format($result, 2);
            } else {
                throw new Exception("Error: Division by 0.");
            }
        } else {
            throw new Exception("Error: Input must be greater than 0");
        }
    }

}
