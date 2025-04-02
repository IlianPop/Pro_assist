<?php
  session_cache_limiter('private_no_expire');
  require_once "dbc.php";
  session_start();
  if($_SESSION['STAT']!='supadmin'){
    header('Location: ../index.php');
    exit();
  }
  if(isset($_POST['title']) && !isset($_POST['changes']) && !isset($_POST['delete'])){
    $query1 = $pdo->prepare('insert into users (id, name, last_name, midle_name, login, mail, password, status) values(null, ?, ?, ?, ?, ?, ?, ?)');
    $query1->bindValue(1, fixi($_POST['name']), PDO::PARAM_STR);
    $query1->bindValue(2, fixi($_POST['last_name']), PDO::PARAM_STR);
    $query1->bindValue(3, fixi($_POST['midle_name']), PDO::PARAM_STR);
    $query1->bindValue(4, fixi($_POST['login']), PDO::PARAM_STR);
    $query1->bindValue(5, fixi($_POST['mail']), PDO::PARAM_STR);
    $query1->bindValue(6, password_hash($_POST['pass'], PASSWORD_DEFAULT), PDO::PARAM_STR);
    $query1->bindValue(7, "admin", PDO::PARAM_STR);
    $query1->execute();
    $id1 = $pdo->lastInsertId();
    $query1 = $pdo->prepare('insert into office (id, admin_id, name) values(null, ?, ?)');
    $query1->bindValue(1, $id1, PDO::PARAM_INT);
    $query1->bindValue(2, $_POST['title'], PDO::PARAM_STR);
    $query1->execute();
    header('Location: supadmin.php');
    exit();
  }
  if(isset($_POST['who'])&&isset($_POST['changes'])){
    $query1 = $pdo->prepare('select office.*, office.id as oid, users.* from office join users where office.id = ? and users.id = office.admin_id');
    $query1->bindParam(1, $_POST['who'], PDO::PARAM_INT, 1000);
    $query1->execute();
    $res = $query1->fetch();
    $query = 'update users set name = ?, last_name = ?, midle_name = ?, login = ?, mail = ? ';
    if(isset($_POST['pass'])){
      $query .= ',`password` = ? where id = ?';
    }
    else{
      $query .= 'where id = ?';
    }
    $query1 = $pdo->prepare($query);
    $query1->bindValue(1, fixi($_POST['name']), PDO::PARAM_STR);
    $query1->bindValue(2, fixi($_POST['last_name']), PDO::PARAM_STR);
    $query1->bindValue(3, fixi($_POST['midle_name']), PDO::PARAM_STR);
    $query1->bindValue(4, fixi($_POST['login']), PDO::PARAM_STR);
    $query1->bindValue(5, fixi($_POST['mail']), PDO::PARAM_STR);
    if(isset($_POST['pass'])){
      $query1->bindValue(6, password_hash($_POST['pass'], PASSWORD_DEFAULT), PDO::PARAM_STR);
      $query1->bindValue(7, fixi($res['admin_id']), PDO::PARAM_INT);
    }
    else{
      $query1->bindValue(6, fixi($res['admin_id']), PDO::PARAM_INT);
    }
    $query1->execute();
    $query1 = $pdo->prepare('update office set name = ? where id = ?');
    $query1->bindValue(1, fixi($_POST['title']), PDO::PARAM_STR);
    $query1->bindValue(2, fixi($res['oid']), PDO::PARAM_INT);
    $query1->execute();
    header('Location: supadmin.php');
    exit();
  }
  if(isset($_POST['who'])&&isset($_POST['delete'])){
    $query1 = $pdo->prepare('select users.id as uid, office.id as oid from office join users where office.id = ? and users.id = office.admin_id');
    $query1->bindValue(1, fixi($_POST['who']), PDO::PARAM_INT);
    $query1->execute();
    $res = $query1->fetch();
    $query1 = $pdo->prepare('delete from users where id = ?');
    $query1->bindValue(1, $res['uid'], PDO::PARAM_INT);
    $query1->execute();
    $query1 = $pdo->prepare('delete from office where id = ?');
    $query1->bindValue(1, $res['oid'], PDO::PARAM_INT);
    $query1->execute();
    header('Location: supadmin.php');
    exit();
  }
  if(isset($_POST['who'])){
    $query1 = $pdo->prepare('select office.*, users.*, office.name as title,  users.name as name from office join users where office.id = ? and users.id = office.admin_id');
    $query1->bindParam(1, $_POST['who'], PDO::PARAM_INT, 1000);
    $query1->execute();
    $res = $query1->fetch();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Робота з офісами</title>
    <link rel = 'stylesheet' href = '../styles/add_edit_office.css'>
    <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
    <script src="../JS/add_edit_office.js" ></script>
  </head>
  <body>
    <div class = 'form-container'>
      <form method = 'post' action = 'add_edit_office.php' onsubmit="return(validate(this))">
        <input type = 'text' name = 'title' placeholder="Назва" <?php if(isset($res['title']))echo("value = '{$res['title']}'"); ?>>
        <br>
        <input type = 'text' name = 'name' placeholder="Ім'я" <?php if(isset($res['name']))echo("value = '{$res['name']}'"); ?>>
        <br>
        <input type = 'text' name = 'last_name' placeholder="Прізвище" <?php if(isset($res['last_name']))echo("value = '{$res['last_name']}'"); ?>>
        <br>
        <input type = 'text' name = 'midle_name' placeholder="По батькові" <?php if(isset($res['midle_name']))echo("value = '{$res['midle_name']}'"); ?>>
        <br>
        <input type = 'text' name = 'login' placeholder="Логін" <?php if(isset($res['login']))echo("value = '{$res['login']}'"); ?>>
        <br>
        <input type = 'text' name = 'mail' placeholder="Електронна адреса" <?php if(isset($res['mail']))echo("value = '{$res['mail']}'"); ?>>
        <br>
        <input type = 'password' name = 'pass' placeholder="Пароль">
        <br>
        <input type = 'password' name = 'pass_repeat' placeholder="Повторіть пароль">
        <br>
        <?php if(isset($_POST['who'])){ ?>
          <input type = 'hidden' name = 'who' value = <?= $_POST['who'] ?>>
          <input type = 'hidden' name = 'changes' value = 'true'>
        <?php } ?>
        <input type = 'submit' value = 'Зберегти'>
      </form>
      <?php if(isset($_POST['who'])){ ?>
        <form action = 'add_edit_office.php' method="post">
          <input type = 'hidden' name = 'delete' value = 'true'>
          <input type = 'hidden' name = 'who' value = '<?= fixi($_POST['who']) ?>'>
          <input type = 'submit' value = 'Видалити'>
        </form>
      <?php } ?>
    </div>
  </body>
</html>
