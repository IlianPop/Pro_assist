<?php
    require_once "dbc.php";
    session_start();
    if(isset($_POST['exit'])){
        header('Location: admin.php');
        exit();
    }
    if(!isset($_POST['who'])){
        header('Location: admin.php');
        exit();
    }
    if(isset($_POST['receiver'])){
        $query = $pdo->prepare('update requests set reciver = ? where id = ?');
        $query->bindValue(1, $_POST['receiver'], PDO::PARAM_INT);
        $query->bindValue(2, $_POST['who'], PDO::PARAM_INT);
        $query->execute();
        header('Location: admin.php');
        exit();
    }
    if($_SESSION['STAT']!='admin' || !isset($_SESSION['OID'])){
        header('Location: ../index.php');
        exit();
    }
    if(isset($_POST['who'])){
        $query = $pdo->prepare('SELECT users.id as uid, requests.reciver as rid, users.name as name, users.last_name as last_name, users.midle_name as midle_name, rooms.title as roomTitle, requests.title as requestTitle, requests.description as description, requests.type as type, requests.status as status, requests.time as timee FROM requests join users on requests.sender = users.id join room_workers on users.id = room_workers.user_id join rooms on room_workers.room_id = rooms.id where requests.id = ?;');
        $query->bindParam(1,$_POST['who'], PDO::PARAM_INT, 100);
        $query->execute();
        $res = $query->fetch();
    }
?>
<!DOCTYPE html>
    <head>
        <title>Ручний режим</title>
        <link rel="stylesheet" href="../styles/request_admin.css">
        <link rel = 'icon' type="image/jpg" href="../styles/system_images/site.png">
    </head>
    <body>
        <div id = 'container'>
            <div id = 'sender'>
                <h3><?= $res['uid'] . ' ' . $res['last_name'] . ' ' . $res['midle_name'] . ' ' . $res['roomTitle'] ?></h3>
            </div>
            <div id = 'info'>
                <h3><?= $res['requestTitle'] . ' ' . $res['timee'] . ' ' . $res['type'] ?><?php if($res['status']==0){echo(' Невиконано');}else{echo(' Виконано');} ?></h3>
                <p><?= $res['description'] ?></p>
            </div>
            <?php
            if($res['status'] == 0){
                $query = $pdo->prepare('SELECT users.id as id, users.name as name, users.last_name as lastName, users.midle_name as midleName, rob_graphik.monday as mo, rob_graphik.tuesday as tu, rob_graphik.wednesday as we, rob_graphik.thursday as th, rob_graphik.friday as fr, rob_graphik.saturday as sa, rob_graphik.sunday as su from users join rob_graphik on users.id = rob_graphik.user_id where rob_graphik.work_type = ?');
                $query->bindValue(1, $res['type'], PDO::PARAM_STR);
                $query->execute();
                ?>
                <form action = 'request_admin.php' method = 'POST'>
                    <input type = 'hidden' name = 'who' value = '<?= $_POST['who'] ?>'>
                    <select name = 'receiver'>
                        <?php while($row = $query->fetch()){
                            $days = '';
                            if($row['mo'] == 1){$days .= 'Пн';}else{$days .= ' ';};
                            if($row['tu'] == 1){$days .= ' Вт';}else{$days .= ' ';};
                            if($row['we'] == 1){$days .= ' Ср';}else{$days .= ' ';};
                            if($row['th'] == 1){$days .= ' Чт';}else{$days .= ' ';};
                            if($row['fr'] == 1){$days .= ' Пт';}else{$days .= ' ';};
                            if($row['sa'] == 1){$days .= ' Сб';}else{$days .= ' ';};
                            if($row['su'] == 1){$days .= ' Нд';}else{$days .= ' ';};
                            ?>
                            <option value = '<?= $row['id'] ?>' <?php if($row['id']==$res['rid']){echo('selected');} ?>><?= $row['lastName'] . ' ' . $row['name'] . ' ' . $row['midleName'] . ' ' . trim($days) ?></option>
                        <?php } ?>
                    </select>
                    <br>
                    <input type = 'submit' value = 'Зробити виконавцем'>
                    <?php } ?>
                </form>
            </div>
        <div id = 'left_container'>
            <form action = "request_admin.php" method="post">
            <input id = 'home' type = 'submit' value = '' name = 'exit'>
            </form>
        </div>
    </body>
</html>