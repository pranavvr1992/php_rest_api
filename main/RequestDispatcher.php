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
    protected $serviceName;
    protected $param;
    public $dbCon;

    public function __construct() {
//        Check HTTP Method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->throwError(REQUEST_METHOD_NOT_VALID, "Request method not valid");
        }
//        Read RAW Data
        $fileHandler = fopen('php://input', 'r');
        $this->request = stream_get_contents($fileHandler);
        $this->validateRequest();
        $dbCon = new DbCon();
        $this->dbCon = $dbCon->getConnection();
    }

    public function processRequest() {
        $reqController = new RequestController();
        $reflectionMethod = new ReflectionMethod('API', $this->serviceName);
        if (!method_exists($api, $this->serviceName)) {
            $this->throwError(API_DOST_NOT_EXIST, "Endpoint doesnot exists");
        }
        $reflectionMethod->invoke($api);
    }

    public function validateRequest() {

        if ($_SERVER["CONTENT_TYPE"] !== 'application/json') {
            $this->throwError(REQUEST_CONTENT_TYPE_NOT_VALID, "Request content type not valid");
        }
        $data = json_decode($this->request, true);
//        checks name 
        if (!isset($data['name']) || $data['name'] == "") {
            $this->throwError(API_NAME_REQUIRED, "Api name required");
        }
        $this->serviceName = $data['name'];
//checks params
        if (!isset($data['param']) || !is_array($data['param'])) {
            $this->throwError(API_PARAM_REQUIRED, "Api param required");
        }
        $this->param = $data['param'];
    }

//Throw Error Custom Method Custom
    public function throwError($httpStatusCode, $message) {
        header("content-type:application/json");
        $errorMsg = json_encode(array('error' => array('status' => $httpStatusCode, 'message' => $message)));
        echo $errorMsg;
        exit;
    }

}
