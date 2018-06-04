<?php
    require __DIR__ . '/../vendor/autoload.php';

    use PHPUnit\Framework\TestCase;

    class ApiTest extends TestCase {

        /**
         * @var GuzzleHttp\Client
         */
        private $http;

        public function connectClient($url, $paraArray = array()) {

            $url .= '?';
            foreach($paraArray as $key => $para) {
                $url .= "&" . $key . "=" . $para;
            }

            $this->http = new GuzzleHttp\Client([ 'base_uri' => $url ]);
        }

        public function closeClient() {
            $this->http = null;
        }

        public function testGet() {
            $url       = "http://c-numbers.me/mandelbrot";
            $paraArray = array(
                  'realFrom'      => '-2',
                  'realTo'        => '2',
                  'imaginaryFrom' => '-2',
                  'imaginaryTo'   => '2',
                  'intervall'     => '0.05',
                  'maxIteration'  => '255'
            );
            $this->connectClient($url, $paraArray);

            $response = $this->http->request('GET', 'user-agent');

            $this->assertEquals(200, $response->getStatusCode());

            $contentType = $response->getHeaders()["Content-Type"][0];
            $this->assertEquals("application/json", $contentType);

            $userAgent = json_decode($response->getBody())->{"user-agent"};
            $this->assertRegexp('/Guzzle/', $userAgent);

            $this->closeClient();
        }
    }