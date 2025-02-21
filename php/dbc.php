<?php
  $host = 'localhost';
  $data = 'proassist';
  $user = 'root';
  $pass = 'mysql';
  $chrs = 'utf8mb4';
  $attr = "mysql:host=$host;dbname=$data;charset=$chrs";
  $opts =
  [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES => false,
  ];
  function fixi($e){
      return(htmlentities(stripcslashes(strip_tags($e))));
  }
  try{
    $pdo = new PDO($attr, $user, $pass, $opts);
  }
  catch(PDOExceptoin $e){
    die("Проблекми з базою");
  }
 ?>
