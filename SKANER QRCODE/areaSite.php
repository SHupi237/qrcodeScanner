<?php
    session_start();
    $_SESSION['id']=$_GET['id'];
    $_SESSION['area_id']=$_GET['area_id'];
    $_SESSION['name']=$_GET['name'];
    $_SESSION['surname']=$_GET['surname'];

    $serverName ='localhost';
    $rootName ='root';
    $password ='';
    $dbName ='pracownicy';
    $dsn = "mysql:host=$serverName;dbname=$dbName;charset=UTF8";

    
        $time=date('Y-m-d H:i:s');
        echo '<h2>Data zalogowania:' . $time . '</h2>';
        $pdo = new PDO($dsn, $rootName, $password);
        if ($pdo) {
            $sth = $pdo->prepare('SELECT `name` FROM `area` WHERE `id`= ?');
            $sth->execute(array($_GET['area_id']));
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            $result = json_encode($result);

            $request = $pdo->prepare('SELECT `id`,`name`,`surname` FROM `users` WHERE `id`=?');
            $request->execute(array($_SESSION['id']));
            $resultForRequest = $request->fetch(PDO::FETCH_ASSOC);
            $resultForRequest = json_encode($resultForRequest);
            echo '<h1>Witaj na stronie dzialu:' . json_decode($result)->name . '<br>użytkownik:' . json_decode($resultForRequest)->name . ' ' . json_decode($resultForRequest)->surname . '</h1>' ;

            $check = $pdo->prepare('SELECT * FROM `work_days` WHERE `user_id`=?');
            $check->execute(array($_SESSION['id']));
            $checkResult = $check->fetch(PDO::FETCH_ASSOC);
            
            if(is_array($checkResult)){
                $logInUser = $pdo -> prepare('UPDATE `work_days` SET `log_in`=?  WHERE `user_id` = ?');
                $logInUser -> execute(array($time,$_SESSION['id']));
            }else{
                $insert = $pdo->prepare('INSERT INTO `work_days`(id,user_id,log_in,log_out,area_id)VALUE(null,?,?,null,?)');
                $insert->execute(array($_SESSION['id'], $time,$_SESSION['area_id']));
            }
            
            $tableRequest = $pdo->prepare('SELECT * FROM `users` WHERE `area_id`=?');
            $tableRequest->execute(array($_GET['area_id']));
            $resultForTableRequest = $tableRequest->fetchAll(PDO::FETCH_ASSOC);
            $resultForTableRequest = json_encode($resultForTableRequest);
            
            $getArea = $pdo->prepare('SELECT `log_in`,`log_out` FROM `work_days` WHERE `area_id`=?');
            $getArea->execute(array($_SESSION['area_id']));
            $resultGetArea = $getArea->fetchAll(PDO::FETCH_ASSOC);
            $resultGetAreaArray = json_encode($resultGetArea);
            echo '<table>';
                echo '<tr>';
                    echo '<th>Pracownicy</th><th>Godzina zalogowania</th><th>Godzina wylogowania</th>';
                echo '</tr>';
            for ($i=0; $i<count(json_decode($resultForTableRequest)); $i++) {
                echo $i;
                echo '<tr>';
                    echo '<td>'. json_decode($resultForTableRequest)[$i]->name . ' ' . json_decode($resultForTableRequest)[$i]->surname . '</td>' . '<td>' . json_decode($resultGetAreaArray)[$i]->log_in . '</td>' . '<td>'. json_decode($resultGetAreaArray)[$i]->log_out  .'</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<a href="./index.php">Zaloguj kolejną osobę</a><br>';
            echo '<a href="./logOutUser.php">Wyloguj się</a>';
        }
?>