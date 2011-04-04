<?php
include("../../lib/jTube.Query.php");
include("../../config.php");

$oJTube = new jTubeQuery;
$oJTube->setDev($sDevKey);
$oJTube->setQuery("user", "adultswim", "contacts");
$oJTube->setOption("limit", 5);

try {
	$aContacts = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<?php
$sTitle = "Specific user contacts - Query";
include("../inc_header.php");
?>

<h2>Specific user contacts - Query</h2>
<ul id="video-thumbs">
	<?php
	foreach($aContacts as $aContact) {
		echo "<li>\n";
		echo "\t<a href=\"".$aContact["link"]."\">\n";
		echo "\t\t".$aContact["username"]."\n";
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
$oJTube->setQuery("user", "adultswim", "contacts");
$oJTube->setOption("limit", 5);

try {
	$aPlaylists = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<ul id="video-thumbs">
	<?php
	foreach($aContacts as $aContact) {
		echo "<li>\n";
		echo "\t<a href=\"".$aContact["link"]."\">\n";
		echo "\t\t".$aContact["username"]."\n";
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
		<pre><code><?php print_r($aContacts);?></code></pre>
	</section>
</section>

<?php include("../inc_footer.php"); ?>