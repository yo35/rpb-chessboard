<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstracttoplevelshortcodemodel.php');


/**
 * Model associated to the [fen][/fen] short-code page in the frontend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelFen extends RPBChessboardAbstractTopLevelShortcodeModel
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
	 * By default, the wordpress engine may turn some hypen characters (ASCII)
	 * into dash characters (non-ASCII). This should be reversed in order to
	 * allow FEN parsing.
	 */
	protected function filterShortcodeContent($content)
	{
		return str_replace('&#8211;', '-', $content);
	}
}
