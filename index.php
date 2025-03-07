<?php
  require_once "php/dbc.php";
  if(isset($_POST['login'])&&isset($_POST['pass'])){
    session_start();
    $err = "";
    $query = $pdo->prepare('select * from users where login = ?');
    $query->bindValue(1, fixi($_POST['login']), PDO::PARAM_STR);
    $query->execute();
    if($query->rowcount()!=0){
      $res = $query->fetch();
      if(password_verify($_POST['pass'], $res['password'])){
        $_SESSION['ID']=fixi($res['id']);
        $_SESSION['STAT']=fixi($res['status']);
        $_SESSION['NAME']=fixi($res['name']);
        $_SESSION['MIDLE_NAME']=fixi($res['midle_name']);
        $_SESSION['LAST_NAME']=fixi($res['last_name']);
        switch($res['status']){
          case('supadmin'):
            header("Location: php/supadmin.php");
            exit();
            break;
          case('admin'):
            header("Location: php/admin.php");
            exit();
            break;
          case('employee'):
            header("Location: php/employye.php");
            exit();
            break;
          case('staff'):
            header("Location: php/staff.php");
            exit();
            break;
        }
      }
      else{
        $err = "Пароль неправильний";
      }
    }
    else{
      $err = "Нема такого користувача";
    }
  }
 ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Сторінка входу</title>
    <link rel = 'stylesheet' href = 'styles/index_style.css'>
    <link rel = 'icon' type="image/jpg" href="styles/system_images/site.png">
    <script src="JS/index.js" ></script>
  </head>
  <body>
    <div class = 'form-container'>
      <form  method="post" action="index.php" onsubmit="return(validate(this))">
        <input type = "text" placeholder="Логін" name = "login"<?php if(isset($_POST['login']))echo("value = '" . $_POST['login'] . "'")?>>
        <input type = "password" placeholder="Пароль" name = "pass"<?php if(isset($_POST['pass']))echo("value = '" . $_POST['pass'] . "'")?>>
        <input type = 'submit' value = "Ввійти">
      </form>
      <?php if(isset($err))echo("<h5>" . $err . "</h5>") ?>
    </div>
  </body>
</html>
