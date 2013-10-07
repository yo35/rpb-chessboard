
<h2>
	<div id="rpbchessboard-admin-icon">
		<img src="<?php echo RPBCHESSBOARD_URL."/images/admin.png"; ?>" />
	</div>
	<?php
		echo __('Chess games and diagrams', 'rpbchessboard') . ' - ' .
			htmlspecialchars($model->getTitle());
	?>
</h2>
