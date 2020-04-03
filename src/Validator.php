<?php

namespace App;

use App\Errors;
require __DIR__.'/../vendor/autoload.php';

class Validator
{
  private $lang;
  public function __construct($lang = 'ru')
  {
    $this->lang = $lang;
  }

  public function validate($userData)
  {
    $ResError = [];
    $errMessage = new Errors($this->lang);

    
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