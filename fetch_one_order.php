<?php
// Start the session
session_start();
$auftrag = $_POST["auftrag"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $auftrag; ?></title>
</head>
<body>
    
</body>
</html>

<?php
    
    // $auftrag = $_POST["auftrag"];
 
    $servername = 'db1523.mydbserver.com';
    $username = 'p174834';
    $password = 'S8ltia4,c*ackz';
    $dbname = 'usr_p174834_6';

    try {
        $connection = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "connection success!";
    } 
    catch(PDOException $e) {
        echo "ERROR: Connection failed: " . $e->getMessage();
    }
    try {
        $stmt = $connection->prepare("SELECT * FROM cpl_orders WHERE ordernumber = ?");
        $stmt->execute([$auftrag]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        // echo $auftrag;
    } 
    catch (PDOException $e) {
        die("ERROR: Could not execute $stmt. " . $e->getMessage());
    }

    $connection = null;

    //print_r($order);
    // var_dump($_SESSION["orders"]);
    //echo isset($_SESSION["orders"]);
    
    echo '
    <form action="update_order.php" method="post">
        <table>
            <tr>
                <th>Auftrag</th>
                <td>' . $order["ordernumber"] . '</td>
            </tr>
            <tr>
                <th>Vorname</th>
                <td>
                    <input type="text" name="givenname" value="' . $order["usr_givenname"]. '">
                </td>
            </tr>
            <tr>
                <th>Nachname</th>
                <td>
                    <input type="text" name="familyname" value="' . $order["usr_familyname"] .'">
                </td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <input type="email" name="email" value="' . $order["usr_email"] . '">
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                <select name="status">
                <option disabled value="">Please select one</option>
                <option value=-1';
                echo ($order["order_status"] == -1) ? ' selected' : '';
                echo '>Auftrag angelegt</option>';
                echo '<option value=1';
                echo ($order["order_status"] == 1) ? ' selected' : ''; 
                echo '>Warten auf Digitalisierung</option>';
                echo '<option value=2';
                echo ($order["order_status"] == 2) ? ' selected' : '';
                echo '>Digitalisierung läuft</option>';
                echo '<option value=3';
                echo ($order["order_status"] == 3) ? ' selected' : '';
                echo '>Warten auf Weiterverarbeitung</option>';
                echo '<option value=4';
                echo ($order["order_status"] == 4) ? ' selected' : '';
                echo '>Weiterverarbeitung läuft</option>';
                echo '<option value=5';
                echo ($order["order_status"] == 5) ? ' selected' : '';
                echo '>Warten auf Druck / Rechnung</option>';
                echo '<option value=6';
                echo ($order["order_status"] == 6) ? ' selected' : '';
                echo '>Kunde benachrichtigen</option>';
                echo '<option value=7';
                echo ($order["order_status"] == 7) ? ' selected' : '';
                echo '>Warten auf Abholung (Kunde)</option>';
                echo '<option value=8';
                echo ($order["order_status"] == 8) ? ' selected' : '';
                echo '>Auftrag verpacken</option>';
                echo '<option value=9';
                echo ($order["order_status"] == 9) ? ' selected' : '';
                echo '>Warten auf Versand</option>';
                echo '<option value=10';
                echo ($order["order_status"] == 10) ? ' selected' : '';
                echo '>Sonderfall</option>';
                echo '<option value=11';
                echo ($order["order_status"] == 11) ? ' selected' : '';
                echo '>Abgeholt vom Kunden</option>';
                echo '<option value=12'; 
                echo ($order["order_status"] == 12) ? ' selected' : '';
                echo '>Versandt</option>';
            echo '</select>
                </td>
            </tr>
            <tr>
                <th>Unsere Notizen</th>
                <td><textarea>...</textarea></td>
            </tr>
        </table>
        <button class="" type="Submit" name="auftrag"
        value="' . $order["ordernumber"] . '">Speichern</button><br>
    </form>

        '; 
    if ($order["order_status"] == 6) {
        echo '<button class="">Kunde jetzt informieren</button>';
        // Then set status to 7
        }
    
    echo '
    <h2 class="">Auftrag Details:</h2>
    <section class=""> 
        <section>
            <h3>Quellmedien</h3>
            <div></div>
        </section>
        <section>
            <h3>Zielmedien</h3>
            <div></div>
        </section>
    </section>
    ';

    echo 
    '<h2 class="">Sonstiges:</h2>
    <section class="">
      <section class="">';

    if ($order["confirmedTrash"] == 1) {
        echo '<p>Medien nach den Digitalisierung entsorgen</p>';
    }
    echo '<p>Notizen von Kunde:</p>';

    if ($order["comment"] != '') {
        echo '<p>' . $order["comment"] . '</p>';
    } else {
        echo '<p><i>Keine</i></p>';
    }

    echo '   
    </section>
        <section>
            <button class=""><a href="index.php">Zurück</a></button><br>
        </section>
    </section>
    <h2 class="">Adresse:</h2>
    <section class="">  
        <address>' . 
        $order["usr_givenname"] . ' ' .
        $order["usr_familyname"] . '<br>';
    
    if ($order["usr_company"] != '') {
        echo $order["usr_company"] . '<br>';
    }

    echo       
        $order["usr_street"] . '<br>'
        . $order["usr_zip"] . ' ' . $order["usr_city"] . '<br>'
        . $order["usr_country"] . '<br>'
        . $order["usr_phone"] . '<br>' . $order["usr_email"] .
        '</address>
    </section> '
        
   
 
    
    
?>