
<?php

require('./utils/Constants.php');
require('./db/DbCon.php');
require './models/User.php';

require './utils/jwt.php';
require './main/RequestDispatcher.php';
require('./main/RequestController.php');
$reqController = new RequestController();
$reqController->processRequest();
