
<div id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-in" class="rpbchessboard-in"><?php
	echo htmlspecialchars($model->getContent());
?></div>

<div id="<?php echo htmlspecialchars($model->getTopLevelItemID()); ?>-out" class="rpbchessboard-out rpbchessboard-invisible">
	<div class="rpbchessboard-game-head">
		<div class="PgnWidget-field-fullNameWhite">
			<span class="rpbchessboard-white-square"></span>
			<span class="PgnWidget-anchor-fullNameWhite"></span>
		</div>
		<div class="PgnWidget-field-fullNameBlack">
			<span class="rpbchessboard-black-square"></span>
			<span class="PgnWidget-anchor-fullNameBlack"></span>
		</div>
		<div class="PgnWidget-field-Event">
			<span class="PgnWidget-anchor-Event"></span>
			<span class="PgnWidget-field-Round">(<span class="PgnWidget-anchor-Round"></span>)</span>
			<span class="PgnWidget-field-Date">- <span class="PgnWidget-anchor-Date"></span></span>
		</div>
		<div class="PgnWidget-field-Annotator">
			<?php echo sprintf(__('Commented by %1$s', 'rpbchessboard'), '<span class="PgnWidget-anchor-Annotator"></span>'); ?>
		</div>
	</div>
	<div class="rpbchessboard-game-body PgnWidget-field-moves">
		<div class="PgnWidget-anchor-moves"></div>
		<div class="PgnWidget-field-Result">
			<span class="PgnWidget-anchor-Result"></span>
		</div>
	</div>
</div>

<script type="text/javascript">
	processPGN(
		<?php echo json_encode($model->getTopLevelItemID()); ?> + '-in',
		<?php echo json_encode($model->getTopLevelItemID()); ?> + '-out',
		{<?php echo $model->getCustomOptionsAsJavascript(); ?>}
	);
</script>
