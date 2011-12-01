<?php

// Pre-node for debug messages printing
if($add_debug_tag) {
	echo '<pre id="chess4web-debug"></pre>';
	$add_debug_tag = false;
}

// Remove the useless elements added by the Wordpress engine
$content = preg_replace('/<[^>]*>/', '', $content);

// Raw PGN text section
echo '<pre class="chess4web-pgn" id="rpchessboard_pgn_'.$id_counter.'">';
echo $content;
echo '</pre>';

// Javascript-not-enabled message
echo '<div class="chess4web-javascript-warning">';
_e('You need to activate javascript to enhance the PGN game visualization.', 'rpbchessboard');
echo '</div>';

// Display the game
?>
<div class="chess4web-out chess4web-hide-this" id="rpchessboard_pgn_<?php echo $id_counter; ?>">
	<div class="rpbchessboard-game-head">
		<div><span class="rpbchessboard-white-square">&nbsp;</span>&nbsp;<span class="chess4web-template-WhiteFullName"></span></div>
		<div><span class="rpbchessboard-black-square">&nbsp;</span>&nbsp;<span class="chess4web-template-BlackFullName"></span></div>
		<div><span class="chess4web-template-FullEvent"></span></div>
		<div class="rpbchessboard-annotator"><?php
			echo sprintf(__('Commented by %1$s', 'rpbchessboard'), '<span class="chess4web-template-Annotator"></span>');
		?></div>
	</div>
	<div class="rpbchessboard-game-head">
		<p>Moves: <div class="chess4web-template-Moves"></div></p>
	</div>
</div>
