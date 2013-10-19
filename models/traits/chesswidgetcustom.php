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
	 * Return a string representing all the defined custom chessboard widget options
	 * in a JSON form.
	 *
	 * @return string This string can be inlined in a javascript script.
	 */
	public function getCustomOptionsAsJavascript()
	{
		$retVal = array();
		if(!is_null($this->getCustomSquareSize())) {
			$retVal[] = 'squareSize: ' . json_encode($this->getCustomSquareSize());
		}
		if(!is_null($this->getCustomShowCoordinates())) {
			$retVal[] = 'showCoordinates: ' . json_encode($this->getCustomShowCoordinates());
		}
		return implode(', ', $retVal);
	}


	/**
	 * Custom square size for the chessboard widgets.
	 *
	 * @return int May be null if this parameter is let undefined.
	 */
	public function getCustomSquareSize()
	{
		if(!$this->squareSizeDefined) {
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
	public function getCustomShowCoordinates()
	{
		if(!$this->showCoordinatesDefined) {
			$this->showCoordinates = RPBChessboardHelperValidation::validateShowCoordinates($this->atts['show_coordinates']);
			$this->showCoordinatesDefined = true;
		}
		return $this->showCoordinates;
	}
}
