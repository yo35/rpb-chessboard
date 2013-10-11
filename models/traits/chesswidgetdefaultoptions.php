<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/validation.php');


/**
 * Trait for loading the default options of associated to chessboard widgets
 * from the WP database.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardTraitChessWidgetDefaultOptions extends RPBChessboardAbstractTrait
{
	private $squareSize      = null;
	private $showCoordinates = null;


	/**
	 * Default square size for the chessboard widgets.
	 */
	public function getDefaultSquareSize()
	{
		if(is_null($this->squareSize)) {
			$value = RPBChessboardHelperValidation::validateSquareSize(get_option('rpbchessboard_squareSize'));
			return is_null($value) ? 32 : $value; // TODO: default value somewhere else.
		}
		return $this->squareSize;
	}


	/**
	 * Default show-coordinates parameter for the chessboard widgets.
	 */
	public function getDefaultShowCoordinates()
	{
		if(is_null($this->showCoordinates)) {
			$value = RPBChessboardHelperValidation::validateShowCoordinates(get_option('rpbchessboard_showCoordinates'));
			return is_null($value) ? true : $value; // TODO: default value somewhere else.
		}
		return $this->showCoordinates;
	}
}
