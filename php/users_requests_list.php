<?php
    require_once "dbc.php";
	session_cache_limiter('private_no_expire');
  	session_start();
  	if($_SESSION['STAT']!='service'|| !isset($_SESSION['OID'])){
    	header('Location: ../index.php');
    	exit();
  	}
    $query = $pdo->prepare('select ');
?>