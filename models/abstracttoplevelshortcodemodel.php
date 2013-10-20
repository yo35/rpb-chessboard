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
	 * Whether the current model needs the initialization template.
	 * This function may be overriden by derived classes.
	 *
	 * @return boolean False by default.
	 */
	public function isInitializationTemplateRequired()
	{
		return false;
	}


	/**
	 * Whether the current model needs the localization template.
	 * This function may be overriden by derived classes.
	 *
	 * @return boolean False by default.
	 */
	public function isLocalizationTemplateRequired()
	{
		return false;
	}


	/**
	 * Mark the initialization template as enqueued if not done yet and if required.
	 * This function should be called from the view.
	 *
	 * @return boolean
	 */
	public function mustEnqueueInitializationTemplate()
	{
		if(self::$initializationAlreadyEnqueued || !$this->isInitializationTemplateRequired()) {
			return false;
		}
		$this->loadTrait('ChessWidgetDefault');
		self::$initializationAlreadyEnqueued = true;
		return true;
	}


	/**
	 * Mark the localization template as enqueued if not done yet and if required.
	 * This function should be called from the view.
	 *
	 * @return boolean
	 */
	public function mustEnqueueLocalizationTemplate()
	{
		if(self::$localizationAlreadyEnqueued || !$this->isLocalizationTemplateRequired()) {
			return false;
		}
		self::$localizationAlreadyEnqueued = true;
		return true;
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
	 * Flag indicating whether the initialization template has already been enqueued or not.
	 */
	private static $initializationAlreadyEnqueued = false;

	/**
	 * Flag indicating whether the localization template has already been enqueued or not.
	 */
	private static $localizationAlreadyEnqueued = false;
}
