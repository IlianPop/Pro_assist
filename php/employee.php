<?php
	require_once "dbc.php";
	session_cache_limiter('private_no_expire');
  	session_start();
  	if($_SESSION['STAT']!='working'|| !isset($_SESSION['OID'])){
    	header('Location: ../index.php');
    	exit();
  	}
  	if(isset($_POST['title'])){
  		try{
	  		$query = $pdo->prepare('insert into requests(sender, title, description, type) values(?, ?, ?, ?)');
	  		$query->bindValue(1, $_SESSION['ID'], PDO::PARAM_INT);
	  		$query->bindValue(2, fixi($_POST['title']), PDO::PARAM_STR);
	  		$query->bindValue(3, fixi(trim($_POST['description'])), PDO::PARAM_STR);
	  		$query->bindValue(4, fixi($_POST['type']), PDO::PARAM_STR);
	  		$query->execute();
	  		$id = $pdo->lastInsertId();
	  		$week = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
	  		$who1 = null;
	  		$day = array_search(strtolower(date('l')), $week);
	  		for($i=$day;$i<7;$i++){
	  			$query = $pdo->prepare('select users.id as id from users join rob_graphik on users.id = rob_graphik.user_id where rob_graphik.office_id = ? and ' . $week[$i] .' = 1 limit 1');
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
</head>
<body>
	<div id = 'container'>
		<form id = 'add_container' method="post" action="employee.php">
			<input type="text" name="title" placeholder="Назва" maxlength="20">
			<br>
			<select name = 'type'>
				<?php while($row = $query->fetch()){ ?>
					<option value="<?= $row['work_type'] ?>"><?= $row['work_type'] ?></option>
				<?php } ?>
			</select>
			<br>
			<textarea name="description">
				
			</textarea>
			<br>
			<input type="submit" value="Відправити запит">
		</form>
		<br>
		<div id="list_container">
			<?php while($row = $query2->fetch()){ ?>
				<div class="item_container">
					<h4><?= $row['title']?></h4>
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
</body>
</html>