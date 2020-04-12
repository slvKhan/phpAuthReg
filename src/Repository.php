<?php

namespace App;
require __DIR__.'/../vendor/autoload.php';

class Repository
{
  private $db;

  public function __construct()
  {
    $this->db = new QueryBuilder(Connection::make());
  }
  
  public function save($user)
  {
    $pathToSaveImage = null;
    if ($_FILES['image_file']['name']) {
      $mime = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
      $pathToSaveImage = 'uploades/'.time().'.'.$mime;
      $this->saveImage($pathToSaveImage);
    }
    $user['pathToSaveImage'] = $pathToSaveImage;
    $this->db->insert($user);
  }

  public function find($user)
  {
    return $this->db->select($user);
  }

  public function getUser($login) {
    return $this->db->selectUser($login);
  }

  private function saveImage($path)
  {
    $tmpFileName = $_FILES['image_file']['tmp_name'];
    if (!file_exists(__DIR__.'/../uploades/')) {
      mkdir(__DIR__.'/../uploades/');
    }
    move_uploaded_file($tmpFileName, __DIR__.'/../'.$path);
  }

}