<?php
	require_once "dbc.php";
	session_cache_limiter('private_no_expire');
  	session_start();
	date_default_timezone_set('Europe/Kyiv');
	if(isset($_POST['exit'])){
		unset($_SESSION['STAT']);
		unset($_SESSION['OID']);
		unset($_SESSION['NAME']);
		unset($_SESSION['MIDLE_NAME']);
		unset($_SESSION['LAST_NAME']);
		header('Location: ../index.php');
		exit();
	}
  	if($_SESSION['STAT']!='working'|| !isset($_SESSION['OID'])){
    	header('Location: ../index.php');
    	exit();
  	}
  	if(isset($_POST['title'])){
  		try{
			$query = $pdo->prepare('select mode from office where id = ?');
			$query->bindValue(1, $_SESSION['OID'], PDO::PARAM_INT);
			$query->execute();
			$res1 = $query->fetch();
			if($res1['mode']==1){
				$query = $pdo->prepare('insert into requests(sender, title, description, type) values(?, ?, ?, ?)');
				$query->bindValue(1, $_SESSION['ID'], PDO::PARAM_INT);
				$query->bindValue(2, fixi($_POST['title']), PDO::PARAM_STR);
				$query->bindValue(3, fixi(trim($_POST['description'])), PDO::PARAM_STR);
				$query->bindValue(4, fixi($_POST['type']), PDO::PARAM_STR);
				$query->execute();
				$id = $pdo->lastInsertId();
				$week = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
				$who1 = null;
				$day = date('N')-1;
				for($i=$day;$i<7;$i++){
					$query = $pdo->prepare('select users.id as id from users join rob_graphik on users.id = rob_graphik.user_id where rob_graphik.office_id = ? and ' . trim($week[$i]) .' = 1 limit 1');
					$query->bindValue(1, $_SESSION['OID'], PDO::PARAM_INT);
					$query->execute();
					if($query->rowCount()>0){
						$who1 = $query->fetch()['id'];
						break;
					}
				}
				if($who1==null){
					for($i=0;$i<$day;$i++){
						$query = $pdo->prepare('select users.id as id from users join rob_graphik on users.id = rob_graphik.user_id where rob_graphik.office_id = ? and ' . $week[$i] .' = 1 limit 1');
						$query->bindValue(1, $_SESSION['OID'], PDO::PARAM_INT);
						$query->execute();
						if($query->rowCount()>0){
							$who1 = $query->fetch()['id'];
							break;
						}
					}
				}
				if($who1!=null){
					$query2 = $pdo->prepare('update requests set reciver = ? where id = ?');
					$query2->bindValue(1, $who1, PDO::PARAM_INT);
					$query2->bindValue(2, $id, PDO::PARAM_INT);
					$query2->execute();
				}
			}
			else{
				$query = $pdo->prepare('insert into requests(sender, title, description, type) values(?, ?, ?, ?)');
				$query->bindValue(1, $_SESSION['ID'], PDO::PARAM_INT);
				$query->bindValue(2, fixi($_POST['title']), PDO::PARAM_STR);
				$query->bindValue(3, fixi(trim($_POST['description'])), PDO::PARAM_STR);
				$query->bindValue(4, fixi($_POST['type']), PDO::PARAM_STR);
				$query->execute();
			}
	  		header('Location: employee.php');
    		exit();
  		}
  		catch(PDOException $e){
  			$err = 'Невалідні дані';                   
  		}
  	}
  	$query = $pdo->prepare('DELETE from requests where `status` = 1 and `time` < date(now());');
  	$query->execute();
  	$query = $pdo->prepare('select distinct work_type from rob_graphik where office_id = ?');
  	$query->bindValue(1, $_SESSION['OID'], PDO::PARAM_INT);
  	$query->execute();
  	$query2 = $pdo->prepare('select * from requests where sender = ? order by id desc');
  	$query2->bindValue(1, $_SESSION['ID'], PDO::PARAM_INT);
  	$query2->execute();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Сторінка робочого</title>
	<link rel="stylesheet" href="../styles/employee.css">
	<script src="../js/employee.js"></script>
	<link rel="icon" type="image/jpg" href="../styles/system_images/site.png">
</head>
<body>
	<div id = 'container'>
		<div id = 'container_info'>
			<h3><?= $_SESSION['LAST_NAME'] . ' ' . $_SESSION['NAME'] . ' ' . $_SESSION['MIDLE_NAME'] ?></h3>
			<h3>Персонал</h3>
		</div>
		<div id = 'centreContainer'>
			<div id = 'smartContainer'>
				<button id = 'smartButton'>Додати запит</button>
				<form id = 'add_container' method="post" action="employee.php" onsubmit="return(validate(this))">
					<input type="text" name="title" placeholder="Назва" maxlength="40">
					<select name = 'type'>
						<?php while($row = $query->fetch()){ ?>
							<option value="<?= $row['work_type'] ?>"><?= $row['work_type'] ?></option>
						<?php } ?>
					</select>
					<textarea name="description"></textarea>
					<input type="submit" value="Відправити запит">
				</form>
			</div>
			<div id="list_container">
				<?php while($row = $query2->fetch()){ ?>
					<div class="item_container">
						<h3><?= $row['title']?></h3>
						<h3><?= $row['time'] . ' ' ?>
							<?php 
								if($row['status'] == 0){
									echo(' Не виконано');
								}
								else{
									echo(' Виконано');
								}
							?>
						</h3>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div id = 'left_container'>
      <form action = "add_edit_office.php" method="post">
        <input id = 'home' type = 'submit' value = '' name = 'exit'>
      </form>
    </div>
</body>
</html>