
<?php

require_once ('./utils/Constants.php');
require './main/RequestDispatcher.php';
require_once ('./main/RequestController.php');
$reqController = new RequestController();
$reqController->processRequest();
