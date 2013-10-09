<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');

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
			$this->squareSize = (int)(self::loadWPOption('squareSize', 32));
		}
		return $this->squareSize;
	}


	/**
	 * Default show-coordinates parameter for the chessboard widgets.
	 */
	public function getDefaultShowCoordinates()
	{
		if(is_null($this->showCoordinates)) {
			$this->showCoordinates = (int)(self::loadWPOption('squareSize', 1))!=0;
		}
		return $this->showCoordinates;
	}
}
