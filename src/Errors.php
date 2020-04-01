<?php

namespace App;

class Errors
{
  private $lang;
  private $errMessages = [
    'ru' => [
      'login' => 'Вы ввели некорректный логин, пожалуйста введите другой.',
      'email' => 'Вы ввели некорректный email, пожалуйста введите другой.',
      'phone' => 'Вы ввели неккоректный телефон, пожалуйста введите другой.',
      'password' => 'Вы ввели неккоректный пароль, пожалуйста введите другой.',
      'math' => 'Пароли не совпадают.',
      'file' => 'Данный тип файла не поддерживается.',
    ],
    'en' => [
      'login' => 'Value is not a valid login, please enter again.',
      'email' => 'Value is not a valid email, please enter again.',
      'phone' => 'Value is not a valid phone, please enter again',
      'password' => 'Value is not a valid password, please enter again.',
      'math' => 'Password confirmation does not match to password.',
      'file' => 'Error loading the file, the file type is not supported.',
    ]
  ];

  public function __construct($lang)
  {
    $this->lang = $lang;
  }

  public function get($type)
  {
    return $this->errMessages[$this->lang][$type];
  }

}