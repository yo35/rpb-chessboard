<?php
	require_once(RPBCHESSBOARD_ABSPATH.'helpers/json.php');
?>

<script type="text/javascript">

	// Load the default chess widget aspect options.
	defineDefaultChessWidgetOptions(
		<?php
			echo RPBChessboardHelperJSON::formatChessWidgetAttributes(
				$model->getDefaultSquareSize     (),
				$model->getDefaultShowCoordinates()
			);
		?>
	);

	// Localization: miscellaneous
	PgnWidget.text.initialPosition = '<?php _e('Initial position', 'rpbchessboard'); ?>';

	// Localization: month names
	PgnWidget.text.months = [
		'<?php _e('January'  , 'rpbchessboard'); ?>',
		'<?php _e('February' , 'rpbchessboard'); ?>',
		'<?php _e('March'    , 'rpbchessboard'); ?>',
		'<?php _e('April'    , 'rpbchessboard'); ?>',
		'<?php _e('May'      , 'rpbchessboard'); ?>',
		'<?php _e('June'     , 'rpbchessboard'); ?>',
		'<?php _e('July'     , 'rpbchessboard'); ?>',
		'<?php _e('August'   , 'rpbchessboard'); ?>',
		'<?php _e('September', 'rpbchessboard'); ?>',
		'<?php _e('October'  , 'rpbchessboard'); ?>',
		'<?php _e('November' , 'rpbchessboard'); ?>',
		'<?php _e('December' , 'rpbchessboard'); ?>'
	];

	// Localization: chess piece symbols
	PgnWidget.text.pieceSymbols = {
		'K': '<?php /*i18l King symbol   */ _e('K', 'rpbchessboard'); ?>',
		'Q': '<?php /*i18l Queen symbol  */ _e('Q', 'rpbchessboard'); ?>',
		'R': '<?php /*i18l Rook symbol   */ _e('R', 'rpbchessboard'); ?>',
		'B': '<?php /*i18l Bishop symbol */ _e('B', 'rpbchessboard'); ?>',
		'N': '<?php /*i18l Knight symbol */ _e('N', 'rpbchessboard'); ?>',
		'P': '<?php /*i18l Pawn symbol   */ _e('P', 'rpbchessboard'); ?>'
	};

</script>
