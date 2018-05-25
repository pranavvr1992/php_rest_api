
<?php

require_once './vendor/autoload.php';
require('./utils/Constants.php');
require('./db/DbCon.php');
require './utils/IpFinder.php';
require './models/User.php';
require './main/RequestDispatcher.php';
require('./main/RequestController.php');
$reqController = new RequestController();
$reqController->processRequest();
