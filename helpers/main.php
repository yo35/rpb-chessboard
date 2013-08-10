<?php

/**
 * Common helper functions for RPB Chessboard
 */
abstract class RPBChessBoardMainHelper
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
		++self::$idCounter;
		return 'rpbchessboard-item'.self::$idCounter;
	}

	/**
	 * If $atts is an associative array with a key corresponding to $key, return
	 * the associated value; otherwise, return null.
	 */
	private static function getRawOption($atts, $key)
	{
		if(is_array($atts) && array_key_exists($key, $atts)) {
			return $atts[$key];
		}
		else {
			return null;
		}
	}

	/**
	 * Retrieve the optional show final result argument in the associative array $atts,
	 * and return PHP boolean value.
	 */
	public static function getShowFinalResult($atts)
	{
		$value = self::getRawOption($atts, 'show_final_result');
		if($value!=null && strtolower($value)=="false") {
			return false;
		}
		else {
			return true;
		}
	}

	/**
	 * Retrieve the optional square size argument in the associative array $atts,
	 * and return a corresponding string that can be inlined in a javascript code.
	 */
	public static function readSquareSize($atts)
	{
		$value = self::getRawOption($atts, 'square_size');
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
	 * Retrieve the optional show coordinates arguments in the associative array $atts,
	 * and return a corresponding string that can be inlined in a javascript code.
	 */
	public static function readShowCoordinates($atts)
	{
		$value = self::getRawOption($atts, 'show_coordinates');
		if($value==null) {
			return "null";
		}
		$value = strtolower($value);
		if($value=="true" || $value=="false") {
			return $value;
		}
		else {
			return "null";
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
				var domNode = document.getElementById("<?php echo $currentID; ?>");
				if(domNode!=null) {
					domNode.parentNode.removeChild(domNode);
				}
			</script>
		<?php
	}
}
