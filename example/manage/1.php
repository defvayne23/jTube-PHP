<?php
include("../../lib/jTube.ManageVideo.php");
include("../../config.php");

$oJTube = new jTubeManageVideo;
$oJTube->setDev($sDevKey);
$oJTube->setAuth($sAuthKey);

try {
	$oJTube->loadVideo("S4pGCqtkJEE");
} catch(Exception $sError) {
	die($sError->getMessage());
}

$oJTube->setInfo("title", "Updated Title");

try {
	$oJTube->save();
} catch(Exception $sError) {
	die($sError->getMessage());
}
die;
?>

<?php
$sTitle = "Authenticated user videos - Query";
include("../inc_header.php");
?>

<h2>Authenticated user videos - Query</h2>
<ul id="video-thumbs">
	<?php
	foreach($aVideos as $aVideo) {
		echo "<li>\n";
		echo "\t<a href=\"".$aVideo["link"]."\">\n";
		echo "\t\t<img src=\"".$aVideo["thumbnail"]."\" width=\"120px\"><br>\n";
		echo "\t\t".$aVideo["title"]."\n";
		echo "\t</a>\n";
		echo "</li>\n";
	}
	?>
</ul>
<div class="clear">&nbsp;</div>

<section class="container">
	<h3>Code:</h3>
	<section class="code">
		<pre><code><?php
highlight_string('<?php
include("../../lib/jTube.Query.php");
include("../../config.php");

$oJTube = new jTubeQuery;
$oJTube->setDev($sDevKey);
$oJTube->setAuth($sAuthKey);
$oJTube->setQuery("user", "default", "uploads");
$oJTube->setOption("limit", 5);

try {
	$aVideos = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<ul id="video-thumbs">
	<?php
	foreach($aVideos as $aVideo) {
		echo "<li>\n";
		echo "\t<a href=\"".$aVideo["link"]."\">\n";
		echo "\t\t<img src=\"".$aVideo["thumbnail"]."\" width=\"120px\"><br>\n";
		echo "\t\t".$aVideo["title"]."\n";
		echo "\t</a>\n";
		echo "</li>\n";
	}
	?>
</ul>');
		?></code></pre>
	</section>
</section>

<section class="container">
	<h3>Reponse:</h3>
	<section class="code">
		<pre><code><?php print_r($aVideos);?></code></pre>
	</section>
</section>

<?php include("../inc_footer.php"); ?>