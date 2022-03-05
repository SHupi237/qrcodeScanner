<?php
    //Stworzenie sesji zawierającej dane zalogowanego użtkownika
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
            //Zapytaniem pobieramy nazwe działu
            $sth = $pdo->prepare('SELECT `name` FROM `area` WHERE `id`= ?');
            $sth->execute(array($_GET['area_id']));
            $result = $sth->fetch(PDO::FETCH_ASSOC);
            $result = json_encode($result);
            
             //Zapytaniem pobieramy imie i nazwisko użytkownika
            $request = $pdo->prepare('SELECT `id`,`name`,`surname` FROM `users` WHERE `id`=?');
            $request->execute(array($_SESSION['id']));
            $resultForRequest = $request->fetch(PDO::FETCH_ASSOC);
            $resultForRequest = json_encode($resultForRequest);
            echo '<h1>Witaj na stronie dzialu:' . json_decode($result)->name . '<br>użytkownik:' . json_decode($resultForRequest)->name . ' ' . json_decode($resultForRequest)->surname . '</h1>' ;

            //Sprawdzamy czy dany użtkownij nie jest już zalogowany
            // $check = $pdo->prepare('SELECT * FROM `work_days` WHERE `user_id`=?');
            // $check->execute(array($_SESSION['id']));
            // $checkResult = $check->fetch(PDO::FETCH_ASSOC);
            
            // if(is_array($checkResult)){
            //     //Jeśli jest już,to po prostu uaktualniamy dane
            //     $logInUser = $pdo -> prepare('UPDATE `work_days` SET `log_in`=?,`log_out`=null,`status`=?   WHERE `user_id` = ?');
            //     $logInUser -> execute(array($time,'aktywny',$_SESSION['id']));
            // }else{
            //     //Jeśli nie jest ,to dodajemy rekord
            $insert = $pdo->prepare('INSERT INTO `work_days`(id,user_id,log_in,log_out,area_id)VALUE(null,?,?,null,?)');
            $insert->execute(array($_SESSION['id'], $time,$_SESSION['area_id']));
            // }
            
            
            //Tym zapytaniem pobieramy wszystkich użtkowników z danego działu
            $tableRequest = $pdo->prepare('SELECT u.name,u.surname FROM work_days wd JOIN users u ON wd.user_id=u.id WHERE wd.log_out IS NULL;');
            $tableRequest->execute();
            $resultForTableRequest = $tableRequest->fetchAll(PDO::FETCH_ASSOC);
            $resultForTableRequest = json_encode($resultForTableRequest);
           
           
            //Za pomocą tabeli wyświetlamy użtkowników i ich godziny zalogowania i wylogowania
            

            echo '<table>';
                echo '<tr>';
                    echo '<th>Pracownicy</th>';
                echo '</tr>';
            for ($i=0; $i<count(json_decode($resultForTableRequest)); $i++) {
                echo '<tr>';
                    echo '<td>'. json_decode($resultForTableRequest)[$i]->name . ' ' . json_decode($resultForTableRequest)[$i]->surname . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<a href="./index.php">Zaloguj kolejną osobę</a><br>';
            echo '<a href="./logOutUser.php">Wyloguj użytkownika</a>';
        }
?>