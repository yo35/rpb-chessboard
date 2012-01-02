<?php

// Initialization
$current_pgn_id = rpbchessboard_make_pgn_id();

// Deal with wordpress auto-filtering
$content = str_replace('&#8211;', '-', $content);

// Display the board
?>
<pre class="chess4web-Fen-Position" id="<?php echo $current_pgn_id; ?>"><?php echo $content; ?></pre>
<script type="text/javascript">
	chess4webConfigure();
	substituteFenInlined(document.getElementById("<?php echo $current_pgn_id; ?>"));
</script>
