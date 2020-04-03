<?php 

namespace App;

class QueryBuilder
{
  private $db;

  public function __construct($pdo)
  {
    $this->db = $pdo;
  }

  public function insert($user)
  {
    $query = "INSERT INTO `signup` (`login`, `phone`, `email`, `password`, `image`) VALUES (:login, :phone, :email, :password, :image)";
    $hash = password_hash($user['password'], PASSWORD_ARGON2I);
    $params = [
      ':login' => trim($user['login']),
      ':phone' => trim($user['phone']),
      ':email' => trim($user['email']),
      ':password' => $hash,
      ':image' => $user['pathToSaveImage'],
    ];
    $stmt = $this->db->prepare($query);
    $stmt->execute($params);
  }

  public function select($user)
  {
    $query = "SELECT * FROM signup WHERE `login` = :login OR `email` = :email LIMIT 1";
    $params = [
      ':login' => $user['login'],
      ':email' => $user['email'],
    ];
    $stmt = $this->db->prepare($query);
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

  public function selectUser($login)
  {
    $query = "SELECT * FROM signup WHERE `login` = :login LIMIT 1";
    $params = [':login' => $login];
    $stmt = $this->db->prepare($query);
    $stmt->execute($params);
    $userData = $stmt->fetch();
    return $userData;
  }

}