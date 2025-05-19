<?php
    require_once "dbc.php";
	session_cache_limiter('private_no_expire');
  	session_start();
  	if(!($_SESSION['STAT']!='service'|| $_SESSION['STAT']!='working')|| !isset($_SESSION['OID'])||!isset($_POST['who'])){
    	header('Location: ../index.php');
    	exit();
  	}
    if(isset($_POST['ready'])){
        $query =$pdo->prepare('update requests set status = 1 where id = ?');
        $query->bindValue(1, $_POST['who'], PDO::PARAM_INT);
        $query->execute();
    }
	$query = $pdo->prepare('SELECT requests.id as id, requests.title as title, requests.description as description, requests.type as type, requests.status as status, requests.time as time, u1.name as name1, u1.last_name as last1, u1.midle_name as midle1, u2.name as name2, u2.last_name as last2, u2.midle_name as midle2, rooms.title as room_title from requests JOIN users u1 on requests.sender = u1.id join users u2 on requests.reciver = u2.id JOIN room_workers on u1.id = room_workers.user_id JOIN rooms on room_workers.room_id = rooms.id where requests.id = ?;');
	$query->bindValue(1, $_POST['who'], PDO::PARAM_INT);
	$query->execute();
    $res = $query->fetch();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Сторінка перегляду запиту</title>
        <link rel = 'stylesheet' href = '../styles/service_answer.css'>
        <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
    </head>
    <body>
        <div id = 'container'>
            <h3><?= $res['title'] ?></h3>
            <h4><?= $res['room_title'] . ' ' . $res['time'] . ' '?><?php if($res['status'] == 0 ){echo('Не виконано');}else{echo('Виконано');} ?></h4>
            <h4>Відправник <?= $res['last1'] . ' ' . $res['name1'] . ' ' . $res['midle1']?></h4>
            <h4>Виконавець <?= $res['last2'] . ' ' . $res['name2'] . ' ' . $res['midle2']?></h4>
            <p><?= $res['description'] ?></p>
            <?php if($_SESSION['STAT'] == 'service' && $res['status'] == 0){ ?>
                <form method = 'post' action = 'service_answer.php'>
                    <input type = 'hidden' name = 'who' value = '<?= $_POST['who'] ?>'>
                    <input type = 'hidden' name = 'ready' value = 'true'>
                    <input type = 'submit' value = 'Позначити виконаним'>
                </form>
            <?php } ?>
        </div>
    </body>
</html>