<?php 

namespace App;
use App\Repository;
use App\Errors;
use App\Validator;
require __DIR__.'/../vendor/autoload.php';

class Main
{
  public function register($user)
  {
    $language = $_SESSION['lang'];
    $validator = new Validator($language);
    $repo = new Repository();
    $errors = new Errors($language);

    $errors = $validator->validate($user);
    if (count($errors) !== 0) {
      foreach ($errors as $key => $value) {
        $_SESSION[$key] = $value;
      }
      header("Location: /users/new");
      return false;
    }
    
    $oldUser = $repo->find($user);
    if (!$oldUser) {
      //the user does not exist
      $repo->save($user);
      return true;
    } else {
      //the user exists
      $_SESSION['alreadyTaken'] = $errors->get('alreadyTaken');
      header("Location: /users/new");
      return false;
    }
  }

  public function authorization($user)
  {
    $repo = new Repository();
    $errors = new Errors($_SESSION['lang']);

    if ($user['login'] === '') {
      $_SESSION['errorLogin'] = $errors->get('empty');
      header("Location: /");
      return;
    }
    $userData = $repo->find($user);
    if (!$userData) {
      $_SESSION['errorLogin'] = $errors->get('login');
      header("Location: /");
      return;
    } 
    if (password_verify($user['password'], $userData['password'])) {
      $_SESSION['user'] = $user;
      $_SESSION['data'] = $repo->getUser($user['login']);
      header("Location: /");
    } else {
      $_SESSION['errorPassword'] = $errors->get('password');
      header("Location: /");
    }
  }

  public function exit() {
    $_SESSION = [];
    session_destroy();
    header("Location: /");
  }

}