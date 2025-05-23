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
  	$query = $pdo->prepare('select users.id as id, users.last_name as last_name, users.name as name, users.midle_name as midle_name from users join room_workers on room_workers.user_id = users.id where room_workers.office_id = ? and room_workers.room_id is null');
  	$query->bindValue(1, $_SESSION['OID'], PDO::PARAM_INT);
  	$query->execute();
  	if(isset($_POST['who1'])){
  		$query = $pdo->prepare('update room_workers set room_id = ? where user_id = ?');
  		$query->bindValue(1, $_POST['who'], PDO::PARAM_INT);
  		$query->bindValue(2, $_POST['who1'], PDO::PARAM_INT);
  		$query->execute();
  		header('Location: admin.php');
  		exit();
  	}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Присвоїти робочого</title>
    <link rel = 'stylesheet' href="../styles/workers_list.css">
    <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
  </head>
  <body>
    <div id = 'list_container'>
        <br>
        <?php while($row = $query->fetch()){ ?>
          <div class="item_container">
            <h4><?= "<b>{$row['id']}</b> {$row['last_name']} {$row['name']} {$row['midle_name']}"?></h4>
            <form method="post" action = "insert_worker.php">
              <input type = 'hidden' name = 'who1' value="<?= $row['id'] ?>">
              <input type = 'hidden' name = 'who' value="<?= $_POST['who'] ?>">
              <input type = 'submit' value = 'Вибрати'>
            </form>
          </div>
      <?php } ?>
      </div>
    </div>
    <div id = 'left_container'>
      <form method = 'post' action = ''>
        <input id = 'home' type = 'submit' value = '' name = 'exit'>
      </form>
    </div>
  </body>
</html>
