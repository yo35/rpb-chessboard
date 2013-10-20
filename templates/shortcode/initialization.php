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

</script>
