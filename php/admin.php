<?php
  require_once "dbc.php";
  session_start();

  if($_SESSION['STAT']!='admin'|| !isset($_SESSION['OID']) ){
    header('Location: ../index.php');
    exit();
  }
  if(isset($_POST['exit'])){
    unset($_SESSION['STAT']);
    unset($_SESSION['NAME']);
    unset($_SESSION['MIDLE_NAME']);
    unset($_SESSION['LAST_NAME']);
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
      <div id = 'objectPanel'>
        <div id = 'objects'>
          <?php if($query->rowCount()){while($row = $query->fetch()){?>
            <div class="object1">
              <form method = 'post' action = "fill_room.php">
                <input type = 'hidden' name = 'who' value = <?=$row['id']?>>
                <button type="submit">
                  <div class = 'room_info'>
                    <h4>Кількість: <?= $row['number'] ?></h4>
                    <br>
                    <h4><?= $row['title'] ?></h4>
                    <br>
                  </div>
                </button>
              </form>
              <div class = 'editButton'>
                <form method = "post" action = "add_edit_room.php" class = 'upp'>
                  <input type = 'hidden' name = 'who' value = <?=$row['id']?>>
                  <input class= "room_edit" type = 'submit' value= "Редагувати" >
                </form>
              </div>
            </div>
          <?php }}?>
        </div>
      </div>
    </div>
    <div id = 'left_container'>
      <form method = 'post' action = 'hand_made.php'>
        <input type = 'hidden' name = 'who' value = ''>
        <input id = 'hand_made_setting' type = 'submit' value = ''>
      </form>
      <form action = "add_edit_room.php" method="post">
        <input id = 'plus' type = 'submit' value = ''>
      </form>
      <form action = "workers_list.php" method="post">
        <input id = 'pers' type = 'submit' value = ''>
      </form>
      <form action = "" method="post">
        <input id = 'exit' type = 'submit' value = '' name = 'exit'>
      </form>
    </div>
  </body>
</html>
