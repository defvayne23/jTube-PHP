<?php
ini_set("display_errors", true);
include("jTube.php");
include("config.php");

$oVideos = new jTube;
$oVideos->setDev($sDevKey);
$oVideos->setAuth($sAuthKey);
$oVideos->setQuery("user", "default", "uploads");
$oVideos->setOption("limit", 5);

try {
	$aVideos = $oVideos->runQuery();
} catch(Exception $e) {
	die($e->getMessage());
}

echo "<pre>";
print_r($aVideos);
echo "</pre>";