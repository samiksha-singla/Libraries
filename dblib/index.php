<?php
// require necessary files
require_once 'autoload.php';
use libDb\Db;

try{

// Try to call the class
$objUsersModel = new \models\Users();
$objUsersModel->getAllUsers();

}catch(Exception $ex){
   echo $ex->getMessage();
}
?>