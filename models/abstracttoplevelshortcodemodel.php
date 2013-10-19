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
	 * Whether the javascript initialization template needs to be enqueued.
	 *
	 * @return boolean
	 */
	public function isTemplateInitRequired()
	{
		return !self::$javascriptInitEnqueued;
	}


	/**
	 * Prepare the model for being used by the javascript initialization template.
	 */
	public function prepareForTemplateInit()
	{
		$this->loadTrait('ChessWidgetDefault');
		self::$javascriptInitEnqueued = true;
	}


	/**
	 * Return the name of the view to use.
	 *
	 * @return string
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
	 *
	 * @return string
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


	/**
	 * Flag indicating whether the initialization of the javascript objects has been done yet.
	 */
	private static $javascriptInitEnqueued = false;
}
