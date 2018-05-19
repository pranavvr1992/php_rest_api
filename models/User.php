<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author pvr-admin
 */
class User {

    private $id;
    private $name;
    private $email;
    private $mobile;
    private $password;
    private $createdOn;
    private $lastLoginOn;
    private $lastLoginIp;
    private $role;
    private $status;
    private $tableName = 'users';
    private $dbCon;

    public function __construct() {
        $db = new DbCon();
        $this->dbCon = $db->getConnection();
    }

    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getEmail() {
        return $this->email;
    }

    function getMobile() {
        return $this->mobile;
    }

    function getPassword() {
        return $this->password;
    }

    function getCreatedOn() {
        return $this->createdOn;
    }

    function getLastLoginOn() {
        return $this->lastLoginOn;
    }

    function getLastLoginIp() {
        return $this->lastLoginIp;
    }

    function getRole() {
        return $this->role;
    }

    function getStatus() {
        return $this->status;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setMobile($mobile) {
        $this->mobile = $mobile;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
    }

    function setLastLoginOn($lastLoginOn) {
        $this->lastLoginOn = $lastLoginOn;
    }

    function setLastLoginIp($lastLoginIp) {
        $this->lastLoginIp = $lastLoginIp;
    }

    function setRole($role) {
        $this->role = $role;
    }

    function setStatus($status) {
        $this->status = $status;
    }


// CRUD Operations


    public function create() {
        $sql = "INSERT INTO " . $this->tableName . " (name,mobile,email,passwd,created_on,last_login_on,last_login_ip,role,status) VALUES(:name,:mobile,:email,:password,:createdOn,null,null,:role,:status)";
        $stmt = $this->dbCon->prepare($sql);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":mobile", $this->mobile);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":createdOn", $this->createdOn);
        $stmt->bindParam(":role", $this->role);
        $stmt->bindParam(":status", $this->status);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
