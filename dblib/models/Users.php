<?php
namespace models;
use libDb\Table;

class Users extends Table{
  var $_name = 'zone'   ;
  
  /**
   * Function to get all users;
   * **/
  public function getAllUsers(){
     
     $row = $this->createRow();
     $row->name = "test Zone";
     $row->created = date('Y-m-d H:i:s');
     
     $status = $row->save();
     
     #-- debug
     var_dump($status);
     die;
     
  }
}
?>