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
  $params = [
    'islogIn' => ''
  ];
  return $template->render('index.phtml');
});

$app->route('GET', '/users/new', function () use($template) {
  return $template->render('users/new.phtml');
});

$app->route('POST', '/users', function () use($template, $repo) {
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




