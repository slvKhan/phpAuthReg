<?php

namespace App;

use App\Errors;
require __DIR__.'/../vendor/autoload.php';

class Validator
{
  public function validate($userData, $lang = 'ru')
  {
    $ResError = [];
    $errMessage = new Errors($lang);
    if (strlen($userData['login']) <= 2 || strlen($userData['login'] >= 12)) {
      $ResError['login'] = $errMessage->get('login');
    }
    if (strpos($userData['email'], '@') === false || strlen($userData['email']) <= 4) {
      $ResError['email'] = $errMessage->get('email');
    }
    if ($userData['phone'] !== '') {
      if (strlen($userData['phone']) !== 12)
      {
        $ResError['phone'] = $errMessage->get('phone');
      }
    }
    if (strlen($userData['password']) < 6) {
      $ResError['password'] = $errMessage->get('password');
    }
    if ($userData['password'] !== $userData['password_confirm']) {
      $ResError['password_confirm'] = $errMessage->get('math');
    }
    if ($_FILES['image_file']['name']) {
      $MimeTypes = ['png', 'gif', 'jpg'];
      $maxSize = 10485760;
      $mime = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
      $size = $_FILES['image_file']['size'];
      if ((count($_FILES) > 1) || (!in_array($mime, $MimeTypes)) || ($size > $maxSize)) {
        $ResError['file'] = $errMessage->get('file');
      }
    }

    return $ResError;
  }
}