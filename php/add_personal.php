<?php
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
  if(isset($_POST['name'])){
    if($_POST['who'] == "working"){
      try{
        $query = $pdo->prepare('insert into users(id, name, last_name, midle_name, login, mail, password, status)values(null, ?, ?, ?, ?, ?, ?, ?)');
        $query->bindValue(1, fixi($_POST['name']), PDO::PARAM_STR);
        $query->bindValue(2, fixi($_POST['last_name']), PDO::PARAM_STR);
        $query->bindValue(3, fixi($_POST['midle_name']), PDO::PARAM_STR);
        $query->bindValue(4, fixi($_POST['login']), PDO::PARAM_STR);
        $query->bindValue(5, fixi($_POST['mail']), PDO::PARAM_STR);
        $query->bindValue(6, password_hash(fixi($_POST['pass']), PASSWORD_DEFAULT), PDO::PARAM_STR);
        $query->bindValue(7, "working", PDO::PARAM_STR);
        $query->execute();
        $query = $pdo->prepare('insert into room_workers(user_id, office_id) values(?, ?)');
        $query->bindValue(1, $pdo->lastInsertId(), PDO::PARAM_INT);
        $query->bindValue(2, $_SESSION['OID'], PDO::PARAM_INT);
        $query->execute();
        header('Location: admin.php');
        exit();
      }
      catch(PDOException $e){
        $err = "<h4 style = 'color:red'>Пошта або логін вже зайняті</h4>";
      }
    }
    else{
      $monday = 0;
      $tuesday = 0;
      $wednesday = 0;
      $thursday = 0;
      $friday = 0;
      $saturday = 0;
      $sunday = 0;
      foreach ($_POST['days'] as $item) {
        $$item = 1;
      }
      try{
        $query = $pdo->prepare('insert into users(id, name, last_name, midle_name, login, mail, password, status)values(null, ?, ?, ?, ?, ?, ?, ?)');
        $query->bindValue(1, fixi($_POST['name']), PDO::PARAM_STR);
        $query->bindValue(2, fixi($_POST['last_name']), PDO::PARAM_STR);
        $query->bindValue(3, fixi($_POST['midle_name']), PDO::PARAM_STR);
        $query->bindValue(4, fixi($_POST['login']), PDO::PARAM_STR);
        $query->bindValue(5, fixi($_POST['mail']), PDO::PARAM_STR);
        $query->bindValue(6, password_hash(fixi($_POST['pass']), PASSWORD_DEFAULT), PDO::PARAM_STR);
        $query->bindValue(7, "service", PDO::PARAM_STR);
        $query->execute();
        $query = $pdo->prepare('insert into rob_graphik (user_id, work_type, monday, tuesday, wednesday, thursday, friday, sathurday, sunday, office_id) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $query->bindValue(1, $pdo->lastInsertId(), PDO::PARAM_INT);
        $query->bindValue(2, fixi($_POST['role']), PDO::PARAM_STR);
        $query->bindValue(3, $monday, PDO::PARAM_INT);
        $query->bindValue(4, $tuesday, PDO::PARAM_INT);
        $query->bindValue(5, $wednesday, PDO::PARAM_INT);
        $query->bindValue(6, $thursday, PDO::PARAM_INT);
        $query->bindValue(7, $friday, PDO::PARAM_INT);
        $query->bindValue(8, $saturday, PDO::PARAM_INT);
        $query->bindValue(9, $sunday, PDO::PARAM_INT);
        $query->bindValue(10, $_SESSION['OID'], PDO::PARAM_INT);
        $query->execute();
        header('Location: admin.php');
        exit();
      }
      catch(PDOException $e){
        $err = "<h4 style = 'color:red'>Пошта або логін вже зайняті</h4>";
      }
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Додати робітника</title>
  <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
  <script src = '../JS/add_edit_personal.js'>
  <?php
    if(isset($monday)){
      echo("sysf()");
    }else{
      echo("sysh()");
    }?>
</script>
  <link rel = 'stylesheet' href = '../styles/add_edit_personal.css'>
</head>
<body>
  <div id = 'form-container'>
    <form method = 'post' action = 'add_personal.php' onsubmit="return(validate(this))">
      <label>
        <input type = 'radio' name = 'who' onclick="sysh()" <?php if(!isset($monday))echo("checked = 'checked'");?> value = 'working'>Робочий персонал<br>
      </label>
      <label>
        <input type = 'radio' name = 'who' onclick="sysf()" <?php if(isset($monday))echo("checked = 'checked'");?> value = 'service'>Обслуговючий персонал<br>
      </label>
      <input type = 'text' name = 'name' placeholder="Ім'я" <?php if(isset($err))echo("value = '{$_POST['name']}'")?>>
      <br>
      <input type = 'text' name = 'last_name' placeholder="Прізвище" <?php if(isset($err))echo("value = '{$_POST['last_name']}'")?>>
      <br>
      <input type = 'text' name = 'midle_name' placeholder="По батькові" <?php if(isset($err))echo("value = '{$_POST['midle_name']}'")?>>
      <br>
      <input type = 'text' name = 'login' placeholder="Логін" <?php if(isset($err))echo("value = '{$_POST['login']}'")?>>
      <br>
      <input type = 'text' name = 'mail' placeholder="Електронна адреса" <?php if(isset($err))echo("value = '{$_POST['mail']}'")?>>
      <br>
      <input type = 'password' name = 'pass' placeholder="Пароль" <?php if(isset($err))echo("value = '{$_POST['pass']}'")?>>
      <br>
      <input type = 'password' name = 'pass_repeat' placeholder="Повторіть пароль" <?php if(isset($err))echo("value = '{$_POST['pass_repeat']}'")?>>
      <br>
      <div id = 'sys' style="
      <?php if(isset($monday))echo('display: block');else{echo('display: none');}?>">
        <input type = 'text' name = 'role' placeholder = 'Вид діяльності' <?php if(isset($err))echo("value = '{$_POST['role']}'")?>>
        <label>
          <input type = 'checkbox' name = 'days[]' value = 'monday' <?php if(isset($monday) && $monday==1)echo("checked = 'checked'")?>>Понеділок
        </label>
        <label>
          <input type = 'checkbox' name = 'days[]' value = 'tuesday' <?php if(isset($tuesday) && $tuesday==1)echo("checked = 'checked'")?>>Вівторок
        </label>
        <label>
          <input type = 'checkbox' name = 'days[]' value = 'wednesday' <?php if(isset($wednesday) && $wednesday==1)echo("checked = 'checked'")?>>Середа
        </label>
        <label>
          <input type = 'checkbox' name = 'days[]' value = 'thursday' <?php if(isset($thursday) && $thursday==1)echo("checked = 'checked'")?>>Четверг
        </label>
        <label>
        <input type = 'checkbox' name = 'days[]' value = 'friday' <?php if(isset($friday) && $friday==1)echo("checked = 'checked'")?>>Пятниця
        </label>
        <label>
          <input type = 'checkbox' name = 'days[]' value = 'saturday' <?php if(isset($saturday) && $saturday==1)echo("checked = 'checked'")?>>Субота
        </label>
        <label>
        <input type = 'checkbox' name = 'days[]' value = 'sunday' <?php if(isset($sunday) && $sunday==1)echo("checked = 'checked'")?>>Неділя
        </label>
      </div>
      <input type = 'submit' value = 'Зареєструвати'>
      <?php if(isset($err))echo($err);?>
    </form>
  </div>
  <div id = 'left_container'>
    <form method = 'post' action = ''>
      <input id = 'home' type = 'submit' value = '' name = 'exit'>
    </form>
  </div>
</body>
