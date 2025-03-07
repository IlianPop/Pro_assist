<?php
  require_once "dbc.php";
  session_start();
  if($_SESSION['STAT']!='supadmin'){
    header('Location: ../index.php');
    exit();
  }
  $query = $pdo->prepare('select office.*, users.name as aname from office join users where office.admin_id = users.id');
  $query->execute();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Панель супер адміністратора</title>
    <link rel = "stylesheet" href = "../styles/subadmin_style.css">
    <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
  </head>
  <body>
    <div class="container">
      <form action = "add_edit_office.php" method="post" class = "object1">
        <input type = 'submit' value = '+'>
      </form>
      <?php if($query->rowCount()){while($row = $query->fetch()){?>
        <form method="post" action="add_edit_office.php" class = "object1">
          <h4><?= $row['aname'] ?></h4>
          <br>
          <h4><?= $row['name'] ?></h4>
          <br>
          <input type = 'hidden' name = 'who' value = <?=$row['id']?>>
          <input type = 'submit' value= "Редагувати">
        </form>
      <?php }}?>
    </div>
</html>
