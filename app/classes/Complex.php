<?php
class Complex {

    public $real      = 0;
    public $imaginary = 0;

    public function product($other) {
        $result            = new Complex();
        $result->real      = ($this->real*$other->real) - ($this->imaginary*$other->imaginary);
        $result->imaginary = ($this->real*$other->imaginary) + ($this->imaginary*$other->real);
        return $result;
    }

    public function sum($other) {
        $result            = new Complex();
        $result->real      = $this->real+$other->real;
        $result->imaginary = $this->imaginary+$other->imaginary;
        return $result;
    }

    public function euclidean() {
                return sqrt(pow($this->real, 2)+pow($this->imaginary, 2));
    }
}