
<div id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-javascript" class="rpbchessboard-javascript-warning">
	<?php
		_e('You need to activate javascript to enhance chess game and diagram visualization.', 'rpbchessboard');
	?>
</div>

<script type="text/javascript">
	hideJavascriptWarning(<?php echo json_encode($model->getTopLevelItemID()); ?> + '-javascript');
</script>
