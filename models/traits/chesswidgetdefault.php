<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/validation.php');


/**
 * Trait for loading the default options of associated to chessboard widgets
 * from the WP database.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardTraitChessWidgetDefault extends RPBChessboardAbstractTrait
{
	private $squareSize      = null;
	private $showCoordinates = null;


	/**
	 * Initial default square size of the chessboard widgets.
	 */
	const DEFAULT_SQUARE_SIZE = 32;

	/**
	 * Initial default show-coordinates parameter of the chessboard widgets.
	 */
	const DEFAULT_SHOW_COORDINATES = true;


	/**
	 * Default square size for the chessboard widgets.
	 *
	 * @return int
	 */
	public function getDefaultSquareSize()
	{
		if(is_null($this->squareSize)) {
			$value = RPBChessboardHelperValidation::validateSquareSize(get_option('rpbchessboard_squareSize'));
			return is_null($value) ? self::DEFAULT_SQUARE_SIZE : $value;
		}
		return $this->squareSize;
	}


	/**
	 * Default show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean
	 */
	public function getDefaultShowCoordinates()
	{
		if(is_null($this->showCoordinates)) {
			$value = RPBChessboardHelperValidation::validateShowCoordinates(get_option('rpbchessboard_showCoordinates'));
			return is_null($value) ? self::DEFAULT_SHOW_COORDINATES : $value;
		}
		return $this->showCoordinates;
	}
}
