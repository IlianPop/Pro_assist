<?php
  session_cache_limiter('private_no_expire');
  require_once "dbc.php";
  session_start();
  if($_SESSION['STAT']!='admin' || !isset($_SESSION['OID'])){
    header('Location: ../index.php');
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
  </head>
  <body>
    <div id = 'list_container'>
      <form action="add_personal.php" method="post">
        <input type = 'submit' value = 'Додати'>
      </form>
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
    
  </body>
</html>
