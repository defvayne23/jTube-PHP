<?php
include("../../lib/jTube.Query.php");
include("../../config.php");

$oJTube = new jTubeQuery;
$oJTube->setDev($sDevKey);
$oJTube->setQuery("videos", "N0gb9v4LI4o", "comments");
$oJTube->setOption("limit", 5);

try {
	$aComments = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<?php
$sTitle = "Video comments";
include("../inc_header.php");
?>

<h2>Video comments - Query</h2>
<ul id="video-comments">
	<?php
	foreach($aComments as $aComment) {
		echo "<li>\n";
		echo "<b>".$aComment["author"]."</b> - ".date("m/d/Y", $aComment["posted"])."<br>\n";
		echo $aComment["comment"]."<br><br>\n";
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
$oJTube->setQuery("videos", "N0gb9v4LI4o", "comments");

try {
	$aVideo = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<ul id="video-comments">
	<?php
	foreach($aComments as $aComment) {
		echo "<li>\n";
		echo "<b>".$aComment["author"]."</b> - ".date("m/d/Y", $aComment["posted"])."<br>\n";
		echo $aComment["comment"]."<br><br>\n";
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
		<pre><code><?php print_r($aComments);?></code></pre>
	</section>
</section>

<?php include("../inc_footer.php"); ?>