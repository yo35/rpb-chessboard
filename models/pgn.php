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


require_once(RPBCHESSBOARD_ABSPATH.'models/abstracttoplevelshortcodemodel.php');


/**
 * Model associated to the [pgn][/pgn] short-code in the frontend.
 */
class RPBChessboardModelPgn extends RPBChessboardAbstractTopLevelShortcodeModel
{
	public function __construct($atts, $content)
	{
		parent::__construct($atts, $content);
		$this->loadTrait('ChessWidgetDefault');
		$this->loadTrait('ChessWidgetCustom', $this->getAttributes());
	}


	/**
	 * By default, the wordpress engine turn the line breaks into the corresponding
	 * HTML tag (<br/>), or into paragraph separator tags (<p></p>).
	 * This filter cancel this operation.
	 */
	protected function filterShortcodeContent($content)
	{
		// Replace the </p><p> and <br/> with line breaks.
		$content = preg_replace('/ *<\/p>\s*<p> */', "\n\n", $content);
		$content = preg_replace('/<br *\/>\n/', "\n", $content);

		// Trim the content.
		$content = trim($content);

		// Replace the ellipsis character with '...'.
		$content = str_replace('&#8230;', '...', $content);

		// Apply the short-code replacement function of Wordpress to the PGN comments.
		$content = preg_replace_callback('/{([^{}]*)}/', array(self, doShortcode), $content);

		// Return the result
		return $content;
	}


	/**
	 * Callback called to process the matched comments between the [pgn][/pgn] tags.
	 */
	private static function doShortcode($matches)
	{
		return '{' . do_shortcode($matches[1]) . '}';
	}
}
