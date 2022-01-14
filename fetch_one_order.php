<?php
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
        $stmt = $connection->prepare("SELECT ordernumber, order_status, usr_givenname, usr_familyname, usr_company, usr_street, usr_zip, usr_city, usr_country, usr_email, usr_phone, '' AS quellmedien, '' AS zielmedien,
        usr_comment, cpl_comment, count8mm, count16mm, countVhs, countVhsc,
        countVideo8, countMinidv, countMicromv, countVideo2000,
        countBetamax, countMc, countTonband, countLp, countSingle,
        countDia, countKb, countAps, countFoto, super8resolution,
        lpCleaning, singleCleaning, diaResolution, diaScratch,
        diaCleaning, diaRoc, diaRotate, diaSlidechange, kbResolution,
        kbScratch, kbCleaning, kbRoc, kbRotate, apsResolution,
        apsScratch, apsRoc, apsRotate, fotoResolution, fotoScratch,
        fotoRoc, fotoRotate, wishDvd, countDvd, shellDvd, wishCd,
        countCd, shellCd, wishData, destMedium, confirmedTrash FROM cpl_orders WHERE ordernumber = ?");
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
    // foreach loop modifies the original $order array using references
    $clean = "Klangverbesserung: Nassreinigung";
        $order["quellmedien"] = $value;
        foreach ($order as $col => $col_value) {
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
        }
        
        // print_r($order);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title><?php echo $auftrag; ?></title>
</head>
<body>
    <main class="container-fluid">
        <div class="row g-2">
            <header id="order-header" class="col">
                <h1 class="my-3">Auftrag</h1>
            </header>
        </div>
<?php
    echo '<div class="row g-2">';
    echo '
    <section id="details-form" class="col">
        <form  action="index.php" method="post">
            <table class="table table-bordered border w-50">
                <tr>
                    <th>Auftrag</th>
                    <td>' . $order["ordernumber"] . '</td>
                </tr>
                <tr>
                    <th>Vorname</th>
                    <td>
                        <input class="form-control" type="text" name="givenname" value="' . $order["usr_givenname"]. '">
                    </td>
                </tr>
                <tr>
                    <th>Nachname</th>
                    <td>
                        <input class="form-control" type="text" name="familyname" value="' . $order["usr_familyname"] .'">
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <input class="form-control" type="email" name="email" value="' . $order["usr_email"] . '">
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                    <select class="form-select" name="status">
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
                    <td><textarea class="form-control" name="message" rows="5" cols="30">' . $order["cpl_comment"] . '</textarea></td>
                </tr>
            </table>
            <button class="btn btn-primary mb-3" type="Submit" name="auftrag"
            value="' . $order["ordernumber"] . '">Speichern</button><br>
        </form>

            '; 
       

        echo '<section class="col">';
        if ($order["order_status"] == 6) {
            echo '<button class="btn btn-primary mb-3">Kunde jetzt informieren</button><br>';
            // Then set status to 7
            // Then send email
            }
        echo ' <a class="btn btn-primary mb-3" href="index.php" role="button">Zurück</a>
        </section>';
                
    echo '<h2 class="">Auftrag Details:</h2>
    </section>';
    echo '</div>';

    /* Code to display quell und ziel medien*/
    echo '<div class="row g-2">';
    echo '
        <section class="col" id="quell">
            <div class="card text-dark bg-light mb-3">
                <h3 class="card-header">Quellmedien</h3>
                <div class="card-body p-3">';
    echo $order["quellmedien"];
    echo '      </div>
            </div>
        </section>
    
        <section class="col" id="ziel">
            <div class="card text-dark bg-light mb-3">
                <h3 class="card-header">Zielmedien</h3>
                <div class="p-3">';
    echo $order["zielmedien"];
    echo '      </div>
            </div>
        </section>
    ';
    echo '</div>';
    echo '<div class="row g-2">';
    echo 
    '
    <section class="col">
        <div class="card text-dark bg-light mb-3" id="sonstiges">
            <h3 class="card-header">Sonstiges</h3>
            <div class="card-body p-3">';

            if ($order["confirmedTrash"] == 1) {
                echo '<p class="card-text">Medien nach den Digitalisierung entsorgen</p>';
            }
            echo '<h4 class="card-title">Notizen von Kunde:</h4>';

            if ($order["usr_comment"] != '') {
                echo '<p class="card-text"><i>' . $order["usr_comment"] . '</i></p>';
            } else {
                echo '<p class="card-text"><i>Keine</i></p>';
            }

    echo '   
            </div>
           
        </div>
    </section>';
    
    /* Code to display customers address */
    echo '
    <section class="col" id="address">
        <div class="card text-dark bg-light mb-3">
            <h3 class="card-header">Adresse</h3>  
            <address class=card-body p-3>';
                echo $order["usr_givenname"] . ' ';
                echo $order["usr_familyname"] . '<br>'; 
                if (!empty($order["usr_company"])) {
                    echo $order["usr_company"] . '<br>';
                }
                echo $order["usr_street"] . '<br>';
                echo $order["usr_zip"] . ' ' . $order["usr_city"] . '<br>';
                echo $order["usr_country"] . '<br>';
                echo $order["usr_phone"] . '<br>' . $order["usr_email"] .
            '</address>
        </div>
    </section> ';
    echo '</div>';

    /* Code to create Media title tables for each mediatype */

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
    echo '<div class="row g-2">';

    echo '<section class="col" id="media">';

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
            echo '<table class="table table-striped caption-top table-bordered table-sm">
                <caption>' . strtoupper($arr[0]["mediatype"]) . '</caption>
                <thead class="table-dark">
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
    echo '</div>';
?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>