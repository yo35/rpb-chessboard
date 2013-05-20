<?php

	// Helper
	require_once(RPBCHESSBOARD_ABSPATH.'helpers/main.php');

	// ID for the current element
	$currentID = RPBChessBoardMainHelper::makeID();

	// Deal with wordpress auto-filtering
	$content = str_replace('&#8211;', '-', $content);
?>

<pre class="jsChessLib-fen-source" id="<?php echo $currentID; ?>"><?php echo $content; ?></pre>
<script type="text/javascript">
	jsChessRenderer.processFENByID(
		"<?php echo $currentID; ?>",
		<?php echo RPBChessBoardMainHelper::readSquareSize($atts); ?>,
		<?php echo RPBChessBoardMainHelper::readShowCoordinates($atts); ?>
	);
</script>

<?php
	RPBChessBoardMainHelper::printJavascriptActivationWarning();
?>
