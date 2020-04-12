<?php 

namespace App;
use App\Repository;
use App\Errors;
use App\Validator;

require __DIR__.'/../vendor/autoload.php';

class Main
{
  private $language;
  private $repo;
  private $errorMassages;

  public function __construct()
  {
    $this->language = $_SESSION['lang'];
    $this->repo = new Repository();
    $this->errorMassages = new Errors($this->language);
  }

  public function register($user)
  {
    $validator = new Validator($this->language, $this->errorMassages);
    $errors = $validator->validate($user);
    if (count($errors) !== 0) {
      foreach ($errors as $key => $value) {
        $_SESSION[$key] = $value;
      }
      header("Location: /users/new");
      return false;
    }
    
    $oldUser = $this->repo->find($user);
    if (!$oldUser) {
      //the user does not exist
      $this->repo->save($user);
      return true;
    } else {
      //the user exists
      $_SESSION['alreadyTaken'] = $this->errorMassages->get('alreadyTaken');
      header("Location: /users/new");
      return false;
    }
  }

  public function authorization($user)
  {
    if ($user['login'] === '') {
      $_SESSION['errorLogin'] = $this->errorMassages->get('empty');
      return;
    }
    $userData = $this->repo->find($user);
    if (!$userData) {
      $_SESSION['errorLogin'] = $this->errorMassages->get('login');
      return;
    } 
    if (password_verify($user['password'], $userData['password'])) {
      $_SESSION['user'] = $user;
      $_SESSION['data'] = $this->repo->getUser($user['login']);
      return;
    } else {
      $_SESSION['errorPassword'] = $this->errorMassages->get('password');
      return;
    }
  }

  public function exit() {
    $_SESSION = [];
    session_destroy();
    header("Location: /");
  }

}