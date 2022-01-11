<?php 
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
            $sql = "SELECT ordernumber AS auftrag, 
                    CONCAT_WS(' ', usr_givenname, usr_familyname) AS kunde, 
                    order_status AS status, '' AS quellmedien, '' AS zielmedien,
                    comment AS notizen, count8mm, count16mm, countVhs, countVhsc,
                    countVideo8, countMinidv, countMicromv, countVideo2000,
                    countBetamax, countMc, countTonband, countLp, countSingle,
                    countDia, countKb, countAps, countFoto, super8resolution,
                    lpCleaning, singleCleaning, diaResolution, diaScratch,
                    diaCleaning, diaRoc, diaRotate, diaSlidechange, kbResolution,
                    kbScratch, kbCleaning, kbRoc, kbRotate, apsResolution,
                    apsScratch, apsRoc, apsRotate, fotoResolution, fotoScratch,
                    fotoRoc, fotoRotate, wishDvd, countDvd, shellDvd, wishCd,
                    countCd, shellCd, wishData, destMedium FROM cpl_orders;";
            $result = $connection->query($sql);
            // array that stores data from database query
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
        } 
        catch (PDOException $e) {
            die("ERROR: Could not execute $sql. " . $e->getMessage());
        }
        
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
    
        function GetStatus($arg)
        {
            $status = "";
            switch ($arg) {
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
        // code that takes all results from "count" columns and combine them into one named "quellmedien"
        $orders = [];
        $value = "";
        // foreach loop modifies the original $rows array using references, later I want to change this into an array_map function for speed and simplicity
        $clean = "Klangverbesserung: Nassreinigung";
        foreach ($rows as &$row) {
            $row["quellmedien"] = $value;
            foreach ($row as $col => $col_value) {
                if ($col == "status") {
                    $row["statustext"] = GetStatus($row["status"]);
                }
                
                if (is_numeric($col_value) && $col_value > 0) {
                    switch ($col) {
                        case "count8mm":
                            $filmRes = "Art: Premium SD";
                            if ($row["super8resolution"] == "hd") {
                                $filmRes = "Art: Deluxe HD";
                            };
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Normal 8 / Super 8 Filme</b></p><p>$filmRes</p>";
                            break;
                        case "count16mm":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x 16 mm / Super 16 Filme</b></p>";
                            break;
                        case "countVhs":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x VHS-Kassetten</b></p>";
                            break;
                        case "countVhsc":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x VHS-C Kassetten</b></p>";
                            break;
                        case "countVideo8":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Video8 / Hi8 / Digital8-Kassetten</b></p>";
                            break; 
                        case "countMinidv":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Mini-DV / DVCAM-Kassetten</b></p>";
                            break;
                        case "countMicromv":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x MicroMV-Kassetten</b></p>";
                            break;
                        case "countVideo2000":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Video2000-Kassetten</b></p>";
                            break;
                        case "countBetamax":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Betamax-Kassetten</b></p>";
                            break;
                        case "countMc":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Musik-Kassetten (MC)</b></p>";
                            break;
                        case "countTonband":
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Tonband</b></p>";
                            break;
                        case "countLp":
                            if ($row["lpCleaning"] == 1) {   
                                $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Langspiel-Schallplatte (LP)</b></p><p>$clean</p>";
                            } else {
                                $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Langspiel-Schallplatte (LP)</b></p>";
                            }
                            break;
                        case "countSingle":
                            if ($row["singleCleaning"] == 1) {   
                                $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Single-Schallplatte</b></p><p>$clean</p>";
                            } else {
                                $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Single-Schallplatte</b></p>";
                            }
                            break;
                        case "countDia":
                            $diaResolution = "";
                            if ($row["diaResolution"] == "standard") {
                                $diaResolution = "Standard-Scan 3900 dpi";
                            } elseif ($row["diaResolution"] == "premium") {
                                $diaResolution = "Premium 4500 dpi";
                            } elseif ($row["diaResolution"] == "premium-plus") {
                                $diaResolution = "Premium-Plus 4500 dpi";
                            } else {
                                $diaResolution = "Budget-Scan 2900 dpi";
                            };
                        
                            if ($row["diaScratch"] == 1) {
                                $diaScratch = "Digitale Staub- und Kratzerentfernung";
                            };
                            if ($row["diaCleaning"] == 1) {
                                $diaCleaning = "Feucht-Intensivreinigung";
                            };
                            if ($row["diaRoc"] == 1) {
                                $diaRoc = "Automatische Farbkorrektur";
                            };
                            if ($row["diaRotate"] == 1) {
                                $diaRotate = "Bilddrehung und Spiegelung";
                            };
                            if ($row["diaSlidechange"] == 1) {
                                $diaSlidechange = "Dias in glaslose Rahmen umrahmen";
                            };
                            
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Dias</b></p><p>$diaResolution</p><p>$diaScratch</p><p>$diaCleaning</p><p>$diaRoc</p><p>$diaRotate</p><p>$diaSlidechange</p>";
                            break;
                        case "countKb":
                           
                            if ($row["kbResolution"] == "high") {
                                $kbResolution = "4500dpi";
                            } else {
                                $kbResolution = "2800dpi";
                            };
    
                            if ($row["kbScratch"] == 1) {
                                $kbScratch = "Digitale Staub- und Kratzerentfernung";
                            };
                            if ($row["kbCleaning"] == 1) {
                                $kbCleaning = "Manuelle Staubentfernung";
                            };
                            if ($row["kbRoc"] == 1) {
                                $kbRoc = "Automatische Farbkorrektur";
                            };
                            if ($row["kbRotate"] == 1) {
                                $kbRotate = "Bilddrehung";
                            };
    
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Negative (Kleinbildstreifen)</b></p><p>$kbResolution</p><p>$kbScratch</p><p>$kbCleaning</p><p>$kbRoc</p><p>$kbRotate</p>";
                            break;
                        case "countAps":
                            if ($row["apsResolution"] == "high") {
                                $apsResolution = "3000dpi";
                            } else {
                                $apsResolution = "2000dpi";
                            };
    
                            if ($row["apsScratch"] == 1) {
                                $apsScratch = "Digitale Staub- und Kratzerentfernung";
                            };
                            if ($row["apsRoc"] == 1) {
                                $apsRoc = "Automatische Farbkorrektur";
                            };
                            if ($row["apsRotate"] == 1) {
                                $apsRotate = "Bilddrehung";
                            };
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Bilder auf APS-Rollen</b></p><p>$apsResolution</p><p>$apsScratch</p><p>$apsRoc</p><p>$apsRotate</p>";
                            break;
                        case "countFoto":
                            if ($row["fotoResolution"] == "high") {
                                $fotoResolution = "600dpi";
                            } else {
                                $fotoResolution = "300dpi";
                            };
    
                            if ($row["fotoScratch"] == 1) {
                                $fotoScratch = "Digitale Staub- und Kratzerentfernung";
                            };
                            if ($row["fotoRoc"] == 1) {
                                $fotoRoc = "Automatische Farbkorrektur";
                            };
                            if ($row["fotoRotate"] == 1) {
                                $fotoRotate = "Bilddrehung";
                            };
                            $row["quellmedien"] = $row["quellmedien"] . "<p><b>$col_value" . "x Papierfotos</b></p><p>$fotoResolution</p><p>$fotoScratch</p><p>$fotoRoc</p><p>$fotoRotate</p>";
                            break;                
                    }
                }
                // $getShell = $row["shellDvd"];
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
                    if ($row["countDvd"] > 0) {
                        $countDvd = "<p>Zusätzliche DVD-Kopien: " . $row['countDvd'] . "</p>";
                    }
                    $row["zielmedien"] = $row["zielmedien"] . "<p><b>Jedes Film/Videomedium auf eine Video-DVD</b></p><p>$countDvd</p><p>Hülle: " . GetShell($row["shellDvd"]) . "</p>";
                }
    
                $countCd = "";
                if ($col == "wishCd" && $col_value == 1) {
                    if ($row["countCd"] > 0) {
                        $countCd = "<p>Zusätzliche CD-Kopien: " . $row['countCd'] . "</p>";
                    }
                    $row["zielmedien"] = $row["zielmedien"] . "<p><b>Jedes Audiomedium auf eine Audio-CD</b></p><p>$countCd</p><p>Hülle: " . GetShell($row['shellCd']) . "</p>";
                }
                $destMedium = $row["destMedium"];
                if ($col == "wishData" && $col_value == 1) {
                    $row["zielmedien"] = $row["zielmedien"] . "<p><b>Gewünscht: Daten auf $destMedium</b></p>";
                }
                
                // remove columns when no longer necessary
                if (preg_match("/count/", $col)) {
                    unset($row[$col]);
                }
                      
            }
            unset($row["super8resolution"], $row["lpCleaning"], $row["singleCleaning"], 
                    $row["diaResolution"], $row["diaScratch"], $row["diaCleaning"], 
                    $row["diaRoc"], $row["diaRotate"], $row["diaSlidechange"], 
                    $row["kbResolution"], $row["kbScratch"], $row["kbCleaning"], 
                    $row["kbRoc"], $row["kbRotate"], $row["apsResolution"], 
                    $row["apsScratch"], $row["apsRoc"], $row["apsRotate"], 
                    $row["fotoResolution"], $row["fotoScratch"], $row["fotoRoc"], 
                    $row["fotoRotate"], $row["wishDvd"], $row["countDvd"], 
                    $row["shellDvd"], $row["wishCd"], $row["countCd"], $row["shellCd"], 
                    $row["wishData"], $row["destMedium"]);
            //array_push($orders, $row["auftrag"],$row["kunde"],$row["status"],$row["quellmedien"],$row["zielmedien"],$row["notizen"]);
        };
        
        
        $connection = null;
        
        echo "<table>
        <tr>
        <th>Auftrag</th>
        <th>Kunde</th>
        <th>Status</th>
        <th>Quellmedien</th>
        <th>Zielmedien</th>
        <th>Notizen</th>
        </tr>";

        foreach ($rows as $row) {
            echo "<tr>";
            echo "<td>" . $row['auftrag'] . '<form action="fetch_one_order.php" method="post">
                <input type="submit" name="auftrag"
                    value="' . $row['auftrag'] . '"/>
                </form>' . "</td>";
            echo "<td>" . $row['kunde'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['quellmedien'] . "</td>";
            echo "<td>" . $row['zielmedien'] . "</td>";
            echo "<td>" . $row['notizen'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    ?>
