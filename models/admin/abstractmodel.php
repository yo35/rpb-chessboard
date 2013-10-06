<?php

/**
 * Base class for the models used by the RPBChessboard plugin.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractModel
{
	// Miscellaneous
	private $name = null;

	// Chessboard aspect settings
	private $squareSize      = null;
	private $showCoordinates = null;


	/**
	 * Return the title of the current page.
	 */
	public abstract function getTitle();


	/**
	 * Return the name of the model.
	 */
	public function getName()
	{
		if(is_null($this->name)) {
			if(preg_match('/^RPBChessboardModel(.*)$/', get_class($this), $matches)) {
				$this->name = $matches[1];
			}
			else {
				$this->name = '';
			}
		}
		return $this->name;
	}


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


	/**
	 * Load the value of an option saved in the dedicated WP table.
	 *
	 * @param $option Name of the option. It is saved with an additional prefix
	 *        in the WP option table, to avoid naming collisions.
	 * @param $defaultValue Default option value.
	 */
	private static function loadWPOption($option, $defaultValue)
	{
		return get_option('rpbchessboard_' . $option, $defaultValue);
	}
}
