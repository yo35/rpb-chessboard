
<script type="text/javascript">

	// Load the default chess widget aspect options.
	defineDefaultChessWidgetOptions({
		squareSize     : <?php echo json_encode($model->getDefaultSquareSize     ()); ?>,
		showCoordinates: <?php echo json_encode($model->getDefaultShowCoordinates()); ?>
	});

</script>
