
<h3><?php _e('Reminder', 'rpbchessboard'); ?></h3>

<h4><?php _e('FEN diagram alone', 'rpbchessboard'); ?></h4>

<p>
	<?php echo sprintf(
		__(
			'Chess diagrams can be inserted in pages and posts thanks to the %1$s[fen][/fen]%2$s shortcode. '.
			'The position must be described using the %3$sForsyth-Edwards notation (FEN)%4$s. '.
			'See an example below '.
			'(left: code written by the post or page author; right: what is publicly visible).'
		, 'rpbchessboard'),
		'<span class="rpbchessboard-admin-code-inline">',
		'</span>',
		sprintf('<a href="%1$s" target="_blank">', __('http://en.wikipedia.org/wiki/Forsyth%E2%80%93Edwards_Notation', 'rpbchessboard')),
		'</a>'
	); ?>
</p>

<div class="rpbchessboard-admin-columns">
	<div class="rpbchessboard-admin-column-left">
		<div class="rpbchessboard-admin-code-block">
			<?php _e('White to move and mate in two:', 'rpbchessboard'); ?>
			<br/><br/>
			[fen]r2qkbnr/ppp2ppp/2np4/4N3/2B1P3/2N5/PPPP1PPP/R1BbK2R w KQkq - 0 6[/fen]
			<br/><br/>
			<?php _e(
				'This position is known as Légal Mate. '.
				'It is named after the French player François Antoine de Kermeur Sire de Legale (1702-1795).'
			, 'rpbchessboard'); ?>
		</div>
	</div>
	<div class="rpbchessboard-admin-column-right">
		<div class="rpbchessboard-admin-visu-block">
			<p><?php _e('White to move and mate in two:', 'rpbchessboard'); ?></p>
			<pre class="jsChessLib-fen-source" id="rpbchessboard-admin-example1">r2qkbnr/ppp2ppp/2np4/4N3/2B1P3/2N5/PPPP1PPP/R1BbK2R w KQkq - 0 6</pre>
			<script type="text/javascript">
				jsChessRenderer.processFENByID("rpbchessboard-admin-example1", 28, true);
			</script>
			<p>
				<?php _e(
					'This position is known as Légal Mate. '.
					'It is named after the French player François Antoine de Kermeur Sire de Legale (1702-1795).'
				, 'rpbchessboard'); ?>
			</p>
		</div>
	</div>
</div>



<h4><?php _e('Simple PGN game', 'rpbchessboard'); ?></h4>

TODO



<h4><?php _e('PGN game with comments, variations and diagrams', 'rpbchessboard'); ?></h4>

TODO
