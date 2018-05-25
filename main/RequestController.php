<?php

/**
 * Description of RequestController
 *
 * @author pvr-admin
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Firebase\JWT\JWT;

class RequestController extends RequestDispatcher {

// create a log channel
    private $log;

    public function __construct() {
        $this->log = new Logger('RequestController');
        $appProperties = parse_ini_file(parse_ini_file("application.ini")["propFile"]);
        $this->log->pushHandler(new StreamHandler($appProperties["log_file_path"], Logger::WARNING));
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
            $this->log->info('Login Success');
            $this->returnResponse(SUCCESS_RESPONSE, $data);
        } else {
            $this->log->warning('Failed to login, Invalid credentials');
            $this->returnResponse(SUCCESS_RESPONSE, "Failed to login, Invalid credentials");
        }
    }

    public function createUser() {
        $name = $this->validateParameter('name', $this->param['name'], STRING, false);
        $email = $this->validateParameter('email', $this->param['email'], STRING, false);
        $mobile = $this->validateParameter('mobile', $this->param['mobile'], STRING, false);
        $password = $this->validateParameter('passwd', $this->param['passwd'], STRING, false);
        try {
//Password Hash, Using salt
            $options = [
                'cost' => 11,
                'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
            ];
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, $options);
            $user = new User();
            $user->setName($name);
            $user->setMobile($mobile);
            $user->setEmail($email);
            $user->setPassword($hashed_password);
            $user->setCreatedAt(date('Y-m-d H:i:s'));
            $user->setRole("user");
            $user->setStatus("active");
            if ($user->create()) {
                $this->returnResponse(SUCCESS_RESPONSE, "User created");
            } else {
                $this->returnResponse(SUCCESS_RESPONSE, "Failed to create user");
            }
        } catch (Exception $e) {
            $log->error($e);
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

    public function updateUser() {
        $id = $this->validateParameter('id', $this->param['id'], INTEGER, true);
        $email = $this->validateParameter('email', $this->param['email'], STRING, true);
        $mobile = $this->validateParameter('mobile', $this->param['mobile'], STRING, true);

        try {
            $user = new User();
            $user->setId($id);
            $user->setMobile($mobile);
            $user->setEmail($email);
            if ($user->update()) {
                $this->returnResponse(SUCCESS_RESPONSE, "User updated");
            } else {
                $this->returnResponse(SUCCESS_RESPONSE, "Failed to update user");
            }
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
