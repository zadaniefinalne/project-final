<?php

Header('Content-type: text/xml');

require_once('config.php');

$data_map = $pdo->query("SELECT DISTINCT(strednaskolaadresa) AS 'address', COUNT(strednaskolaadresa) AS 'counted' FROM `FinalZadanie` GROUP BY strednaskolaadresa")->fetchAll();

    $xml = new SimpleXMLElement('<markers/>');
    $i = 0;
    foreach ($data_map as $row) {
        $i++;
        $track = $xml->addChild('marker');
        $track->addAttribute('id', $i);
        $track->addAttribute(   "city", $row['address']);
        $track->addAttribute(   "count", $row['counted']);
}

print($xml->asXML());

?>
