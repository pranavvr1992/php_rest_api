<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IpFinder
 *
 * @author pvr-admin
 */
class IpFinder {

    public function getClientIp() {
        $ipAddr = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipAddr = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipAddr = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipAddr = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipAddr = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipAddr = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipAddr = getenv('REMOTE_ADDR');
        else
            $ipAddr = 'UNKNOWN';
        return $ipAddr;
    }

}
