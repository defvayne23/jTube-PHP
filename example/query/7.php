<?php
include("../../lib/jTube.Query.php");
include("../../config.php");

$oJTube = new jTubeQuery;
$oJTube->setDev($sDevKey);
$oJTube->setQuery("user", "defvayne23", "playlists");
$oJTube->setOption("limit", 5);

try {
	$aPlaylists = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<?php
$sTitle = "Specific user playlists - Query";
include("../inc_header.php");
?>

<h2>Specific user playlists - Query</h2>
<ul id="video-thumbs">
	<?php
	foreach($aPlaylists as $aPlaylist) {
		echo "<li>\n";
		echo "\t<a href=\"".$aPlaylist["link"]."\">\n";
		echo "\t\t".$aPlaylist["title"]."\n";
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
$oJTube->setQuery("user", "defvayne23", "playlists");
$oJTube->setOption("limit", 5);

try {
	$aPlaylists = $oJTube->runQuery();
} catch(Exception $sError) {
	die($sError->getMessage());
}
?>

<ul id="video-thumbs">
	<?php
	foreach($aPlaylists as $aPlaylist) {
		echo "<li>\n";
		echo "\t<a href=\"".$aPlaylist["link"]."\">\n";
		echo "\t\t".$aPlaylist["title"]."\n";
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
		<pre><code><?php print_r($aPlaylists);?></code></pre>
	</section>
</section>

<?php include("../inc_footer.php"); ?>