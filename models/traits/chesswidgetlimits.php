<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/validation.php');


/**
 * Trait for the limit that could be set on the parameters defining the aspect
 * of chessboard widgets.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardTraitChessWidgetLimits extends RPBChessboardAbstractTrait
{
	/**
	 * Minimum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMinimumSquareSize()
	{
		return RPBChessboardHelperValidation::MINIMUM_SQUARE_SIZE;
	}


	/**
	 * Maximum square size of the chessboard widgets.
	 *
	 * @return int
	 */
	public function getMaximumSquareSize()
	{
		return RPBChessboardHelperValidation::MAXIMUM_SQUARE_SIZE;
	}


	/**
	 * Number of digits of the maximum square size parameter.
	 *
	 * @return int
	 */
	public function getDigitNumberForSquareSize()
	{
		$maxVal = $this->getMaximumSquareSize();
		return 1 + floor(log10($maxVal));
	}


	/**
	 * The square size of the chessboard widgets must be a multiple of this value.
	 *
	 * @return int
	 */
	public function getStepSquareSize()
	{
		return RPBChessboardHelperValidation::STEP_SQUARE_SIZE;
	}
}
