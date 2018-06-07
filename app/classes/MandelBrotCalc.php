<?php

    require __DIR__ . '/../../vendor/autoload.php';

    class MandelBrotCalc {

        /**
         * Check if a Complex number is in the mandelbrot set
         *
         * @param \Complex $point
         * @param int      $maxIteration
         *
         * @return int
         */
        private static function in_mandelBrot(\Complex $point, $maxIteration = 255) {

            $zn            = new \Complex();
            $zn->real      = 0;
            $zn->imaginary = 0;

            for($i = 0; $i < $maxIteration; $i++) {
                $zn = self::mandelBrot($zn, $point);
                if($zn->euclidean() >= 2) {
                    return $i;
                }
            }

            return $i;
        }

        /**
         * Calculate the mandelbrot z_n+1 = z^2_n + c
         *
         * @param \Complex $z
         * @param \Complex $c
         *
         * @return \Complex
         */
        private static function mandelBrot(\Complex $z, \Complex $c) {
            return $z->product($z)->sum($c);
        }

        /**
         * This function defines the mandelbrot set
         * $extended is for response set as an multi dimension array
         *
         * @param \Complex $min
         * @param \Complex $max
         * @param double   $resolution
         * @param int      $maxIteration
         * @param bool     $extended
         *
         * @return array
         */
        public static function define_set(
              \Complex $min,
              \Complex $max,
              $resolution,
              $maxIteration = 255,
              $extended = false
        ) {

            $set             = array();
            $real_range      = range($min->real, $max->real, $resolution);
            $imaginary_range = range($min->imaginary, $max->imaginary, $resolution);

            $x = 0;
            foreach($real_range as $real) {
                $y = 0;

                foreach($imaginary_range as $imaginary) {
                    $current            = new \Complex();
                    $current->real      = $real;
                    $current->imaginary = $imaginary;

                    if($extended) {
                        $set[$real . ""][$imaginary . ""] = self::in_mandelBrot($current, $maxIteration);
                    } else {
                        $set[] = self::in_mandelBrot($current, $maxIteration);
                    }
                    $y++;
                }
                $x++;
            }

            return $set;
        }

    }