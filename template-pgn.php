<?php

	// Helper
	require_once(RPBCHESSBOARD_ABSPATH.'helpers/main.php');

	// ID for the current element
	$currentID = RPBChessBoardMainHelper::makeID();

	// Remove the useless HTML tags added by the Wordpress engine
	$filtered_content  = '';
	$length_content    = strlen($content);
	$inside_commentary = false;
	$start_copy_at     = 0;
	$length_copy       = 0;
	for($k=0; $k<$length_content; ++$k) {
		if($inside_commentary)
		{
			// Detect the end of a commentary.
			if($content[$k]=='}') {
				$length_copy = $k-$start_copy_at;
				if($length_copy>0) {
					$commentary = do_shortcode(substr($content, $start_copy_at, $length_copy));
					$commentary = str_replace('&nbsp;', ' ', $commentary);
					$filtered_content .= $commentary;
				}
				$start_copy_at     = $k;
				$inside_commentary = false;
			}
		}
		else
		{
			// Outside commentaries, the HTML tags are filtered out.
			if($content[$k]=='<') {
				$length_copy = $k-$start_copy_at;
				if($length_copy>0) {
					$filtered_content .= substr($content, $start_copy_at, $length_copy);
				}
			}
			else if($content[$k]=='>') {
				$start_copy_at = $k+1;
			}

			// Detect the beginning of a commentary.
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

	// Do not forget to copy the end of the text.
	if($start_copy_at<$lg_content) {
		$filtered_content .= substr($content, $start_copy_at);
	}
?>

<pre class="jsChessLib-pgn-source" id="<?php echo $currentID; ?>-in"><?php echo $filtered_content; ?></pre>
<div class="jsChessLib-invisible" id="<?php echo $currentID; ?>-out">
	<div class="rpbchessboard-game-head">
		<div class="jsChessLib-field-fullNameWhite">
			<span class="rpbchessboard-white-square">&nbsp;</span>
			<span class="jsChessLib-anchor-fullNameWhite"></span>
		</div>
		<div class="jsChessLib-field-fullNameBlack">
			<span class="rpbchessboard-black-square">&nbsp;</span>
			<span class="jsChessLib-anchor-fullNameBlack"></span>
		</div>
		<div class="jsChessLib-field-Event">
			<span class="jsChessLib-anchor-Event"></span>
			<span class="jsChessLib-field-Round">(<span class="jsChessLib-anchor-Round"></span>)</span>
			<span class="jsChessLib-field-Date">- <span class="jsChessLib-anchor-Date"></span></span>
		</div>
		<div class="jsChessLib-field-Annotator"><?php
			echo sprintf(__('Commented by %1$s', 'rpbchessboard'), '<span class="jsChessLib-anchor-Annotator"></span>');
		?></div>
	</div>
	<div class="rpbchessboard-game-body jsChessLib-field-moves">
		<span class="jsChessLib-anchor-moves"></span>
		<div class="jsChessLib-field-Result">
			<span class="jsChessLib-anchor-Result"></span>
		</div>
	</div>
</div>
<script type="text/javascript">
	jsChessRenderer.processPGNByID(
		"<?php echo $currentID; ?>"
	);
</script>

<?php
	RPBChessBoardMainHelper::printJavascriptActivationWarning();
?>
