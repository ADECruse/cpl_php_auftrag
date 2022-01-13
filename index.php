<?php

    function UpdateOrder()
    {
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
            echo(
            '<div class="alert alert-success text-center m-3" role="alert">
            Die Aktualisierung war erfolgreich!
          </div>');
        } 
        catch (PDOException $e) {
            echo("ERROR: Could not execute $connection. " . $e->getMessage());
        }
    }

    if(isset($_POST['auftrag']))
    {
        UpdateOrder();
    } 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css"> -->
    <title>Auftrag</title>
</head>
<body>
    <main class="container-fluid">
        <div class="row">
            <header class="col">
                <h1 class="my-3">Aufträge</h1>
            </header>
        </div>
        <div class="row">
            <section class="col">
                <?php require 'fetch_all_orders.php';?>
            </section>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>