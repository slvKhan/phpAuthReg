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
    $query = "INSERT INTO `signup` (`login`, `phone`, `email`, `password`, `image`) VALUES (:login, :phone, :email, :password, :image)";
    $hash = password_hash($user['password'], PASSWORD_ARGON2I);
    $pathToSaveImage = null;
    if ($_FILES['image_file']) {
      $mime = substr($_FILES['image_file']['name'], -3, 3);
      $pathToSaveImage = 'uploades/'.time().'.'.$mime;
      $this->saveImage($pathToSaveImage);
    }
    $params = [
      ':login' => $user['login'],
      ':phone' => $user['phone'],
      ':email' => $user['email'],
      ':password' => $hash,
      ':image' => $pathToSaveImage,
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

  public function getUser($login) {
    $query = "SELECT * FROM signup WHERE `login` = :login LIMIT 1";
    $params = [':login' => $login];
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($params);
    $userData = $stmt->fetch();
    return $userData;
  }

  private function saveImage($path)
  {
    $tmpFileName = $_FILES['image_file']['tmp_name'];
    move_uploaded_file($tmpFileName, __DIR__.'/../'.$path);
  }

  private function getImage($user)
  {

  }

}