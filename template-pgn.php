<?php

// Initialization
global $rpbchessboard_id_counter, $rpbchessboard_add_debug_tag;
++$rpbchessboard_id_counter;
$current_id_counter = $rpbchessboard_id_counter;

// Pre-node for debug messages printing
if($rpbchessboard_add_debug_tag && defined('RPBCHESSBOARD_DEBUG')) {
	echo '<pre id="chess4web-debug"></pre>';
}
$rpbchessboard_add_debug_tag = false;

// Remove the useless elements added by the Wordpress engine
$filtered_content  = '';
$length_content    = strlen($content);
$inside_commentary = false;
$start_copy_at     = 0;
$length_copy       = 0;
for($k=0; $k<$length_content; ++$k) {
	if($inside_commentary) {
		if($content[$k]=='}') {
			$length_copy = $k-$start_copy_at;
			if($length_copy>0) {
				$filtered_content .= do_shortcode(substr($content, $start_copy_at, $length_copy));
			}
			$start_copy_at     = $k;
			$inside_commentary = false;
		}
	}
	else {
		if($content[$k]=='<') {
			$length_copy = $k-$start_copy_at;
			if($length_copy>0) {
				$filtered_content .= substr($content, $start_copy_at, $length_copy);
			}
		}
		else if($content[$k]=='>') {
			$start_copy_at = $k+1;
		}
		else if($content[$k]=='{') {
			$length_copy = $k-$start_copy_at+1;
			if($length_copy>0) {
				$filtered_content .= substr($content, $start_copy_at, $length_copy);
			}
			$start_copy_at     = $k+1;
			$inside_commentary = true;
		}
	}
}
if($start_copy_at<$lg_content) {
	$filtered_content .= substr($content, $start_copy_at);
}

// Raw PGN text section
echo '<pre class="chess4web-pgn" id="rpchessboard_pgn_'.$current_id_counter.'">';
echo $filtered_content;
echo '</pre>';

// Javascript-not-enabled message
echo '<div class="chess4web-javascript-warning">';
echo __('You need to activate javascript to enhance the PGN game visualization.', 'rpbchessboard');
echo '</div>';

// Display the game
?>
<div class="chess4web-out chess4web-hide-this" id="rpchessboard_pgn_<?php echo $current_id_counter; ?>">
	<div class="rpbchessboard-game-head">
		<div><span class="rpbchessboard-white-square">&nbsp;</span>&nbsp;<span class="chess4web-template-WhiteFullName"></span></div>
		<div><span class="rpbchessboard-black-square">&nbsp;</span>&nbsp;<span class="chess4web-template-BlackFullName"></span></div>
		<div><span class="chess4web-template-FullEvent"></span></div>
		<div class="rpbchessboard-annotator"><?php
			echo sprintf(__('Commented by %1$s', 'rpbchessboard'), '<span class="chess4web-template-Annotator"></span>');
		?></div>
	</div>
	<div class="rpbchessboard-game-body">
		<div class="chess4web-template-Moves"></div>
	</div>
</div>
