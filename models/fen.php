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
 * Model associated to the [fen][/fen] short-code in the frontend.
 */
class RPBChessboardModelFen extends RPBChessboardAbstractTopLevelShortcodeModel
{
	public function __construct($atts, $content)
	{
		parent::__construct($atts, $content);
		$this->loadTrait('ChessWidgetCustom', $this->getAttributes());
	}

	public function isInitializationTemplateRequired()
	{
		return true;
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
