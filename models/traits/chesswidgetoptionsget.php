<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');

/**
 * Trait for loading the options of associated to chessboard widgets from the WP database.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardTraitChessWidgetOptionsGet extends RPBChessboardAbstractTrait
{
	private $squareSize      = null;
	private $showCoordinates = null;


	/**
	 * Default square size for the chessboard widgets.
	 */
	public function getSquareSize()
	{
		if(is_null($this->squareSize)) {
			$this->squareSize = self::loadWPOption('squareSize', 32);
		}
		return $this->squareSize;
	}
}
