<?php
use App\Application;
use App\Template;
use App\Timer;
use App\Repository;

require __DIR__.'/../vendor/autoload.php';


$app = new Application();
$template = new Template(__DIR__.'/../templates/');

//
Timer::start();
$repo = new PDO('mysql:host=127.0.0.1;dbname=users_db', 'sandy', 'qwe');
echo Timer::finish() . ' сек<br>';
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
    Timer::start();
    print_r($user);
    $query = "INSERT INTO `signup` (`login`, `phone`, `email`, `password`) VALUES (:login, :phone, :email, :password)";
    $params = [
      ':login' => $user['login'],
      ':phone' => $user['phone'],
      ':email' => $user['email'],
      ':password' => $user['password'],
    ];
    $stmt = $repo->prepare($query);
    $stmt->execute($params);
    echo Timer::finish() . ' сек';
  //задержка 2 сек
  }
});

$app->run();




