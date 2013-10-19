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
	 * Return the name of the view to use.
	 */
	public function getViewName()
	{
		return 'TopLevelShortcode';
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
