<?php

class Admin
{
  protected $pdo;

  function __construct($pdo)
  {
    $this->pdo = $pdo;
  }
}