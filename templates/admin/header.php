
<h2>
	<div id="rpbchessboard-admin-icon">
		<img src="<?php echo RPBCHESSBOARD_URL.'/images/admin.png'; ?>" />
	</div>
	<?php
		echo __('Chess games and diagrams', 'rpbchessboard') . ' &ndash; ' .
			htmlspecialchars($model->getTitle());
	?>
</h2>

<div id="rpbchessboard-admin-javascript-warning" class="rpbchessboard-javascript-warning">
	<?php
		_e('To work properly, the RPB Chessboard plugin needs javascript to be activated in your browser.',
			'rpbchessboard');
	?>
</div>

<script type="text/javascript">
	hideJavascriptWarning('rpbchessboard-admin-javascript-warning');
</script>
