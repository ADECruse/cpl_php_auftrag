<?php
    // Start the session
    session_start();
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
    try {
        $stmt = $connection->prepare("SELECT * FROM `cpl_mediatitles` WHERE ordernumber = ?;");
        $stmt->execute([$auftrag]);
        $mediatitles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } 
    catch (PDOException $e) {
        die("ERROR: Could not execute $stmt. " . $e->getMessage());
    }

    $connection = null;

    //print_r($order);
    // print_r($mediatitles);
    // var_dump($_SESSION["orders"]);
    //echo isset($_SESSION["orders"]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">
    <title><?php echo $auftrag; ?></title>
</head>
<body>
    <main class="grid-container">
<?php    
    echo '
    <section id="details-form">
        <form  action="update_order.php" method="post">
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
            // Then send email
            }
                
    echo '<h2 class="">Auftrag Details:</h2>
    </section>';
    echo '
        <section id="quell">
            <h3>Quellmedien</h3>
            <div>';
    if(isset($_SESSION["orders"])){
        foreach($_SESSION["orders"] as $row){
            if ($row["auftrag"] == $auftrag) {
                echo $row['quellmedien'];
            }
        }
    }
    echo '</div>
        </section>
        <section id="ziel">
            <h3>Zielmedien</h3>
            <div>';
    if(isset($_SESSION["orders"])){
        foreach($_SESSION["orders"] as $row){
            if ($row["auftrag"] == $auftrag) {
                echo $row['zielmedien'];
            }
        }
    }
    echo '</div>
        </section>
    ';

    echo 
    '
    <section class="" id="sonstiges">
        <h2 class="">Sonstiges:</h2>
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
    
    <section id="address">
        <h2 class="">Adresse:</h2>  
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
    </section> ';

    $vhs = array();
    $vhsc = array();
    $film8mm = array();
    $film16mm = array();
    $video8 = array();
    $minidv = array();
    $betamax = array();
    $video2000 = array();
    $dia = array();
    $aps = array();
    $foto = array();
    $lp = array();
    $single = array();

    echo '<section id="media">';

    if (!empty($mediatitles)) {
        echo '<h2 class="">Media Titel:</h2>';
    }
     
    foreach ($mediatitles as $row) {
        switch ($row["mediatype"]) {
            case 'vhs':
                array_push($vhs, $row);
                break;
            case 'vhsc':
                array_push($vhsc, $row);
                break;
            case '8mm':
                array_push($film8mm, $row);
                break;
            case '16mm':
                array_push($film16mm, $row);
                break;
            case 'video8':
                array_push($video8, $row);
                break;
            case 'minidv':
                array_push($minidv, $row);
                break;
            case 'betamax':
                array_push($betamax, $row);
                break;
            case 'video2000':
                array_push($video2000, $row);
                break;
            case 'dias':
                array_push($dia, $row);
                break;   
            case 'aps':
                array_push($aps, $row);
                break;
            case 'foto':
                array_push($foto, $row);
                break;
            case 'lp':
                array_push($lp, $row);
                break;
            case 'single':
                array_push($single, $row);
                break;
        }
    }
    
    function createMediaTable($arr)
    {
        if (!empty($arr)) {
            echo '<table>
                <caption>' . strtoupper($arr[0]["mediatype"]) . '</caption>
                <thead>
                    <tr>
                        <th>Title Now</th>
                        <th>Title New</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($arr as $title) {
                echo '<tr>';
                echo '<td>' . $title["titlenow"] . '</td>';
                echo '<td>' . $title["titlenew"] . '</td>';
                echo '</tr>';
            }            
            echo ' 
                    </tbody>                 
                </table>
            ';
        }
    }

    createMediaTable($vhs);
    createMediaTable($vhsc);
    createMediaTable($film8mm);
    createMediaTable($film16mm);
    createMediaTable($video8);
    createMediaTable($minidv);
    createMediaTable($betamax);
    createMediaTable($video2000);
    createMediaTable($dia);
    createMediaTable($aps);
    createMediaTable($foto);
    createMediaTable($lp);
    createMediaTable($single);

    echo '</section>';
/*
   vhs
   vhsc
   8mm
   16mm
   video8
   minidv
   betamax
   video2000
   dia
   aps
   foto
   lp
   single
   */
    // echo '<table>
    //         <caption>VHSc</caption>
    //         <thead>
    //             <tr>
    //             <th>Title Now</th>
    //             <th>Title New</th>
    //             </tr>
    //         </thead>
    //         <tbody>';
    // foreach ($vhsc as $title) {
    //     echo '<tr>';
    //     echo '<td>' . $title["titlenow"] . '</td>';
    //     echo '<td>' . $title["titlenew"] . '</td>';
    //     echo '</tr>';
    // }            
    // echo ' 
    // </tbody>                 
    //     </table>
    // </section>
    // ';



   
 
    
    
?>
    </main>
</body>
</html>