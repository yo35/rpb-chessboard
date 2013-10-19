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
	 * By default, the wordpress engine may turn some hypen characters (ASCII)
	 * into dash characters (non-ASCII). This should be reversed in order to
	 * allow FEN parsing.
	 */
	protected function filterShortcodeContent($content)
	{
		return str_replace('&#8211;', '-', $content);
	}
}
