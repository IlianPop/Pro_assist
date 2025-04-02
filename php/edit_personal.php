<?php
  session_cache_limiter('private_no_expire');
  require_once "dbc.php";
  session_start();
  if($_SESSION['STAT']!='admin' || !isset($_SESSION['OID'])){
    header('Location: ../index.php');
    exit();
  }
  if(!isset($_POST['who'])){
    header('Location: admin.php');
    exit();
  }
  $query = $pdo->prepare('select * from users where id = ?');
  $query->bindValue(1, $_POST['who'], PDO::PARAM_INT);
  $query->execute();
  $res = $query->fetch();
  $res2 = null;
  if($res['status']=='service'){
    $query = $pdo->prepare('select * from rob_graphik where user_id = ?');
    $query->bindValue(1, $_POST['who'], PDO::PARAM_INT);
    $query->execute();
    $res2=$query->fetch();
  }
  if(isset($_POST['delete'])){
    $query = $pdo->prepare('delete users, rob_graphik, room_workers from users left join rob_graphik on users.id = rob_graphik.user_id left join room_workers on users.id = room_workers.user_id where users.id = ?');
    $query->bindParam(1, $_POST['who'], PDO::PARAM_INT, 1000);
    $query->execute();
    header('Location: workers_list.php');
    exit();
  }
  if(isset($_POST['name'])){
    $query = $pdo->prepare('update users set name = ?, last_name = ?, midle_name = ?, login = ?, mail = ? where id = ?');
    $query->bindValue(1, fixi($_POST['name']), PDO::PARAM_STR);
    $query->bindValue(2, fixi($_POST['last_name']), PDO::PARAM_STR);
    $query->bindValue(3, fixi($_POST['midle_name']), PDO::PARAM_STR);
    $query->bindValue(4, fixi($_POST['login']), PDO::PARAM_STR);
    $query->bindValue(5, fixi($_POST['mail']), PDO::PARAM_STR);
    $query->bindValue(6, $_POST['who'], PDO::PARAM_INT);
    $query->execute();
    if(isset($_POST['pass'])){
      $query = $pdo->prepare('update users set pass = ? where id = ?');
      $query->bindValue(1, password_hash($_POST['pass'], PASSWORD_DEFAULT), PDO::PARAM_STR);
      $query->bindValue(2, $_POST['who'], PDO::PARAM_INT);
      $query->execute();
    }
    header('Location: workers_list.php');
    exit();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Редагування робочого</title>
    <link rel = 'stylesheet' href = '../styles/add_edit_office.css'>
    <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
    <script src="../JS/add_edit_personal.js"></script>
  </head>
  <body>
    <div class="container">
      <form method = 'post' action = 'edit_personal.php' onsubmit="return(validate(this))">
        <input type = 'hidden' name = 'who' value="<?= $_POST['who'] ?>">
        <input type = 'text' name = 'name' placeholder="Ім'я" value = '<?= $res['name'] ?>'>
        <br>
        <input type = 'text' name = 'last_name' placeholder="Прізвище" value = '<?= $res['last_name'] ?>'>
        <br>
        <input type = 'text' name = 'midle_name' placeholder="По батькові" value = '<?= $res['midle_name'] ?>'>
        <br>
        <input type = 'text' name = 'login' placeholder="Логін" value = '<?= $res['login'] ?>'>
        <br>
        <input type = 'text' name = 'mail' placeholder="Електронна адреса" value = '<?= $res['mail'] ?>'>
        <br>
        <input type = 'password' name = 'pass' placeholder="Пароль">
        <br>
        <input type = 'password' name = 'pass_repeat' placeholder="Повторіть пароль">
        <br>
        <div id = 'sys' style="<?php if($res['status']=='service')echo('display: block');else{echo('display: none');}?>">
          <input type = 'text' name = 'role' placeholder = 'Вид діяльності' <?php if(isset($res2['role']))echo("value = '{$res2['role']}'")?>>
          <label>
            <input type = 'checkbox' name = 'days[]' value = 'monday' <?php if(isset($res2['monday']) && $res2['monday']==1)echo("checked = 'checked'")?>>Понеділок
          </label>
          <label>
            <input type = 'checkbox' name = 'days[]' value = 'tuesday' <?php if(isset($res2['tuesday']) && $res2['tuesday']==1)echo("checked = 'checked'")?>>Вівторок
          </label>
          <label>
            <input type = 'checkbox' name = 'days[]' value = 'wednesday' <?php if(isset($res2['wednesday']) && $res2['wednesday']==1)echo("checked = 'checked'")?>>Середа
          </label>
          <label>
            <input type = 'checkbox' name = 'days[]' value = 'thursday' <?php if(isset($res2['thursday']) && $res2['thursday']==1)echo("checked = 'checked'")?>>Четверг<br>
          </label>
          <label>
          <input type = 'checkbox' name = 'days[]' value = 'friday' <?php if(isset($res2['friday']) && $res2['friday']==1)echo("checked = 'checked'")?>>Пятниця
          </label>
          <label>
            <input type = 'checkbox' name = 'days[]' value = 'saturday' <?php if(isset($res2['saturday']) && $res2['saturday']==1)echo("checked = 'checked'")?>>Субота
          </label>
          <label>
          <input type = 'checkbox' name = 'days[]' value = 'sunday' <?php if(isset($res2['sunday']) && $res2['sunday']==1)echo("checked = 'checked'")?>>Неділя
          </label>
        </div>
        <input type = 'submit' value = 'Зберегти'>
        <?php if(isset($err))echo($err);?>
      </form>
      <form method="post" action="edit_personal.php">
        <input type = 'hidden' name = 'who' value="<?= $_POST['who'] ?>">
        <input type = 'hidden' name = 'delete' value='true'>
        <input type="submit" value="Видалити">
      </form>
    </div>
  </body>
</html>
