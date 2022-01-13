# cpl_php_auftrag
Copylab Auftrag nur mit PHP/HTML5/CSS

to-do:

- combine update_order.php with index.php 
- add logic to update status after customer email sent
- sort the main table by status


SELECT `ordernumber`, `created`, `order_status`, `usr_company`, `usr_givenname`, `usr_familyname`, `usr_street`, `usr_zip`, `usr_city`, `usr_country`, `usr_email`, `usr_phone`, `delivery_company`, `delivery_givenname`, `delivery_familyname`, `delivery_street`, `delivery_zip`, `delivery_city`, `delivery_country`, `usr_comment`, `cpl_comment`, `count8mm`, `count16mm`, `countVhs`, `countVhsc`, `countMinidv`, `countMicromv`, `countVideo8`, `countVideo2000`, `countBetamax`, `countMc`, `countTonband`, `countLp`, `countSingle`, `countDia`, `countKb`, `countAps`, `countFoto`, `countDvd`, `countCd`, `destMedium`, `wishData`, `wishDvd`, `wishCd`, `shellDvd`, `shellCd`, `super8resolution`, `lpCleaning`, `singleCleaning`, `diaResolution`, `diaNumbering`, `diaCleaning`, `diaScratch`, `diaRoc`, `diaRotate`, `diaSlidechange`, `kbResolution`, `kbNumbering`, `kbCleaning`, `kbScratch`, `kbRoc`, `kbRotate`, `apsResolution`, `apsNumbering`, `apsScratch`, `apsRoc`, `apsRotate`, `fotoResolution`, `fotoNumbering`, `fotoRoc`, `fotoRotate`, `fotoScratch`, `confirmedTrash`, '' AS quellmedien, '' AS zielmedien FROM cpl_orders WHERE ordernumber = ?