<html !DOCTYPE>
    <head>
        <meta charset="UTF-8">
        <title>Wylogowanie użytkownika</title>
    </head>
    <body>
    <h1>Wylogowywanie użytkownika</h1>
    <div style="width: 500px" id ="reader"></div>
        <script src = "node_modules/html5-qrcode/html5-qrcode.min.js"></script>
        <script src = "https://raw.githubusercontent.com/mebjas/html5-qrcode/master/minified/html5-qrcode.min.js"></script>
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src = "./logOutUserScript.js"></script>
        <?php 
            session_start();
            $time = date('Y-m-d H:i:s');
            if (isset($_GET['id'])) {
                $serverName = 'localhost';
                $rootName = 'root';
                $password = '';
                $dbName = 'pracownicy';
                $dsn = "mysql:host=$serverName;dbname=$dbName;charset=UTF8";
                    $pdo = new PDO($dsn, $rootName, $password);
                    if ($pdo) {
                        //Sprawdzanie czy taki pracownik jest
                        $sth = $pdo->prepare('SELECT `id`,`area_id`,`name`, `surname`,`password` FROM `users` WHERE `name`=? AND `surname`=?');
                        $sth->execute(array($_GET['name'],$_GET['surname']));
                        $result = $sth->fetch(PDO::FETCH_ASSOC);
                        if(is_array($result)){
                            echo $_SESSION['id'];
                            $logOutUser = $pdo -> prepare('UPDATE `work_days` SET `log_out`=? WHERE `user_id` = ?');
                            $logOutUser -> execute(array($time,$_GET['id']));
                            header('Location:./index.php');
                        }else{
                            echo 'Błąd.Wylogowywanie się nie powiodło.Skontaktuj się z administratorem';
                        }
                        
                    }
                
            }
        ?>
    </body>
</html>