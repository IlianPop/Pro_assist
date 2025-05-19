<?php
  session_cache_limiter('private_no_expire');
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
            $query = $pdo->prepare('select id from office where admin_id = ?');
            $query->bindValue(1, $res['id'], PDO::PARAM_STR);
            $query->execute();
            $_SESSION['OID'] = $query->fetch()['id'];
            header("Location: php/admin.php");
            exit();
            break;
          case('working'):
            $query = $pdo->prepare('select room_workers.office_id as id, room_workers.room_id as roomId from users join room_workers on users.id = room_workers.user_id where users.id = ?');
            $query->bindValue(1, $res['id'], PDO::PARAM_STR);
            $query->execute();
            $res = $query->fetch();
            if($res['roomId'] !== null){
              $_SESSION['OID'] = $res['id'];
              header("Location: php/employee.php");
              exit();
              break;
            }
            else{
              $err = 'Ви не привязані до жодної кімнати';
              break;
            }
          case('service'):
            $query = $pdo->prepare('select rob_graphik.office_id as id from users join rob_graphik on users.id = rob_graphik.user_id where users.id = ?');
            $query->bindValue(1, $res['id'], PDO::PARAM_STR);
            $query->execute();
            $_SESSION['OID'] = $query->fetch()['id'];
            header("Location: php/service.php");
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
