<?php

namespace App;
require __DIR__.'/../vendor/autoload.php';
use PDO;

class Repository
{
  private $pdo;

  public function __construct()
  {
    $HOST_NAME = '127.0.0.1';
    $DB_NAME = 'users_db';
    $USER_NAME = 'sandy';
    $PASSWORD = 'qwe';

    $this->pdo = new PDO("mysql:host={$HOST_NAME};dbname={$DB_NAME}", $USER_NAME, $PASSWORD);
  }

  public function save($user)
  {
    $query = "INSERT INTO `signup` (`login`, `phone`, `email`, `password`) VALUES (:login, :phone, :email, :password)";
    $hash = password_hash($user['password'], PASSWORD_ARGON2I);
    $params = [
      ':login' => $user['login'],
      ':phone' => $user['phone'],
      ':email' => $user['email'],
      ':password' => $hash,
    ];
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
  }

  public function find($user)
  {
    $query = "SELECT * FROM signup WHERE `login` = :login OR `email` = :email LIMIT 1";
    $params = [
      ':login' => $user['login'],
      ':email' => $user['email'],
    ];
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    $userData = $stmt->fetch();
    if (!$userData) {
      return null;
    } 
    return [
      'login' => $userData['login'],
      'password' => $userData['password'],
    ];
  }

}