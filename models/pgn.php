<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


require_once(RPBCHESSBOARD_ABSPATH.'models/abstract/abstracttoplevelshortcodemodel.php');


/**
 * Model associated to the [pgn][/pgn] short-code in the frontend.
 */
class RPBChessboardModelPgn extends RPBChessboardAbstractTopLevelShortcodeModel
{
	public function __construct($atts, $content)
	{
		parent::__construct($atts, $content);
		$this->loadTrait('ChessWidgetDefault');
		$this->loadTrait('ChessWidgetCustom', array($this->getAttributes()));
	}


	/**
	 * Apply some auto-format traitments to the text comments.
	 *
	 * These traitments used to be performed by the WP engine on the whole post/page content
	 * (see wp-includes/default-filters.php, filter `'the_content'`).
	 * However, the [pgn][/pgn] shortcode is processed in a low-level manner,
	 * therefore its content is preserved from these traitments, that must be applied
	 * to each text comment individually.
	 */
	protected function filterShortcodeContent($content)
	{
		return preg_replace_callback('/{([^{}]*)}/', array(__CLASS__, 'processTextComment'), $content);
	}


	/**
	 * Callback called to process the matched comments between the [pgn][/pgn] tags.
	 */
	private static function processTextComment($m)
	{
		$comment = convert_chars(convert_smilies(wptexturize($m[1])));
		return '{' . do_shortcode($comment) . '}';
	}
}
