<?php

/**
 * Base trait class.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractTrait
{
	/**
	 * Load the value of an option saved in the dedicated WP table.
	 *
	 * @param $option Name of the option. It is saved with an additional prefix
	 *        in the WP option table, to avoid naming collisions.
	 * @param $defaultValue Default option value.
	 */
	protected static function loadWPOption($option, $defaultValue)
	{
		return get_option('rpbchessboard_' . $option, $defaultValue);
	}
}
