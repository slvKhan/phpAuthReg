<?php

namespace App;

class Application
{
  private $handlers = [];

  public function route($method, $path, $handler)
  {
    $route = "{$method}{$path}";
    $this->append($route, $handler);
  }

  public function run()
  {
    $currentMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    $currentPath = "{$currentMethod}{$this->prettier($uri)}";

    if (!array_key_exists($currentPath, $this->handlers)) {
      echo 404;
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

}