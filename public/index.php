<?php
use App\Application;
use App\Template;
use App\Main;
require __DIR__.'/../vendor/autoload.php';

session_start();

//set language, default ru
$app = new Application();
$main = new Main();
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'ru';
$template = new Template(__DIR__.'/../templates/'.$lang.'/');


//routes of lang setters
$app->route('POST', '/en', function() {
  $_SESSION['lang'] = 'en';
  header("Location: /");
});
$app->route('POST', '/ru', function() {
  $_SESSION['lang'] = 'ru';
  header("Location: /");
});

//the main page rout
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

$app->route('POST', '/sign_in', function () use ($main) {
  $main->authorization($_POST);
  header("Location: /");
});

$app->route('POST', '/sign_out', function () use ($main) {
  $main->exit();
});

$app->route('GET', '/users/new', function () use ($template) {
  $keys = ['alreadyTaken', 'loginError', 'passwordError', 'emailError', 'phoneError', 'confirmError', 'fileError'];
  $params = [];
  //if the user has error, than transfer errors in the parameters
  foreach ($keys as $key) {
    $params[$key] = isset( $_SESSION[$key]) ? $_SESSION[$key] : null;
    $_SESSION[$key] = null;
  }
  return $template->render('users/new.phtml', $params);
});


$app->route('POST', '/users', function () use ($template, $main) {
  $succes = $main->register($_POST);
  if ($succes) {
    return $template->render('users/succes.phtml');
  }
});

$app->run();
