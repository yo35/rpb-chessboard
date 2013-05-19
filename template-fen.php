<?php

	// Helper
	require_once(RPBCHESSBOARD_ABSPATH . 'helper.php');

	// ID for the current element
	$currentID = RPBChessBoardHelper::makeID();

	// Deal with wordpress auto-filtering
	$content = str_replace('&#8211;', '-', $content);
?>

<pre class="jsChessLib-fen-source" id="<?php echo $currentID; ?>"><?php echo $content; ?></pre>
<script type="text/javascript">
	jsChessRenderer.processFENByID(
		"<?php echo $currentID; ?>",
		<?php echo RPBChessBoardHelper::readSquareSize($atts); ?>,
		<?php echo RPBChessBoardHelper::readShowCoordinates($atts); ?>
	);
</script>

<?php
	RPBChessBoardHelper::printJavascriptActivationWarning();
?>
