<?php
  session_cache_limiter('private_no_expire');
  require_once "dbc.php";
  session_start();
  if($_SESSION['STAT']!='admin' || !isset($_SESSION['OID'])){
    header('Location: ../index.php');
    exit();
  }
  if(isset($_POST['exit'])){
    header('Location: admin.php');
    exit();
  }
  $query = $pdo->prepare('select users.* from users join room_workers on users.id = room_workers.user_id where room_workers.office_id = ? union select users.* from users join rob_graphik on users.id = rob_graphik.user_id where rob_graphik.office_id = ?');
  $query->bindValue(1, $_SESSION['OID'], PDO::PARAM_INT);
  $query->bindValue(2, $_SESSION['OID'], PDO::PARAM_INT);
  $query->execute();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Список робочих</title>
    <link rel = 'stylesheet' href="../styles/workers_list.css">
    <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
  </head>
  <body>
    <div id = 'list_container'>
      <div id = 'flow'>
        <br>
        <?php while($row = $query->fetch()){ ?>
          <div class="item_container">
            <h4><?= "<b>{$row['id']}</b> {$row['last_name']} {$row['name']} {$row['midle_name']}"?></h4>
            <form method="post" action = "edit_personal.php">
              <input type = 'hidden' name = 'who' value="<?= $row['id'] ?>">
              <input type = 'submit' value = 'Редагувати'>
            </form>
          </div>
      <?php } ?>
      </div>
    </div>
    <div id = 'left_container'>
      <form method = 'post' action = ''>
        <input id = 'home' type = 'submit' value = '' name = 'exit'>
      </form>
      <form action="add_personal.php" method="post">
        <input id = 'plus' type = 'submit' value = ''>
      </form>
    </div>
  </body>
</html>
