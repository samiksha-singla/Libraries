<?php

namespace libDb;

class connect {

   public static function connect() {
      $config = self::_getConfig();
      $adapter = $config['adapter'];
      $dbConfig = array(
                        'dbname' =>$config['dbname'],
                        'username' =>$config['username'],
                        'password' =>$config['password'],
                        'profiler' =>$config['profiler']
                     );
      $db = \libDb\Db::factory($adapter, $dbConfig);
      Registry::set('db', $db);
      return $db;
   }

   private function _getConfig() {
      return array(
          'adapter' => 'Pdo\Mysql',
          'dbname' => 'UVID',
          'username' => 'root',
          'password' => 'welcome',
          'profiler' => 1
      );
   }

}
