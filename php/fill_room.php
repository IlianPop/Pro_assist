<?php
	require_once "dbc.php";
	session_cache_limiter('private_no_expire');
  	session_start();
	if(isset($_POST['exit'])){
		header('Location: admin.php');
		exit();
	}
  	if($_SESSION['STAT']!='admin'|| !isset($_SESSION['OID']) || !isset($_POST['who'])){
    	header('Location: ../index.php');
    	exit();
  	}
  	if(isset($_POST['who1'])){
  		$query2 = $pdo->prepare('update room_workers set room_id = ? where user_id = ?');
  		$query2->bindValue(1, null, PDO::PARAM_NULL);
  		$query2->bindValue(2, $_POST['who1'], PDO::PARAM_INT);
  		$query2->execute();
		header('Location: admin.php');
  	}
  	$query = $pdo->prepare('select users.id as id, users.name as name, users.last_name as last_name, users.midle_name as midle_name from room_workers join users on room_workers.user_id = users.id where room_workers.room_id = ?');
  	$query->bindValue(1, $_POST['who'], PDO::PARAM_INT);
  	$query->execute();
  	$query1 = $pdo->prepare('select number from rooms where id = ?');
  	$query1->bindValue(1, $_POST['who'], PDO::PARAM_INT);
  	$query1->execute();
  	$res = $query1->fetch();
  	?>
<!DOCTYPE html>
<html>
<head>
	<title>Кімната</title>
	<link rel = 'stylesheet' href="../styles/fill_room.css">
	<link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
</head>
<body>
	<div id = 'list_container'>
		<?php 
		for($i = 0; $i < (int)$res['number']-(int)$query->rowCount(); $i++){ ?>
			<div class = 'item_container'>
				<form method="post" action="insert_worker.php">
					<input type="hidden" name = 'who' value="<?=$_POST['who']?>">
					<input type="submit" value="+">
				</form>
			</div>
		<?php }
		while($row = $query->fetch()) { ?>
			<div class = 'item_container'>
				<form method="post" action="fill_room.php" class = 'worker'>
					<input type="hidden" name = 'who' value="<?=$_POST['who']?>">
					<input type="hidden" name = 'who1' value="<?=$row['id']?>">
					<button type="submit">
						<h4 class="smallFont"><?="<b>{$row['id']}</b><br>{$row['last_name']} {$row['name']}"?></h4>
					</button>
				</form>
			</div>
		<?php } ?>
	</div>
	<div id = 'left_container'>
      <form method = 'post' action = ''>
        <input id = 'home' type = 'submit' value = '' name = 'exit'>
      </form>
    </div>
</body>
</html>