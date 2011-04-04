<?php
include("../../lib/jTube.Query.php");
include("../../config.php");

$oJTube = new jTubeQuery;
$oJTube->setDev($sDevKey);
$oJTube->setQuery("user", "twofivethreetwo", "profile");

try {
	$aProfile = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<?php
$sTitle = "Specified user profile - Query";
include("../inc_header.php");
?>

<h2>Specified user profile - Query</h2>
<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td valign="top">
			<img src="<?=$aProfile["thumbnail"]?>">
		</td>
		<td valign="top">
			<h3><a href="<?=$aProfile["link"]?>" target="_blank"><?=$aProfile["username"]?></a></h3>
			Age: <?=$aProfile["age"]?><br>
			Location: <?=$aProfile["location"]?><br>
			Gender: <?php echo ($aProfile["gender"] == "m")?"Male":"Female"; ?><br>
			<br>
			Channel Views: <?=$aProfile["views"]?><br>
			Total Upload Views: <?=$aProfile["uploadViews"]?><br>
			Subscribers: <?=$aProfile["subscribers"]?><br>
			<br>
			Last Login: <?=date("m/d/Y", $aProfile["lastLogin"])?>
		</td>
	</tr>
</table>

<section class="container">
	<h3>Code:</h3>
	<section class="code">
		<pre><code><?php
highlight_string('<?php
include("../../lib/jTube.Query.php");
include("../../config.php");

$oJTube = new jTubeQuery;
$oJTube->setDev($sDevKey);
$oJTube->setQuery("user", "twofivethreetwo", "profile");

try {
	$aProfile = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<table border="0" cellpadding="4" cellspacing="0">
	<tr>
		<td valign="top">
			<img src="<?=$aProfile["thumbnail"]?>">
		</td>
		<td valign="top">
			<h3><a href="<?=$aProfile["link"]?>" target="_blank"><?=$aProfile["username"]?></a></h3>
			Age: <?=$aProfile["age"]?><br>
			Location: <?=$aProfile["location"]?><br>
			Gender: <?php echo ($aProfile["gender"] == "m")?"Male":"Female"; ?><br>
			<br>
			Channel Views: <?=$aProfile["views"]?><br>
			Total Upload Views: <?=$aProfile["uploadViews"]?><br>
			Subscribers: <?=$aProfile["subscribers"]?><br>
			<br>
			Last Login: <?=date("m/d/Y", $aProfile["lastLogin"])?>
		</td>
	</tr>
</table>');
		?></code></pre>
	</section>
</section>

<section class="container">
	<h3>Reponse:</h3>
	<section class="code">
		<pre><code><?php print_r($aProfile);?></code></pre>
	</section>
</section>

<?php include("../inc_footer.php"); ?>