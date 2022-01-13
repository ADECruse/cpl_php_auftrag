<?php
    $status = $_POST["status"];
    $givenname = $_POST["givenname"];
    $familyname = $_POST["familyname"];
    $email = $_POST["email"];
    $auftrag = $_POST["auftrag"];
    $comment = $_POST["message"];

    $servername = 'db1523.mydbserver.com';
    $username = 'p174834';
    $password = 'S8ltia4,c*ackz';
    $dbname = 'usr_p174834_6';

    try {
        $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    } 
    catch(PDOException $e) {
        echo "ERROR: Connection failed: " . $e->getMessage();
    }
    try {
        $connection->prepare("UPDATE cpl_orders SET order_status = ?, usr_givenname = ?, usr_familyname = ?, usr_email = ?, cpl_comment = ? WHERE ordernumber = ?")->execute([$status, $givenname, $familyname, $email, $comment, $auftrag]);
        echo("<h1>Success!</h1><br><button class'button'><a href='index.php'>Zurück</a></button>");
    } 
    catch (PDOException $e) {
        echo("ERROR: Could not execute $connection. " . $e->getMessage());
    }
      
?>