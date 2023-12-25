<?php

class DB
{
  public static function connect()
  {
    $host = 'localhost';
    $dataBase = 'information';
    $user = 'root';
    $password = '';

    return new PDO("mysql:host={$host};dbname={$dataBase};charset=UTF8;", $user, $password);
  }
}
