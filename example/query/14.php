<?php
include("../../lib/jTube.Query.php");
include("../../config.php");

$oJTube = new jTubeQuery;
$oJTube->setDev($sDevKey);
$oJTube->setQuery("videos", "ngXA6nXbp-8", "info");

try {
	$aVideo = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<?php
$sTitle = "Video info";
include("../inc_header.php");
?>

<h2>Video info - Query</h2>
<ul id="video-thumbs">
	<?php
	echo "<li>\n";
	echo "\t<a href=\"".$aVideo["link"]."\">\n";
	echo "\t\t<img src=\"".$aVideo["thumbnail"]."\" width=\"120px\"><br>\n";
	echo "\t\t".$aVideo["title"]."\n";
	echo "\t</a>\n";
	echo "</li>\n";
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
$oJTube->setQuery("videos", "ngXA6nXbp-8", "info");

try {
	$aVideo = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<ul id="video-thumbs">
	<?php
	echo "<li>\n";
	echo "\t<a href=\"".$aVideo["link"]."\">\n";
	echo "\t\t<img src=\"".$aVideo["thumbnail"]."\" width=\"120px\"><br>\n";
	echo "\t\t".$aVideo["title"]."\n";
	echo "\t</a>\n";
	echo "</li>\n";
	?>
</ul>');
		?></code></pre>
	</section>
</section>

<section class="container">
	<h3>Reponse:</h3>
	<section class="code">
		<pre><code><?php print_r($aVideo);?></code></pre>
	</section>
</section>

<?php include("../inc_footer.php"); ?>