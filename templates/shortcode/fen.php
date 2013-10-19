
<pre id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-in">
	<?php echo htmlspecialchars($model->getContent()); ?>
</pre>

<div id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-out" class="rpbchessboard-invisible"></div>

<script type="text/javascript">
	processFEN(
		<?php echo json_encode($model->getTopLevelItemID()); ?> + '-in',
		<?php echo json_encode($model->getTopLevelItemID()); ?> + '-out',
		null
	);
</script>

<?php
	//RPBChessBoardMainHelper::printJavascriptActivationWarning();
?>
