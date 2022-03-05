<?php 
    $serverName='localhost';
    $rootName='root';
    $password='';
    $dbName='pracownicy';
    $dsn = "mysql:host=$serverName;dbname=$dbName;charset=UTF8";
    try{
        $pdo = new PDO($dsn, $rootName, $password);
        if ($pdo) {
            //Po sprawdzeniu czy są dane w linku przechodzimy dalej
            if(isset($_GET['name']) && isset($_GET['surname']) && isset($_GET['password'])){
                //Wysyłamy zapytanie sql na serwer by sprawdzić czy istnieje pracownik na podstawie przesłanych danych
                $sth = $pdo->prepare('SELECT `id`,`area_id`,`name`, `surname`,`password` FROM `users` WHERE `name`=? AND `surname`=? AND `password`=?');
                $sth->execute(array($_GET['name'],$_GET['surname'], md5($_GET['password'])));
                $result = $sth->fetch(PDO::FETCH_ASSOC);
                echo json_encode($result);
                
            }
            
        }
        } catch (PDOException $e) {
            echo $e->getMessage();
            
        }
?>