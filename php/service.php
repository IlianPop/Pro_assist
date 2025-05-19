<?php
    require_once "dbc.php";
  	session_start();
	if(isset($_POST['exit'])){
		unset($_SESSION['STAT']);
		unset($_SESSION['OID']);
		unset($_SESSION['NAME']);
		unset($_SESSION['MIDLE_NAME']);
		unset($_SESSION['LAST_NAME']);
		header('Location: ../index.php');
		exit();
	}
  	if($_SESSION['STAT']!='service'|| !isset($_SESSION['OID'])){
    	header('Location: ../index.php');
    	exit();
  	}
  	$query = $pdo->prepare('DELETE from requests where `status` = 1 and `time` < date(now());');
  	$query->execute();
	$query = $pdo->prepare('SELECT requests.id as id, requests.title as title, users.name as name, users.last_name as `last`, users.midle_name as midle, requests.status as status, rooms.title as room FROM `requests` join users on requests.sender = users.id join room_workers on users.id = room_workers.user_id JOIN rooms on room_workers.room_id = rooms.id where requests.reciver = ?;');
	$query->bindValue(1, $_SESSION['ID'], PDO::PARAM_INT);
	$query->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Сторінка сервісу</title>
	<link rel = 'stylesheet' href = '../styles/service.css'>
	<link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
</head>
<body>
	<div class = 'container'>
		<div id = 'container_info'>
			<h3 id = '1left_container'><?= $_SESSION['LAST_NAME'] . ' ' . $_SESSION['NAME'] . ' ' . $_SESSION['MIDLE_NAME'] ?></h3>
			<h3 id = 'right_container'>Обслуговуючий персонал</h3>
		</div>
		<h1>Мої запити</h1>
		<div id = 'requests_container'>
			<?php while($row = $query->fetch()){?>
				<?php if($row['status'] == 0) {?>
				<div class = 'item'>
					<h5><?=$row['last'] . ' ' . $row['name'] . ' ' . $row['midle'] . ' <strong>' . $row['title'] . '</strong> ' . $row['room']?></h5>
					<a href = 'request_view.php?who=<?= $row['id'] ?>'><button type = 'button'>Детальніше</button></a>
				</div>
			<?php }} ?>
		</div>
	</div>
	<div id = 'left_container'>
      <form action = "add_edit_office.php" method="post">
        <input id = 'home' type = 'submit' value = '' name = 'exit'>
      </form>
    </div>
</body>
</html>