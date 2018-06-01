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

    public function mandelBrotAction(Request $request, Response $response, $args) {

        $params = $request->getParsedBody();

        $error = $this->validateParam($params);
        if(!$error['valid']){
            $this->response       = array('Parameter invalid' => $error['Error']);
            $this->responseStatus = 400;
            return false;
        }

        // Give the system 5 minutes time to calc
        set_time_limit(300);

        $realFrom      = intval($params['realFrom']);
        $realTo        = intval($params['realTo']);
        $imaginaryFrom = intval($params['imaginaryFrom']);
        $imaginaryTo   = intval($params['imaginaryTo']);

        $maxIteration = 255;
        if(isset($params['maxIteration'])){
            $maxIteration = intval($params['maxIteration']);
        }

	    $from            = new \Complex();
	    $from->real      = $realFrom;
	    $from->imaginary = $imaginaryFrom;

	    $to            = new \Complex();
	    $to->real      = $realTo;
	    $to->imaginary = $imaginaryTo;
	    $resolution    = doubleval($params['intervall']);

		$jsonResponse = $response->withJson($this->define_set($from, $to, $resolution, $maxIteration), 200);
		return $jsonResponse;
	}

	/**
    * @param \Complex $point
    * @param int $maxIteration
    * @return int
    */
	private function in_mandelBrot(\Complex $point, $maxIteration = 255){

	    $zn            = new \Complex();
		$zn->real      = 0;
		$zn->imaginary = 0;

		for ($i=0; $i<$maxIteration; $i++) {
			$zn =$this->mandelBrot($zn, $point);
			if ($zn->euclidean() >= 2) {
				return $i;
			}
		}

		return 0;
	}

	/**
    * @param \Complex $a
    * @param \Complex $b
    * @return \Complex
    */
	private function mandelBrot(\Complex $a, \Complex $b){
	    return $a->product($a)->sum($b);
	}

	/**
    * @param \Complex $min
    * @param \Complex $max
    * @param float $resolution
    * @param int $maxIteration
    * @return array
    */
    private function define_set(\Complex $min, \Complex $max, $resolution, $maxIteration = 255) {

		$set = array();
		$real_range      = range($min->real, $max->real, $resolution);
		$imaginary_range = range($min->imaginary, $max->imaginary, $resolution);

		foreach ($real_range as $real) {
		    // Uncomment this for multiple dimension array as return
			//$set[$real] = array();
			foreach ($imaginary_range as $imaginary) {
				$current            = new \Complex();
				$current->real      = $real;
				$current->imaginary = $imaginary;

				// Uncomment this for multiple dimension arr
				//$set[round($real, 7).""][round($imaginary, 7).""] = $this->in_mandelBrot($current, $escape_depth);

				$set[] = $this->in_mandelBrot($current, $maxIteration);
			}
		}
		return $set;
	}

	/**
    * @param array $param
    * @return array
    */
	private function validateParam($param = array()){

        if(empty($param) || !isset($param['realTo'])
        || !isset($param['imaginaryTo']) || !isset($param['realFrom'])
        || !isset($param['imaginaryFrom']) || !isset($param['intervall'])) {
            return array('Error' => 'Empty or Parameters not set.', "valid" => false);
        }

        if(!is_numeric($param['realTo']) || !is_numeric($param['realFrom'])
        || !is_numeric($param['imaginaryTo']) || !is_numeric($param['imaginaryFrom'])){
            return array('Error' => 'Parameter is not numberic.', "valid" => false);
        }

        return array('Error' => false, "valid" => true);
	}
}
