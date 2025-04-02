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
  </head>
  <body>
    <?php while($row = $query->fetch()){ ?>
      <h4><?= "{$row['last_name']} {$row['name']} {$row['midle_name']} {$row['id']}"?></h4>
      <form method="post" action = "edit_personal.php">
        <input type = 'hidden' name = 'who' value="<?= $row['id'] ?>">
        <input type = 'submit' value = 'Редагувати'>
      </form>
    <?php } ?>
    <form action="add_personal.php" method="post">
      <input type = 'submit' value = 'Додати'>
    </form>
  </body>
</html>
