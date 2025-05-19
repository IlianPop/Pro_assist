<?php
  require_once "dbc.php";
    session_cache_limiter('private_no_expire');
  session_start();
  if($_SESSION['STAT']!='admin' || !isset($_SESSION['OID'])){
    header('Location: ../index.php');
    exit();
  }
  if(isset($_POST['exit'])){
    header('Location: admin.php');
    exit();
  }
  $query = $pdo->prepare('SELECT office.name, office.mode from office where id = ?');
  $query->bindParam(1, $_SESSION['OID'], PDO::PARAM_INT, 100);
  $query->execute();
  $res = $query->fetch();
  if(isset($_POST['change'])){
    if($res['mode'] == 1){
      $query = $pdo->prepare('UPDATE office SET mode = 0 WHERE id = ?');
      $query->bindParam(1, $_SESSION['OID'], PDO::PARAM_INT, 100);
      $query->execute();
      $res['mode'] = 0;
    }else{
      $query = $pdo->prepare('UPDATE office SET mode = 1 WHERE id = ?');
      $query->bindParam(1, $_SESSION['OID'], PDO::PARAM_INT, 100);
      $query->execute();
      $res['mode'] = 1;
    }
  }
  $query = $pdo->prepare('SELECT requests.id as id, requests.title as title, users.id as uid, users.name as name, users.last_name as last_name, users.midle_name as midle_name, rooms.title as room_title from requests join users on requests.sender = users.id join room_workers on users.id = room_workers.user_id join rooms on room_workers.room_id = rooms.id WHERE requests.sender in (SELECT DISTINCT users.id FROM users join room_workers on users.id = room_workers.user_id WHERE room_workers.office_id = ?)');
  $query->bindParam(1, $_SESSION['OID'], PDO::PARAM_INT);
  $query->execute();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Ручний режим</title>
        <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
        <link rel="stylesheet" href="../styles/hand_made.css">
    </head>
    <body>
      <div id = 'container'>
        <div id = 'title'>
          <h1><?= $res['name'] ?></h1>
          <form method="post" action="hand_made.php">
            <input type="submit" value="<?php if($res['mode']==1){echo('Вимкнути авто режим');}else{echo('Ввімкнути авто режим');} ?>" name="change"style="<?php if($res['mode']==1){echo('background-color:rgb(201, 72, 40);');}else{echo('background-color: #2ea77e;');} ?>">
          </form>
        </div>
        <div id = 'scroll_view'>
        <?php while($row = $query->fetch()){?>
            <div class="item_view">
              <div class = 'titles'>
                <h1><?= $row['title'] ?></h1>
                <h2><?= $row['uid'] . ' ' . $row['name'] . ' ' . $row['last_name'] . ' ' . $row['midle_name'] . ' ' . $row['room_title'] ?></h2>
              </div>
              <form method="post" action="request_admin.php" class="button_form">
                <input type="hidden" name="who" value="<?= $row['id'] ?>">
                <input type="submit" value="Переглянути">
              </form>
            </div>
        <?php } ?>
        </div>
        <div id = 'left_container'>
          <form action = "hand_made.php" method="post">
            <input id = 'home' type = 'submit' value = '' name = 'exit'>
          </form>
        </div>
      </div>
    </body>
</html>