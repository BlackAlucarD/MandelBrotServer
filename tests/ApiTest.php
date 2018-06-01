<?php

    use PHPUnit\Framework\TestCase;

    class ApiTest extends TestCase {

        /**
         * @var GuzzleHttp\Client
         */
        private $http;

        public function connectClient($url, $paraArray = array()) {

            foreach($paraArray as $key => $para) {
                $url .= "&" . $key . "=" . $para;
            }

            $this->http = new GuzzleHttp\Client([ 'base_uri' => $url ]);
        }

        public function closeClient() {
            $this->http = null;
        }

        public function testGet() {
            $url       = "http://c-numbers.me?RESTurl=api";
            $paraArray = array(
                  'real'       => '-2',
                  'imaginary'  => '-2',
                  'bsize'      => '3',
                  'resolution' => '0.2'
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