<?php
    $status = $_POST["status"];
    $givenname = $_POST["givenname"];
    $familyname = $_POST["familyname"];
    $email = $_POST["email"];
    $auftrag = $_POST["auftrag"];

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
        $connection->prepare("UPDATE cpl_orders SET order_status = ?, usr_givenname = ?, usr_familyname = ?, usr_email = ? WHERE ordernumber = ?")->execute([$status, $givenname, $familyname, $email, $auftrag]);
        echo("Success!");
    } 
    catch (PDOException $e) {
        echo("ERROR: Could not execute $connection. " . $e->getMessage());
    }
      
?>