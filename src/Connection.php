<?php

namespace App;
require __DIR__.'/../vendor/autoload.php';
use PDO;

class Connection
{
  public static function make()
  {
    $HOST_NAME = '127.0.0.1';
    $DB_NAME = 'users_db';
    $USER_NAME = 'sandy';
    $PASSWORD = 'qwe';

    return new PDO("mysql:host={$HOST_NAME};dbname={$DB_NAME}", $USER_NAME, $PASSWORD);
  }
}