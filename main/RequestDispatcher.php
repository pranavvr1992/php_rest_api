<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RestRequestDispatcher
 *
 * @author pvr-admin
 */
class RequestDispatcher {

    protected $request;
    protected $endPoint;
    protected $param;
    public $dbCon;

    public function __construct() {
//        Check HTTP Method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->throwError(REQUEST_METHOD_NOT_ALLOWED, "Request method not allowed");
        }
//        Read RAW Data
        $fileHandler = fopen('php://input', 'r');
        $this->request = stream_get_contents($fileHandler);
        $this->validateRequest();
        $dbCon = new DbCon();
        $this->dbCon = $dbCon->getConnection();
    }

//Process Request to endpoint after validating request
    public function processRequest() {
        $reqController = new RequestController();
        try {
            $reflectionMethod = new ReflectionMethod('RequestController', $this->endPoint);
            if (!method_exists($reqController, $this->endPoint)) {
                $this->throwError(ENDPOINT_NOT_EXIST, "Endpoint doesnot exists");
            }
        } catch (Exception $exc) {
            $this->throwError(ENDPOINT_NOT_EXIST, "Endpoint doesnot exists");
        }
        $reflectionMethod->invoke($reqController);
    }

//Validate Content-Type,Endpoint,Parameters
    public function validateRequest() {
        if ($_SERVER["CONTENT_TYPE"] !== 'application/json') {
            $this->throwError(UNSUPPOERTED_CONTENT_TYPE, "Unsuppoerted content type");
        }
        $data = json_decode($this->request, true);
//        checks endpoint 
        if (!isset($data['endpoint']) || $data['endpoint'] == "") {
            $this->throwError(API_ENDPOINT_REQUIRED, "Api endpoint required");
        }
        $this->endPoint = $data['endpoint'];
//checks params
        if (!isset($data['params']) || !is_array($data['params'])) {
            $this->throwError(ENDPOINT_PARAM_REQUIRED, "Endpoint param required");
        }
        $this->param = $data['params'];
    }
//Validate parameter and its type
    public function validateParameter($fieldName, $value, $dataType, $required = true) {
        if ($required == true && empty($value) == true) {
            $this->throwError(VALIDATE_PARAMETER_REQUIRED, $fieldName . " Parameter is required");
        }
        switch ($dataType) {
            case BOOLEAN:

                if (!is_bool($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, $fieldName . "Must be boolean");
                }
                break;
            case INTEGER:

                if (!is_numeric($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, $fieldName . "Must be integer");
                }
                break;
            case STRING:

                if (!is_string($value)) {
                    $this->throwError(VALIDATE_PARAMETER_DATATYPE, $fieldName . "Must be string");
                }
                break;

            default:
                $this->throwError(VALIDATE_PARAMETER_DATATYPE, $fieldName . "Datatype is not valid");
                break;
        }
        return $value;
    }

//Throw Error Custom Method Custom
    public function throwError($httpStatusCode, $message) {
        header("content-type:application/json");
        $errorMsg = json_encode(array('error' => array('code' => $httpStatusCode, 'message' => $message)));
        echo $errorMsg;
        exit;
    }

}
