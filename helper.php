<?php

/**
 * Helper functions for RPB Chessboard
 */
abstract class RPBChessBoardHelper
{
	/**
	 * Global ID counter
	 */
	private static $idCounter = 0;

	/**
	 * Allocate a new HTML node ID
	 */
	public static function makeID()
	{
		++self::$rpbchessboard_id_counter;
		return 'rpbchessboard-item'.self::$rpbchessboard_id_counter;
	}

	/**
	 * If $atts is an associative array with a key corresponding to $key, return
	 * the associated value; otherwise, return null.
	 */
	private static function readRawOption($atts, $key)
	{
		if(is_array($atts) && array_key_exists($key, $atts)) {
			return $atts[$key];
		}
		else {
			return null;
		}
	}

	/**
	 * Retrieve the optional square size argument in the associative array $atts,
	 * and return a corresponding string that can be inlined in a javascript code.
	 */
	public static function readSquareSize($atts)
	{
		$value = self::readRawOption($atts, 'square_size');
		if($value==null || !is_numeric($value)) {
			return "null";
		}
		if($value<=24) {
			return 24;
		}
		else if($value>=64) {
			return 64;
		}
		else {
			return $value = 4*round($value / 4);
		}
	}

	/**
	 * Whether the debug HTML node has already been printed or not
	 */
	private static $debugNodePrinted = false;

	/**
	 * Print the debug HTML node if necessary
	 */
	public static function printDebugNode()
	{
		if(!self::$debugNodePrinted && defined('RPBCHESSBOARD_DEBUG')) {
			echo '<pre id="jsChessLib-debug"></pre>';
			self::$debugNodePrinted = true;
		}
	}

	/**
	 * Print a message to warn the user about javascript activation
	 */
	public static function printJavascriptActivationWarning()
	{
		$currentID = self::makeID();
		?>
			<div class="rpbchessboard-javascript-warning" id="<?php echo $currentID; ?>">
				<?php echo __(
					'You need to activate javascript to enhance chess game and diagram visualization.'
				, 'rpbchessboard'); ?>
			</div>
			<script type="text/javascript">
				var domNode = document.getElementById("<?php $currentID; ?>");
				if(domNode!=null) {
					domNode.parentNode.removeChild(domNode);
				}
			</script>
		<?php
	}
}
