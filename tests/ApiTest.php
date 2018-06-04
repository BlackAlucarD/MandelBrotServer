<?php
    require __DIR__ . '/../vendor/autoload.php';

    use PHPUnit\Framework\TestCase;

    class ApiTest extends TestCase {

        /**
         * @throws \GuzzleHttp\Exception\GuzzleException
         */
        public function testPost() {
            // localhost forwarding
            $url = "http://c-numbers.me/";

            $paraArray1 = array(
                  'realFrom'      => '-2',
                  'realTo'        => '0',
                  'imaginaryFrom' => '0',
                  'imaginaryTo'   => '2',
                  'interval'     => '0.05',
                  'maxIteration'  => '255'
            );
            $paraArray2 = array(
                  'realFrom'      => '-2',
                  'realTo'        => '0',
                  'imaginaryFrom' => '-2',
                  'imaginaryTo'   => '0',
                  'interval'     => '0.05',
                  'maxIteration'  => '255'
            );
            $paraArray3 = array(
                  'realFrom'      => '0',
                  'realTo'        => '2',
                  'imaginaryFrom' => '0',
                  'imaginaryTo'   => '2',
                  'interval'     => '0.05',
                  'maxIteration'  => '255'
            );
            $paraArray4 = array(
                  'realFrom'      => '0',
                  'realTo'        => '2',
                  'imaginaryFrom' => '-2',
                  'imaginaryTo'   => '0',
                  'interval'     => '0.05',
                  'maxIteration'  => '255'
            );
            // Test Missing Parameters to get 400 StatusCode
            $paraArrayFail = array(
                  'realFrom' => '0',
                  'realTo'   => '2'
            );

            $paraArray = array( $paraArray1, $paraArray2, $paraArray3, $paraArray4, $paraArrayFail );

            $client = new \GuzzleHttp\Client();
            foreach($paraArray as $para) {
                // async because we have to wait for the result to be calculated
                $response = $client->postAsync(
                      $url,
                      [
                            GuzzleHttp\RequestOptions::JSON => $para
                      ]
                )->then(
                      function (\Psr\Http\Message\ResponseInterface $res) {
                          // we expect 200 OK StatusCode
                          $this->assertEquals(200, $res->getStatusCode());

                          // response should be an json
                          $contentType = $res->getHeaders()["Content-Type"][0];
                          $this->assertEquals("application/json;charset=utf-8", $contentType);
                      },
                      function (\GuzzleHttp\Exception\RequestException $e) {
                          // if error then we should get 400 ErrorCode
                          $this->assertEquals(400, $e->getResponse()->getStatusCode());
                      }
                );

                $response->wait();
            }
        }
    }