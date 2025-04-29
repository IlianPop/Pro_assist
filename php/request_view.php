<?php
    require_once "dbc.php";
  	session_start();
  	if($_SESSION['STAT']!='service'|| !isset($_SESSION['OID']) || !isset($_GET['who'])){
    	header('Location: ../index.php');
    	exit();
  	}
	$query = $pdo->prepare('select users.name as name, users.last_name as `last`, users.midle_name AS midle, rooms.title as room, requests.title as title, requests.description as description, requests.type as type FROM requests join users on requests.sender = users.id join room_workers on users.id = room_workers.user_id join rooms on room_workers.room_id = rooms.id where requests.id = ?');
	$query->bindValue(1, $_GET['who'], PDO::PARAM_INT);
	$query->execute();
	if($query->rowCount()==0){
		header('Location: service.php');
    	exit();
	}
	$res = $query->fetch();
	if(isset($_GET['ready'])){
		$query = $pdo->prepare('update requests set status = 1 where id = ?');
		$query->bindParam(1, $_GET['who'], PDO::PARAM_INT);
		$query->execute();
		header('Location: service.php');
    	exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Перегляд запиту</title>
	<link rel = 'stylesheet' href = '../styles/request_view.css'>
</head>
<body>
	<div id = 'container'>
		<div id = 'container_info'>
			<h3 id = 'left_container'><?= $res['last'] . ' ' . $res['name'] . ' ' . $res['midle'] ?></h3>
			<h3 id = 'right_container'><?= $res['room'] ?></h3>
		</div>
			<div id = 'info_panel'>
			<h4><?= $res['title'] ?></h4>
			<h4><?= $res['type'] ?></h4>
			<p><?= $res['description'] ?></p>
			<form method="get" action="request_view.php">
				<input type="hidden" name="ready" value="true">
				<input type="hidden" name="who" value="<?= $_GET['who'] ?>">
				<input type="submit" value="Позначити виконаним">
			</form>
		</div>
	</div>
</body>
</html>