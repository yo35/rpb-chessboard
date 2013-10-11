<?php

/**
 * Validation functions.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardHelperValidation
{
	/**
	 * Minimum square size of the chessboard widgets.
	 */
	const MINIMUM_SQUARE_SIZE = 24;

	/**
	 * Maximum square size of the chessboard widgets.
	 */
	const MAXIMUM_SQUARE_SIZE = 64;


	/**
	 * Validate a chessboard widget square size parameter.
	 *
	 * @param mixed $value
	 * @return int May be null is the value is not valid.
	 */
	public static function validateSquareSize($value)
	{
		$value = filter_var($value, FILTER_VALIDATE_INT);
		if($value===false) {
			return null;
		}
		else {
			return min(max($value, self::MINIMUM_SQUARE_SIZE), self::MAXIMUM_SQUARE_SIZE);
		}
	}
}
