<?php

namespace App;

class Repository
{
  private $pdo;

  public function __construct($pdo)
  {
    $this->pdo = $pdo;
  }

  public function save($user)
  {
    $query = "INSERT INTO `signup` (`login`, `user_phone`, `email`, `password`) VALUES (:login, :user_phone, :email, :password)";
    $params = [
      ':login' => $user['login'],
      ':user_phone' => $user['phone'],
      ':email' => $user['email'],
      ':password' => $user['password'],
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
    return json_encode($userData);
  }


}