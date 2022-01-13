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
        $stmt = $connection->prepare("SELECT `ordernumber`, `created`, `order_status`, `usr_company`, `usr_givenname`, `usr_familyname`, `usr_street`, `usr_zip`, `usr_city`, `usr_country`, `usr_email`, `usr_phone`, `delivery_company`, `delivery_givenname`, `delivery_familyname`, `delivery_street`, `delivery_zip`, `delivery_city`, `delivery_country`, `comment`, `count8mm`, `count16mm`, `countVhs`, `countVhsc`, `countMinidv`, `countMicromv`, `countVideo8`, `countVideo2000`, `countBetamax`, `countMc`, `countTonband`, `countLp`, `countSingle`, `countDia`, `countKb`, `countAps`, `countFoto`, `countDvd`, `countCd`, `destMedium`, `wishData`, `wishDvd`, `wishCd`, `shellDvd`, `shellCd`, `super8resolution`, `lpCleaning`, `singleCleaning`, `diaResolution`, `diaNumbering`, `diaCleaning`, `diaScratch`, `diaRoc`, `diaRotate`, `diaSlidechange`, `kbResolution`, `kbNumbering`, `kbCleaning`, `kbScratch`, `kbRoc`, `kbRotate`, `apsResolution`, `apsNumbering`, `apsScratch`, `apsRoc`, `apsRotate`, `fotoResolution`, `fotoNumbering`, `fotoRoc`, `fotoRotate`, `fotoScratch`, `confirmedTrash`, '' AS quellmedien, '' AS zielmedien FROM cpl_orders WHERE ordernumber = ?");
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

    function GetShell($arg) {
        $huelle = "";
        if ($arg == "cardboard") {
            $huelle = "Kartontasche";
        } elseif ($arg == "roundmetal") {
            $huelle = "Metalldose rund";
        } elseif ($arg == "rectmetal") {
            $huelle = "Metallbox eckig";
        } else {
            $huelle = "Papierhülle";
        }
        return $huelle;
    }

    // code that takes all results from "count" columns and combine them into one named "quellmedien"
    // $orders = [];
    $value = "";
    // foreach loop modifies the original $order array using references, later I want to change this into an array_map function for speed and simplicity
    $clean = "Klangverbesserung: Nassreinigung";
    // foreach ($order as &$row) {
        $order["quellmedien"] = $value;
        foreach ($order as $col => $col_value) {
            // if ($col == "status") {
            //     $order["statustext"] = GetStatus($order["status"]);
            // }
            
            if (is_numeric($col_value) && $col_value > 0) {
                switch ($col) {
                    case "count8mm":
                        $filmRes = "Art: Premium SD";
                        if ($order["super8resolution"] == "hd") {
                            $filmRes = "Art: Deluxe HD";
                        };
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Normal 8 / Super 8 Filme</b></p><p>$filmRes</p>";
                        break;
                    case "count16mm":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x 16 mm / Super 16 Filme</b></p>";
                        break;
                    case "countVhs":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x VHS-Kassetten</b></p>";
                        break;
                    case "countVhsc":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x VHS-C Kassetten</b></p>";
                        break;
                    case "countVideo8":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Video8 / Hi8 / Digital8-Kassetten</b></p>";
                        break; 
                    case "countMinidv":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Mini-DV / DVCAM-Kassetten</b></p>";
                        break;
                    case "countMicromv":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x MicroMV-Kassetten</b></p>";
                        break;
                    case "countVideo2000":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Video2000-Kassetten</b></p>";
                        break;
                    case "countBetamax":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Betamax-Kassetten</b></p>";
                        break;
                    case "countMc":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Musik-Kassetten (MC)</b></p>";
                        break;
                    case "countTonband":
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Tonband</b></p>";
                        break;
                    case "countLp":
                        if ($order["lpCleaning"] == 1) {   
                            $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Langspiel-Schallplatte (LP)</b></p><p>$clean</p>";
                        } else {
                            $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Langspiel-Schallplatte (LP)</b></p>";
                        }
                        break;
                    case "countSingle":
                        if ($order["singleCleaning"] == 1) {   
                            $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Single-Schallplatte</b></p><p>$clean</p>";
                        } else {
                            $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Single-Schallplatte</b></p>";
                        }
                        break;
                    case "countDia":
                        $diaResolution = "";
                        if ($order["diaResolution"] == "standard") {
                            $diaResolution = "Standard-Scan 3900 dpi";
                        } elseif ($order["diaResolution"] == "premium") {
                            $diaResolution = "Premium 4500 dpi";
                        } elseif ($order["diaResolution"] == "premium-plus") {
                            $diaResolution = "Premium-Plus 4500 dpi";
                        } else {
                            $diaResolution = "Budget-Scan 2900 dpi";
                        };
                    
                        if ($order["diaScratch"] == 1) {
                            $diaScratch = "Digitale Staub- und Kratzerentfernung";
                        };
                        if ($order["diaCleaning"] == 1) {
                            $diaCleaning = "Feucht-Intensivreinigung";
                        };
                        if ($order["diaRoc"] == 1) {
                            $diaRoc = "Automatische Farbkorrektur";
                        };
                        if ($order["diaRotate"] == 1) {
                            $diaRotate = "Bilddrehung und Spiegelung";
                        };
                        if ($order["diaSlidechange"] == 1) {
                            $diaSlidechange = "Dias in glaslose Rahmen umrahmen";
                        };
                        
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Dias</b></p><p>$diaResolution</p><p>$diaScratch</p><p>$diaCleaning</p><p>$diaRoc</p><p>$diaRotate</p><p>$diaSlidechange</p>";
                        break;
                    case "countKb":
                       
                        if ($order["kbResolution"] == "high") {
                            $kbResolution = "4500dpi";
                        } else {
                            $kbResolution = "2800dpi";
                        };

                        if ($order["kbScratch"] == 1) {
                            $kbScratch = "Digitale Staub- und Kratzerentfernung";
                        };
                        if ($order["kbCleaning"] == 1) {
                            $kbCleaning = "Manuelle Staubentfernung";
                        };
                        if ($order["kbRoc"] == 1) {
                            $kbRoc = "Automatische Farbkorrektur";
                        };
                        if ($order["kbRotate"] == 1) {
                            $kbRotate = "Bilddrehung";
                        };

                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Negative (Kleinbildstreifen)</b></p><p>$kbResolution</p><p>$kbScratch</p><p>$kbCleaning</p><p>$kbRoc</p><p>$kbRotate</p>";
                        break;
                    case "countAps":
                        if ($order["apsResolution"] == "high") {
                            $apsResolution = "3000dpi";
                        } else {
                            $apsResolution = "2000dpi";
                        };

                        if ($order["apsScratch"] == 1) {
                            $apsScratch = "Digitale Staub- und Kratzerentfernung";
                        };
                        if ($order["apsRoc"] == 1) {
                            $apsRoc = "Automatische Farbkorrektur";
                        };
                        if ($order["apsRotate"] == 1) {
                            $apsRotate = "Bilddrehung";
                        };
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Bilder auf APS-Rollen</b></p><p>$apsResolution</p><p>$apsScratch</p><p>$apsRoc</p><p>$apsRotate</p>";
                        break;
                    case "countFoto":
                        if ($order["fotoResolution"] == "high") {
                            $fotoResolution = "600dpi";
                        } else {
                            $fotoResolution = "300dpi";
                        };

                        if ($order["fotoScratch"] == 1) {
                            $fotoScratch = "Digitale Staub- und Kratzerentfernung";
                        };
                        if ($order["fotoRoc"] == 1) {
                            $fotoRoc = "Automatische Farbkorrektur";
                        };
                        if ($order["fotoRotate"] == 1) {
                            $fotoRotate = "Bilddrehung";
                        };
                        $order["quellmedien"] = $order["quellmedien"] . "<p><b>$col_value" . "x Papierfotos</b></p><p>$fotoResolution</p><p>$fotoScratch</p><p>$fotoRoc</p><p>$fotoRotate</p>";
                        break;                
                }
            }
            // $getShell = $order["shellDvd"];
            // if ($getShell == "cardboard") {
            //     $huelle = "Kartontasche";
            // } elseif ($getShell == "roundmetal") {
            //     $huelle = "Metalldose rund";
            // } elseif ($getShell == "rectmetal") {
            //     $huelle = "Metallbox eckig";
            // } else {
            //     $huelle = "Papierhülle";
            // }
            //Hülle: Papierhülle (kostenlos) (shellDvd==paper)
            //Kartontasche (shellDvd==cardboard)
            //Metalldose rund (shellDvd==roundmetal)
            //Metallbox eckig (shellDvd==rectmetal)
            $countDvd = "";
            if ($col == "wishDvd" && $col_value == 1) {
                if ($order["countDvd"] > 0) {
                    $countDvd = "<p>Zusätzliche DVD-Kopien: " . $order['countDvd'] . "</p>";
                }
                $order["zielmedien"] = $order["zielmedien"] . "<p><b>Jedes Film/Videomedium auf eine Video-DVD</b></p><p>$countDvd</p><p>Hülle: " . GetShell($order["shellDvd"]) . "</p>";
            }

            $countCd = "";
            if ($col == "wishCd" && $col_value == 1) {
                if ($order["countCd"] > 0) {
                    $countCd = "<p>Zusätzliche CD-Kopien: " . $order['countCd'] . "</p>";
                }
                $order["zielmedien"] = $order["zielmedien"] . "<p><b>Jedes Audiomedium auf eine Audio-CD</b></p><p>$countCd</p><p>Hülle: " . GetShell($order['shellCd']) . "</p>";
            }
            $destMedium = $order["destMedium"];
            if ($col == "wishData" && $col_value == 1) {
                $order["zielmedien"] = $order["zielmedien"] . "<p><b>Gewünscht: Daten auf $destMedium</b></p>";
            }
            
            // remove columns when no longer necessary
            if (preg_match("/count/", $col)) {
                unset($order[$col]);
            }
                  
        }
        unset($order["super8resolution"], $order["lpCleaning"], $order["singleCleaning"], 
                $order["diaResolution"], $order["diaScratch"], $order["diaCleaning"], 
                $order["diaRoc"], $order["diaRotate"], $order["diaSlidechange"], 
                $order["kbResolution"], $order["kbScratch"], $order["kbCleaning"], 
                $order["kbRoc"], $order["kbRotate"], $order["apsResolution"], 
                $order["apsScratch"], $order["apsRoc"], $order["apsRotate"], 
                $order["fotoResolution"], $order["fotoScratch"], $order["fotoRoc"], 
                $order["fotoRotate"], $order["wishDvd"], $order["countDvd"], 
                $order["shellDvd"], $order["wishCd"], $order["countCd"], $order["shellCd"], 
                $order["wishData"], $order["destMedium"]);
        //array_push($orders, $order["auftrag"],$order["kunde"],$order["status"],$order["quellmedien"],$order["zielmedien"],$order["notizen"]);
    // };
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
        <header id="order-header">
            <h1>Auftrag</h1>
        </header>
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
            value="' . $order["ordernumber"] . '"><a href="index.php">Speichern</a></button><br>
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
    // if(isset($_SESSION["orders"])){
    //     foreach($_SESSION["orders"] as $row){
    //         if ($row["auftrag"] == $auftrag) {
    //             echo $row['quellmedien'];
    //         }
    //     }
    // }
    echo $order["quellmedien"];
    echo '</div>
        </section>
        <section id="ziel">
            <h3>Zielmedien</h3>
            <div>';
    // if(isset($_SESSION["orders"])){
    //     foreach($_SESSION["orders"] as $row){
    //         if ($row["auftrag"] == $auftrag) {
    //             echo $row['zielmedien'];
    //         }
    //     }
    // }
    echo $order["zielmedien"];
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
    $micromv = array();
    $betamax = array();
    $video2000 = array();
    $mc = array();
    $tonband = array();
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
            case 'micromv':
                array_push($micromv, $row);
                break;
            case 'mc':
                array_push($mc, $row);
                break;
            case 'tonband':
                array_push($tonband, $row);
                break;
            case 'video2000':
                array_push($video2000, $row);
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
    createMediaTable($micromv);
    createMediaTable($mc);
    createMediaTable($tonband);
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