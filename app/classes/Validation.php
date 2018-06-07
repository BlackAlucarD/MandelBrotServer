<?php

    class Validation {

        /**
         * Validate the given parsed body which should be POST as json/xml
         *
         * @param array $param
         *
         * @return array
         */
        public static function validateParam($param = array()) {

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