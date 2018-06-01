<?php

    /**
     * Class Complex
     * Complex Number
     */
    class Complex {

        /**
         * real part of the complex number
         * @var double
         */
        public $real = 0.0;
        /**
         * imaginary part of the complex number
         * @var double
         */
        public $imaginary = 0.0;

        /**
         * Product of this Complex number and the param Complex number
         *
         * @param \Complex $other
         *
         * @return \Complex
         */
        public function product(\Complex $other) {
            $result            = new \Complex();
            $result->real      = ($this->real * $other->real) - ($this->imaginary * $other->imaginary);
            $result->imaginary = ($this->real * $other->imaginary) + ($this->imaginary * $other->real);
            return $result;
        }

        /**
         * Sum of this Complex number and the param Complex number
         *
         * @param \Complex $other
         *
         * @return \Complex
         */
        public function sum(\Complex $other) {
            $result            = new \Complex();
            $result->real      = $this->real + $other->real;
            $result->imaginary = $this->imaginary + $other->imaginary;
            return $result;
        }

        /**
         * Euclidean distance of this complex number
         *
         * @return float
         */
        public function euclidean() {
            return sqrt(pow($this->real, 2) + pow($this->imaginary, 2));
        }
    }