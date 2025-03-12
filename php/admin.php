<?php
  require_once "dbc.php";
  session_start();

  if($_SESSION['STAT']!='admin'){
    header('Location: ../index.php');
    exit();
  }
  $query = $pdo->prepare('select * from office where admin_id = ?');
  $query->bindValue(1, fixi($_SESSION['ID']), PDO::PARAM_INT);
  $query->execute();
  $res = $query->fetch();
  $query = $pdo->prepare('select * from rooms where office_id = ?');
  $query->bindValue(1, fixi($res['id']), PDO::PARAM_INT);
  $query->execute();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Панель адміністратора</title>
    <link rel = "stylesheet" href = "../styles/admin_style.css">
    <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
  </head>
  <body>
    <div class="container">
      <div class = "info_panel">
        <h1 id = "name_info"><?= fixi($_SESSION['LAST_NAME'] . " " . $_SESSION['NAME'] . " " . $_SESSION['MIDLE_NAME']) ?></h1>
        <h1 id = "office_info"><?= fixi($res['name']) ?></h1>
      </div>
      <form action = "add_edit_room.php" method="post" class = "object1">
        <input type = 'hidden' name = 'oid' value="<?= $res['id'] ?>">
        <input type = 'submit' value = '+'>
      </form>
      <?php if($query->rowCount()){while($row = $query->fetch()){?>
        <div class="object1">
          <div class = 'room_info'>
            <h4>Кількість: <?= $row['number'] ?></h4>
            <br>
            <h4><?= $row['title'] ?></h4>
            <br>
          </div>
          <form method = 'post' action = "add_edit_personal.php">
            <input type = 'hidden' name = 'who' value = <?=$row['id']?>>
            <input class = 'hided' type = 'submit' value="">
          </form>
          <form method = "post" action = "add_edit_room.php" class = 'upp'>
            <input type = 'hidden' name = 'oid' value = <?=$res['id']?>>
            <input type = 'hidden' name = 'who' value = <?=$row['id']?>>
            <input class= "room_edit" type = 'submit' value= "Редагувати" >
          </form>
        </div>
      <?php }}?>
    </div>
</html>
