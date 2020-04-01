<?php
use App\Application;
use App\Template;
use App\Repository;
require __DIR__.'/../vendor/autoload.php';

session_start();
$app = new Application();
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ru';
$template = new Template(__DIR__.'/../templates/'.$lang.'/');

$app->route('POST', '/en', function() {
  $_SESSION['lang'] = 'en';
  header("Location: /");
});
$app->route('POST', '/ru', function() {
  $_SESSION['lang'] = 'ru';
  header("Location: /");
});

$app->route('GET', '/', function () use($template) {
  $params = [
    'currentUser' => $_SESSION['user'] ?? null,
    'userData' => isset( $_SESSION['data']) ? $_SESSION['data'] : null,
    'errorLogin' => isset( $_SESSION['errorLogin']) ? $_SESSION['errorLogin'] : null,
    'errorPassword' => isset( $_SESSION['errorPassword']) ? $_SESSION['errorPassword'] : null,
  ];
  $_SESSION['errorLogin'] = null;
  $_SESSION['errorPassword'] = null;
  return $template->render('index.phtml', $params);
});

$app->route('POST', '/sign_in', function () {
  $repo = new Repository();
  $errors = new App\Errors($_SESSION['lang']);
  $user = $_POST;
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
});

$app->route('POST', '/sign_out', function () use ($template) {
  $_SESSION = [];
  session_destroy();
  header("Location: /");
});

$app->route('GET', '/users/new', function () use ($template) {
  $keys = ['alreadyTaken', 'loginError', 'passwordError', 'emailError', 'phoneError', 'confirmError', 'fileError'];
  $params = [];
  foreach ($keys as $key) {
    $params[$key] = isset( $_SESSION[$key]) ? $_SESSION[$key] : null;
    $_SESSION[$key] = null;
  }
  return $template->render('users/new.phtml', $params);
});

$app->route('POST', '/users', function () use ($template) {
  $user = $_POST;
  $validator = new App\Validator($_SESSION['lang']);
  
  $errors = $validator->validate($user);
  if (count($errors) !== 0) {
    foreach ($errors as $key => $value) {
      $_SESSION[$key] = $value;
    }
    header("Location: /users/new");
    return;
  }
  $repo = new Repository();
  $oldUser = $repo->find($user);
  if (!$oldUser) {
    //the user does not exist
    $repo->save($user);
    return $template->render('users/succes.phtml');
  } else {
    //the user exists
    $errors = new App\Errors($_SESSION['lang']);
    $_SESSION['alreadyTaken'] = $errors->get('alreadyTaken');
    header("Location: /users/new");
    return;
  }
});


$app->run();
