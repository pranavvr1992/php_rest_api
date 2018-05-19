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
        echo 'generate token';
    }

}
