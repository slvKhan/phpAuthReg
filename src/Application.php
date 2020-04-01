<?php

namespace App;

class Application
{
  private $handlers = [];
  private const ERROR_404 = '<h1>404 Not Found</h1>
  <p>The resource could not be found.</p>
  <a href="http://test/">http://test/</a>';

  public function route($method, $path, $handler)
  {
    $route = "{$method}{$path}";
    $this->append($route, $handler);
  }

  public function run()
  {
    $currentMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, 'uploades') !== false) {
      $filePath = __DIR__.'/..'.$uri;
      $this->getImage($filePath);
    }
    if (strpos($uri, 'scripts') !== false) {
      $filePath = __DIR__.'/../public'.$uri;
      $this->getScript($filePath);
    }
    $currentPath = "{$currentMethod}{$this->prettier($uri)}";

    if (!array_key_exists($currentPath, $this->handlers)) {
      echo self::ERROR_404;
      return;
    }
    
    $handler = $this->handlers[$currentPath];
    echo $handler();
    return;
  }

  private function append($route, $handler)
  {
    $this->handlers[$route] = $handler;
  }

  private function prettier($path)
  {
    if (strlen($path) > 1) {
      return rtrim($path, '/');
    }
    return $path;
  }

  private function getImage($filepath)
  { 
    header("Content-Type: image/jpeg");
    readfile($filepath);
    exit();
  }

  private function getScript($filepath)
  {
    header("Content-Type: application/x-javascript");
    readfile($filepath);
    exit();
  }

}