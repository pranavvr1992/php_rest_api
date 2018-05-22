<?php

/**
 * Description of RequestController
 *
 * @author pvr-admin
 */
class RequestController extends RequestDispatcher {

    public function __construct() {
        parent::__construct();
    }

    public function userLogin() {
        $email = $this->validateParameter('email', $this->param['email'], STRING);
        $password = $this->validateParameter('passwd', $this->param['passwd'], STRING);
        $role = $this->validateParameter('role', $this->param['role'], STRING);
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setRole($role);
        $userDetails = $user->login();
        if ($userDetails != false) {
            $payload = array('isuueedAt' => time(),
                'issuer' => 'localhost',
                'exp' => time() + (15 * 60),
                'userId' => $userDetails['id']);
            $token = JWT::encode($payload, SECRETE_KEY);
            $data = array('token' => $token);
            $this->returnResponse(SUCCESS_RESPONSE, $data);
        } else {
            $this->returnResponse(SUCCESS_RESPONSE, "Failed to login, Invalid credentials");
        }
    }

    public function createUser() {
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $email = $this->validateParameter('email', $this->param['email'], STRING, false);
        $mobile = $this->validateParameter('mobile', $this->param['mobile'], STRING, false);
        $password = $this->validateParameter('passwd', $this->param['passwd'], STRING, false);
        try {
            $user = new User();
            $user->setName($name);
            $user->setMobile($mobile);
            $user->setEmail($email);
            $user->setPassword($password);
            $user->setCreatedOn(date('Y-m-d'));
            $user->setRole("user");
            $user->setStatus("active");
            if ($user->create()) {
                $this->returnResponse(SUCCESS_RESPONSE, "User created");
            } else {
                $this->returnResponse(SUCCESS_RESPONSE, "Failed to create user");
            }
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
    }

    public function getUsers() {
        try {
            $user = new User();
            $users = $user->read();
            echo json_encode($users);
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
    }

    public function deleteUser() {
        $id = $this->validateParameter('id', $this->param['id'], INTEGER, false);
        try {
            $user = new User();
            if ($user->delete($id)) {
                $this->returnResponse(SUCCESS_RESPONSE, "User deleted");
            } else {
                $this->returnResponse(SUCCESS_RESPONSE, "Failed to delete user");
            }
        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
    }

}
