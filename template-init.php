<?php

	// Helper
	require_once(RPBCHESSBOARD_ABSPATH . 'helper.php');

	// Debug node
	RPBChessBoardHelper::printDebugNode();
?>

<script type="text/javascript">

	(function()
	{
		// Exit if already configured
		if(jsChessRenderer.isBaseURLConfigured()) {
			return;
		}

		// Set the base URL
		jsChessRenderer.configureBaseURL("<?php echo RPBCHESSBOARD_URL; ?>/chess4web");

		// Localized month names
		jsChessRenderer.option.monthName = {
			 1: "<?php echo __('january'  , 'rpbchessboard'); ?>",
			 2: "<?php echo __('february' , 'rpbchessboard'); ?>",
			 3: "<?php echo __('march'    , 'rpbchessboard'); ?>",
			 4: "<?php echo __('april'    , 'rpbchessboard'); ?>",
			 5: "<?php echo __('may'      , 'rpbchessboard'); ?>",
			 6: "<?php echo __('june'     , 'rpbchessboard'); ?>",
			 7: "<?php echo __('july'     , 'rpbchessboard'); ?>",
			 8: "<?php echo __('august'   , 'rpbchessboard'); ?>",
			 9: "<?php echo __('september', 'rpbchessboard'); ?>",
			10: "<?php echo __('october'  , 'rpbchessboard'); ?>",
			11: "<?php echo __('november' , 'rpbchessboard'); ?>",
			12: "<?php echo __('december' , 'rpbchessboard'); ?>"
		};

		// Localized piece symbols
		jsChessRenderer.option.pieceSymbol = {
			"K": "R",
			"Q": "D",
			"R": "T",
			"B": "F",
			"N": "C",
			"P": "P"
		};
	})();

</script>
