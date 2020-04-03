<?php

namespace App;

require __DIR__.'/../vendor/autoload.php';

class Validator
{
  private $lang;
  private $errorMessagesTemp;
  public function __construct($lang = 'ru', $errors)
  {
    $this->lang = $lang;
    $this->errorMessagesTemp = $errors;
  }

  public function validate($userData)
  {
    $ResError = [];
    $errMessage = $this->errorMessagesTemp;

    if (strlen($userData['login']) <= 2 || strlen($userData['login'] >= 12)) {
      $ResError['loginError'] = $errMessage->get('login');
    }

    if (strpos($userData['email'], '@') === false || strlen($userData['email']) <= 4) {
      $ResError['emailError'] = $errMessage->get('email');
    }

    if ($userData['phone'] !== '') {
      if (strlen($userData['phone']) !== 12)
      {
        $ResError['phoneError'] = $errMessage->get('phone');
      }
    }

    if ((strlen($userData['password']) < 6) || (strlen($userData['password']) > 14)) {
      $ResError['passwordError'] = $errMessage->get('password');
    }

    if ($userData['password'] !== $userData['password_confirm']) {
      $ResError['confirmError'] = $errMessage->get('math');
    }
    
    if ($_FILES['image_file']['name']) {
      $MimeTypes = ['png', 'gif', 'jpg'];
      $maxSize = 10485760;
      $mime = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
      $size = $_FILES['image_file']['size'];

      if ((count($_FILES) > 1) || (!in_array($mime, $MimeTypes)) || ($size > $maxSize)) {
        $ResError['fileError'] = $errMessage->get('file');
      }
      
    }

    return $ResError;
  }
}