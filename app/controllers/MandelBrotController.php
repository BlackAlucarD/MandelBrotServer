<?php

    namespace App\Controllers;

    use Psr\Container\ContainerInterface;
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    require __DIR__ . '/../../vendor/autoload.php';

    class MandelBrotController {

        const OK = 200;
        const ACCEPTED = 202;
        const BAD_REQUEST = 400;
        const UNAUTHORIZED = 401;
        const FORBIDDEN = 403;
        const NOT_FOUND = 404;
        const METHOD_NOT_ALLOWED = 405;

        protected $container;

        public function __construct(ContainerInterface $container) {
            $this->container = $container;
        }

        /**
         * The controller for the route /
         *
         * @param Request  $request
         * @param Response $response
         *
         * @return Response
         */
        public function mandelBrotAction(Request $request, Response $response) {

            $params = $request->getParsedBody();
            $error  = \Validation::validateParam($params);
            if(!$error['valid']) {
                $errorResponse = $response->withJson($error['Error'], self::BAD_REQUEST);
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

            $jsonResponse = $response->withJson(
                  array( "response" => \MandelBrotCalc::define_set($from, $to, $resolution, $maxIteration) ),
                  self::OK
            );
            return $jsonResponse;
        }

        /**
         * The controller for the route /multi
         *
         * @param Request  $request
         * @param Response $response
         *
         * @return Response
         */
        public function mandelBrotMultiAction(Request $request, Response $response) {

            $params = $request->getParsedBody();
            $error  = \Validation::validateParam($params);
            if(!$error['valid']) {
                $errorResponse = $response->withJson($error['Error'], self::BAD_REQUEST);
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

            $jsonResponse = $response->withJson(
                  array( "response" => \MandelBrotCalc::define_set($from, $to, $resolution, $maxIteration, true) ),
                  200
            );
            return $jsonResponse;
        }
    }
