<?php
use App\Application;
use App\Template;
use App\Repository;

require __DIR__.'/../vendor/autoload.php';


$app = new Application();
$template = new Template(__DIR__.'/../templates/');
//
$repo = new Repository(new PDO('mysql:host=localhost;dbname=users_db', 'root', ''));
//задержка 2 сек

$app->route('GET', '/', function () use($template) {
  return $template->render('index.phtml');
});

$app->route('GET', '/users/new', function () use($template) {
  return $template->render('users/new.phtml');
});

$app->route('POST', '/users', function () use($template, $repo) {
  $user = $_POST;
  $validator = new App\Validator();
  $errors = $validator->validate($user);
  
  if (count($errors) === 0) {
    $oldUser = $repo->find($user);
    if (!$oldUser) {
      echo 'user was been created';
      $repo->save($user);
    } else {
      print_r($oldUser);
    }
  }
});

$app->run();




