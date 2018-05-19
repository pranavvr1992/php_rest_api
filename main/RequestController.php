<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RequestController
 *
 * @author pvr-admin
 */
class RequestController extends RequestDispatcher {

    public function __construct() {
        parent::__construct();
    }

    public function generateToken() {
        $email = $this->validateParameter('email', $this->param['email'], STRING);
        $pass = $this->validateParameter('pass', $this->param['pass'], STRING);
        echo 'generate token';
    }

    public function createUser() {
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $email = $this->validateParameter('email', $this->param['email'], STRING, false);
        $mobile = $this->validateParameter('mobile', $this->param['mobile'], STRING, false);
        try {
            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $user->setMobile($mobile);
            $user->setCreatedOn(date('Y-m-d'));
            $user->setRole("user");
            $user->setRole("active");
            if ($user->create()) {
                $this->returnResponse(SUCCESS_RESPONSE, "User created");
            } else {
                $this->returnResponse(SUCCESS_RESPONSE, "Failed to create user");
            }
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
    }

}
