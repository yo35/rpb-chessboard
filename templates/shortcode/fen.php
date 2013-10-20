<?php
	require_once(RPBCHESSBOARD_ABSPATH.'helpers/json.php');
?>

<div id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-in" class="rpbchessboard-in"><?php
	echo htmlspecialchars($model->getContent());
?></div>

<div id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-out" class="rpbchessboard-out rpbchessboard-invisible"></div>

<script type="text/javascript">
	processFEN(
		<?php echo json_encode($model->getTopLevelItemID()); ?> + '-in',
		<?php echo json_encode($model->getTopLevelItemID()); ?> + '-out',
		<?php
			echo RPBChessboardHelperJSON::formatChessWidgetAttributes(
				$model->getCustomSquareSize     (),
				$model->getCustomShowCoordinates()
			);
		?>
	);
</script>
