<?php
    // $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
  
    // if ($contentType === "application/json; charset=UTF-8") {

    //     $content = trim(file_get_contents("php://input"));

    //     $data = json_decode($content, true); 
    // }
    
    $auftrag = $_POST["auftrag"];
 
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

    function statusNumberToText($statusnumber)
    {
        $status = "";
        switch ($statusnumber) {
            case 1:
                $status = "Warten auf Digitalisierung";
                break;
            case 2:
                $status = "Digitalisierung läuft";
                break;
            case 3:
                $status = "Warten auf Weiterverarbeitung";
                break;
            case 4:
                $status = "Weiterverarbeitung läuft";
                break;
            case 5:
                $status = "Warten auf Druck / Rechnung";
                break;
            case 6:
                $status = "Kunde benachrichtigen";
                break;
            case 7:
                $status = "Warten auf Abholung (Kunde)";
                break;
            case 8:
                $status = "Auftrag verpacken";
                break;
            case 9:
                $status = "Warten auf Versand";
                break;
            case 10:
                $status = "Sonderfall";
                break;
            case 11:
                $status = "Abgeholt vom Kunden";
                break;
            case 12:
                $status = "Versandt";
                break;               
            default:
                $status = "Auftrag angelegt";
                break;
        }
        return $status;
    }

    echo '
    <form action="" method="post">
        <table>
            <tr>
                <th>Auftrag</th>
                <td>' . $order["ordernumber"] . '</td>
            </tr>
            <tr>
                <th>Vorname</th>
                <td>' . 
                  $order["usr_givenname"] . ' ' .
                '<br>
                <input type="text" name="givenname" placeholder="Neuer Vorname"></td>
            </tr>
            <tr>
                <th>Nachname</th>
                <td>' . 
                  $order["usr_familyname"] . ' ' .
                '<br>
                <input type="text" name="familyname" placeholder="Neuer Nachname"></td>
            </tr>
            <tr>
                <th>Email</th>
                <td>' . $order["usr_email"] . '<br>
                <input type="email" name="email" placeholder="Neue Email Adresse"></td>
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td> ' . statusNumberToText($order["order_status"]) . '<br>
                <select>
                <option disabled value="">Please select one</option>
                <option value=-1>Auftrag angelegt</option>
                <option value=1>Warten auf Digitalisierung</option>
                <option value=2>Digitalisierung läuft</option>
                <option value=3>Warten auf Weiterverarbeitung</option>
                <option value=4>Weiterverarbeitung läuft</option>
                <option value=5>Warten auf Druck / Rechnung</option>
                <option value=6>Kunde benachrichtigen</option>
                <option value=7>Warten auf Abholung (Kunde)</option>
                <option value=8>Auftrag verpacken</option>
                <option value=9>Warten auf Versand</option>
                <option value=10>Sonderfall</option>
                <option value=11>Abgeholt vom Kunden</option>
                <option value=12>Versandt</option>
                </select>
                </td>
            </tr>
            <tr>
                <th>Unsere Notizen</th>
                <td><textarea>...</textarea></td>
            </tr>
        </table>
        <button class="" type="Submit">Speichern</button><br>
    </form>

        '; 
    if ($order.["order_status"] == 6) {
        echo '<buttonclass="button-primary">Kunde jetzt informieren</button>';
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
            <button class="">Zurück</button><br>
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