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
	 * The square size of the chessboard widgets must be a multiple of this value.
	 */
	const STEP_SQUARE_SIZE = 4;


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
			$value = min(max($value, self::MINIMUM_SQUARE_SIZE), self::MAXIMUM_SQUARE_SIZE);
			$value = round($value / self::STEP_SQUARE_SIZE) * self::STEP_SQUARE_SIZE;
			return $value;
		}
	}


	/**
	 * Validate a chessboard widget show-coordinates parameter.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function validateShowCoordinates($value)
	{
		return (is_null($value) || $value==='') ? null : filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}


	/**
	 * Validate a boolean specified as an integer value.
	 *
	 * @param mixed $value
	 * @return boolean May be null is the value is not valid.
	 */
	public static function prefilterBooleanFromInt($value)
	{
		$value = filter_var($value, FILTER_VALIDATE_INT);
		if     ($value===0) return false;
		else if($value===1) return true ;
		else                return null ;
	}
}
