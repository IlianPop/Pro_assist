<!DOCTYPE html>
<html>
  <head>
    <title>Сторінка входу</title>
    <link rel = 'stylesheet' href = 'styles/index_style.css'>
  </head>
  <body>
    <div class = 'form-container'>
      <form  method="post" action="index.php">
        <input type = "text" placeholder="Логін" name = "login"<?php if(isset($_POST['login']))echo("value = '" . $_POST['login'] . "'")?>>
        <input type = "password" placeholder="Пароль" name = "pass"<?php if(isset($_POST['pass']))echo("value = '" . $_POST['pass'] . "'")?>>
        <input type = 'submit' value = "Ввійти">
      </form>
    </div>
  </body>
</html>
