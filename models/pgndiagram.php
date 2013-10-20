<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractshortcodemodel.php');


/**
 * Model associated to the [pgndiagram] short-code page in the frontend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelPgnDiagram extends RPBChessboardAbstractShortcodeModel
{
	/**
	 * Constructor.
	 *
	 * @param array $atts Attributes passed with the short-code.
	 * @param string $content Short-code enclosed content.
	 */
	public function __construct($atts, $content)
	{
		parent::__construct($atts, $content);
		$this->loadTrait('ChessWidgetCustom', $this->getAttributes());
	}


	/**
	 * Return the name of the view to use.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return 'PgnDiagram';
	}
}
