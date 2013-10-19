<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/validation.php');


/**
 * Trait for loading the options controlling the aspect of chessboard widgets
 * and passed by a short-code attribute.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardTraitChessWidgetCustom extends RPBChessboardAbstractTrait
{
	private $atts;
	private $squareSizeDefined      = false;
	private $showCoordinatesDefined = false;
	private $squareSize     ;
	private $showCoordinates;


	/**
	 * Constructor.
	 *
	 * @param array $atts
	 */
	public function __construct($atts)
	{
		$this->atts = $atts;
	}


	/**
	 * Custom square size for the chessboard widgets.
	 *
	 * @return int May be null if this parameter is let undefined.
	 */
	public function getCustomSquareSize()
	{
		if(!$this->$squareSizeDefined) {
			$this->squareSize = RPBChessboardHelperValidation::validateSquareSize($this->atts['square_size']);
			$this->squareSizeDefined = true;
		}
		return $this->squareSize;
	}


	/**
	 * Custom show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean May be null if this parameter is let undefined.
	 */
	public function getDefaultShowCoordinates()
	{
		if(!$this->showCoordinatesDefined) {
			$this->showCoordinates = RPBChessboardHelperValidation::validateShowCoordinates($this->atts['show_coordinates']);
			$this->showCoordinatesDefined = true;
		}
		return $this->showCoordinates;
	}
}
