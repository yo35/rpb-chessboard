<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractshortcodemodel.php');


/**
 * Base model class for the top-level shortcodes ([fen][/fen] and [pgn][/pgn])
 * in the frontend.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractTopLevelShortcodeModel extends RPBChessboardAbstractShortcodeModel
{
	private $topLevelItemID = null;


	/**
	 * Return the name of the view to use.
	 */
	public function getViewName()
	{
		return 'TopLevelShortcode';
	}


	/**
	 * Return the name of the template to use.
	 * By default, the template to use is the one with the same name than the model.
	 *
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->getName();
	}


	/**
	 * Return the ID to use (maybe as a prefix) to identify the HTML nodes that
	 * need to be identify in the top-level shortcode view.
	 */
	public function getTopLevelItemID()
	{
		if(is_null($this->topLevelItemID)) {
			$this->topLevelItemID = self::makeID();
		}
		return $this->topLevelItemID;
	}


	/**
	 * Allocate a new HTML node ID.
	 *
	 * @return string
	 */
	private static function makeID()
	{
		++self::$idCounter;
		return 'rpbchessboard-item'.self::$idCounter;
	}


	/**
	 * Global ID counter.
	 */
	private static $idCounter = 0;
}
