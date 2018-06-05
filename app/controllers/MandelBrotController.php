<?php

    namespace App\Controllers;

    use Psr\Container\ContainerInterface;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    require __DIR__ . '/../../vendor/autoload.php';

    class MandelBrotController {

        protected $container;

        public function __construct(ContainerInterface $container) {
            $this->container = $container;
        }

        /**
         * The controller for the route /
         *
         * @param Request  $request
         * @param Response $response
         * @param          $args
         *
         * @return Response
         */
        public function mandelBrotAction(Request $request, Response $response, $args) {

            $params = $request->getParsedBody();
            $error  = $this->validateParam($params);
            if(!$error['valid']) {
                $errorResponse = $response->withJson($error['Error'],400);
                return $errorResponse;
            }

            // Give the system UNLIMITED TIME
            set_time_limit(0);
            ini_set('max_input_time', 0);
            // and UNLIMITED POWER (memory)
            ini_set('memory_limit', -1);

            $realFrom      = doubleval($params['realFrom']);
            $realTo        = doubleval($params['realTo']);
            $imaginaryFrom = doubleval($params['imaginaryFrom']);
            $imaginaryTo   = doubleval($params['imaginaryTo']);
            $resolution    = doubleval($params['interval']);

            $maxIteration = 255;
            if(isset($params['maxIteration'])) {
                $maxIteration = intval($params['maxIteration']);
            }

            $from            = new \Complex();
            $from->real      = $realFrom;
            $from->imaginary = $imaginaryFrom;

            $to            = new \Complex();
            $to->real      = $realTo;
            $to->imaginary = $imaginaryTo;

            $jsonResponse = $response->withJson(array("response" => $this->define_set($from, $to, $resolution, $maxIteration)), 200);
            return $jsonResponse;
        }

        /**
         * The controller for the route /multi
         *
         * @param Request  $request
         * @param Response $response
         * @param          $args
         *
         * @return Response
         */
        public function mandelBrotMultiAction(Request $request, Response $response, $args) {

            $params = $request->getParsedBody();
            $error  = $this->validateParam($params);
            if(!$error['valid']) {
                $errorResponse = $response->withJson($error['Error'],400);
                return $errorResponse;
            }

            // Give the system UNLIMITED TIME
            set_time_limit(0);
            ini_set('max_input_time', 0);
            // and UNLIMITED POWER (memory)
            ini_set('memory_limit', -1);

            $realFrom      = doubleval($params['realFrom']);
            $realTo        = doubleval($params['realTo']);
            $imaginaryFrom = doubleval($params['imaginaryFrom']);
            $imaginaryTo   = doubleval($params['imaginaryTo']);
            $resolution    = doubleval($params['interval']);

            $maxIteration = 255;
            if(isset($params['maxIteration'])) {
                $maxIteration = intval($params['maxIteration']);
            }

            $from            = new \Complex();
            $from->real      = $realFrom;
            $from->imaginary = $imaginaryFrom;

            $to            = new \Complex();
            $to->real      = $realTo;
            $to->imaginary = $imaginaryTo;

            $jsonResponse = $response->withJson(array("response" => $this->define_set($from, $to, $resolution, $maxIteration, true)), 200);
            return $jsonResponse;
        }

        /**
         * Check if a Complex number is in the mandelbrot set
         *
         * @param \Complex $point
         * @param int      $maxIteration
         *
         * @return int
         */
        private function in_mandelBrot(\Complex $point, $maxIteration = 255) {

            $zn            = new \Complex();
            $zn->real      = 0;
            $zn->imaginary = 0;

            for($i = 0; $i < $maxIteration; $i++) {
                $zn = $this->mandelBrot($zn, $point);
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
        private function mandelBrot(\Complex $z, \Complex $c) {
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
        private function define_set(\Complex $min, \Complex $max, $resolution, $maxIteration = 255, $extended = false) {

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

                    if($extended){
                        $set[round($real, 7).""][round($imaginary, 7).""] = $this->in_mandelBrot($current, $maxIteration);
                    } else {
                        $set[] = $this->in_mandelBrot($current, $maxIteration);
                    }
                    $y++;
                }
                $x++;
            }

            return $set;
        }

        /**
         * Validate the given parsed body which should be POST as json/xml
         *
         * @param array $param
         *
         * @return array
         */
        private function validateParam($param = array()) {

            // check id they are set (required for the calculations)
            if(empty($param) || !isset($param['realTo']) || !isset($param['imaginaryTo']) || !isset($param['realFrom']) || !isset($param['imaginaryFrom']) || !isset($param['interval'])) {
                return array(
                      'Error' => 'Empty or Parameters not set.',
                      "valid" => false
                );
            }

            // check if the parsed body is an array, if not then it isn't a json/xml
            if(!is_array($param)) {
                return array(
                      'Error' => 'The object which is parse into the body is not an xml/json.',
                      'valid' => false
                );
            }

            // check for numeric
            if(!is_numeric($param['realTo']) || !is_numeric($param['realFrom']) || !is_numeric(
                        $param['imaginaryTo']
                  ) || !is_numeric($param['imaginaryFrom'])) {
                return array(
                      'Error' => 'Parameter is not numberic.',
                      "valid" => false
                );
            }

            // everything is fine
            return array(
                  'Error' => false,
                  "valid" => true
            );
        }
    }
