<?php
    // Start the session
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Auftrag</title>
</head>
<body>
    <main>
        <header>
            <h1>Aufträge</h1>
        </header>
        <?php require 'fetch_all_orders.php';?>
    </main>  
</body>
</html>