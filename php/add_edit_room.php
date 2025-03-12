<?php
  require_once "dbc.php";
  session_start();
  if($_SESSION['STAT']!='admin'){
    header('Location: ../index.php');
    exit();
  }
  if(isset($_POST['title']) && !isset($_POST['changes']) && !isset($_POST['delete'])){
    $query1 = $pdo->prepare('insert into rooms (id, office_id, number, title) values(null, ?, ?, ?);');
    $query1->bindValue(1, fixi($_POST['oid']), PDO::PARAM_INT);
    $query1->bindValue(2, fixi($_POST['number']), PDO::PARAM_INT);
    $query1->bindValue(3, fixi($_POST['title']), PDO::PARAM_STR);
    $query1->execute();
    header('Location: admin.php');
    exit();
  }
  if(isset($_POST['who'])&&isset($_POST['changes'])){
    $query1 = $pdo->prepare('update rooms set office_id = ?, number = ?, title = ? where id = ?');
    $query1->bindValue(1, fixi($_POST['oid']), PDO::PARAM_INT);
    $query1->bindValue(2, fixi($_POST['number']), PDO::PARAM_INT);
    $query1->bindValue(3, fixi($_POST['title']), PDO::PARAM_STR);
    $query1->bindValue(3, fixi($_POST['title']), PDO::PARAM_STR);
    $query1->bindValue(4, fixi($_POST['who']), PDO::PARAM_INT);
    $query1->execute();
    header('Location: admin.php');
    exit();
  }
  if(isset($_POST['who'])&&isset($_POST['delete'])){
    $query1 = $pdo->prepare('delete from rooms where id = ?');
    $query1->bindValue(1, fixi($_POST['who']), PDO::PARAM_INT);
    $query1->execute();
    header('Location: admin.php');
    exit();
  }
  if(isset($_POST['who'])){
    $query1 = $pdo->prepare('select * from rooms where id = ?');
    $query1->bindParam(1, $_POST['who'], PDO::PARAM_INT, 1000);
    $query1->execute();
    $res = $query1->fetch();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Робота з кімнатою</title>
    <link rel = 'stylesheet' href = '../styles/add_edit_office.css'>
    <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
    <script src="../JS/add_edit_room.js" ></script>
  </head>
  <body>
    <div class = 'form-container'>
      <form method = 'post' action = 'add_edit_room.php' onsubmit="return(validate(this))">
        <input type = 'text' name = 'title' placeholder="Назва" <?php if(isset($res['title']))echo("value = '{$res['title']}'"); ?>>
        <br>
        <input type = 'text' name = 'number' placeholder="Кількість робочих місць" <?php if(isset($res['number']))echo("value = '{$res['number']}'"); ?>>
        <br>
        <input type = 'hidden' name = 'oid' value = <?= $_POST['oid']?>>
        <input type = 'submit' value = 'Зберегти'>
        <?php if(isset($_POST['who'])){ ?>
          <input type = 'hidden' name = 'who' value = <?= $_POST['who'] ?>>
          <input type = 'hidden' name = 'changes' value = 'true'>
        <?php } ?>
      </form>
      <?php if(isset($_POST['who'])){ ?>
        <form action = 'add_edit_room.php' method="post">
          <input type = 'hidden' name = 'delete' value = 'true'>
          <input type = 'hidden' name = 'who' value = '<?= fixi($_POST['who']) ?>'>
          <input type = 'submit' value = 'Видалити'>
        </form>
      <?php } ?>
    </div>
  </body>
</html>
