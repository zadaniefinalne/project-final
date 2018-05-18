<?php

Header('Content-type: text/xml');

require_once('config.php');

$data_map = $pdo->query("SELECT DISTINCT(bydliskoobec) AS 'city', COUNT(bydliskoobec) AS 'count' FROM `FinalZadanie` GROUP BY bydliskoobec")->fetchAll();

    $xml = new SimpleXMLElement('<markers/>');
    $i = 0;
    foreach ($data_map as $row) {
        $i++;
        $track = $xml->addChild('marker');
        $track->addAttribute('id', $i);
        $track->addAttribute(   "city", $row['city']);
        $track->addAttribute(   "count", $row['count']);
}

print($xml->asXML());

?>
