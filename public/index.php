<?php
use App\Application;
use App\Template;
use App\Repository;

require __DIR__.'/../vendor/autoload.php';

session_start();

$app = new Application();
$template = new Template(__DIR__.'/../templates/');
$repo = new Repository();

$app->route('GET', '/', function () use($template) {
  $params = ['currentUser' => $_SESSION['user'] ?? null];
  return $template->render('index.phtml', $params);
});

$app->route('POST', '/sign_in', function () use ($repo) {
  $user = $_POST;
  $userData = $repo->find($user);
  if (!$userData) {
    //the user does not exist
    echo 'неверный логин';
    return;
  } 
  if (password_verify($user['password'], $userData['password'])) {
    $_SESSION['user'] = $user;
    header("Location: http://test/");
  } else {
    echo 'пароль НЕ верный!';
  }
});

$app->route('POST', '/sign_out', function () use ($template) {
  $_SESSION = [];
  session_destroy();
  header("Location: http://test/");
});

$app->route('GET', '/users/new', function () use ($template) {
  return $template->render('users/new.phtml');
});

$app->route('POST', '/users', function () use ($repo) {
  $user = $_POST;
  $validator = new App\Validator();
  $errors = $validator->validate($user);
  if (count($errors) !== 0) {
    //user has errors
  }
  $oldUser = $repo->find($user);
  if (!$oldUser) {
    //the user does not exist
    $repo->save($user);

  } else {
    //the user exists
    echo 'такой пользователь существует!';
  }
});

$app->run();




