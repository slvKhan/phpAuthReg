<?php 

namespace App;

class Template
{
  private $dirPath;

  public function __construct($dirPath)
  {
    $this->dirPath = $dirPath;
  }

  public function render($filePath, $params = [])
  {
    $templatepath = $this->dirPath . $filePath;
    return $this->getTemplate($templatepath, $params);
  }

  private function getTemplate($template, $variables)
  {
    extract($variables);
    ob_start();
    include $template;
    return ob_get_clean();
  }
  
}