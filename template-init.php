<?php

	// This template is intended to be printed only once
	if(defined('RPBCHESSBOARD_JSCHESSLIB_INITIALIZED')) {
		return;
	}
	define('RPBCHESSBOARD_JSCHESSLIB_INITIALIZED', 1);
?>

<?php if(defined('RPBCHESSBOARD_DEBUG')): // Print the debug node if necessary ?>
	<pre id="jsChessLib-debug"></pre>
<?php endif; ?>

<script type="text/javascript">

	(function()
	{
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

		// Localized strings
		jsChessRenderer.text.initialPosition = "<?php echo __('Initial position', 'rpbchessboard'); ?>";

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
