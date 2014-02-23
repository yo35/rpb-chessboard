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


require_once(RPBCHESSBOARD_ABSPATH.'views/abstractview.php');


/**
 * Generic view for the short-codes [fen][/fen] and [pgn][/pgn].
 */
class RPBChessboardViewTopLevelShortcode extends RPBChessboardAbstractView
{
	public function display()
	{
		$model = $this->getModel();
		if(!self::$localizationTemplateAlreadyEnqueued) {
			include(RPBCHESSBOARD_ABSPATH.'templates/localization.php');
			self::$localizationTemplateAlreadyEnqueued = true;
		}
		include(RPBCHESSBOARD_ABSPATH.'templates/shortcode/javascriptwarning.php');
		include(RPBCHESSBOARD_ABSPATH.'templates/shortcode/'.strtolower($model->getTemplateName()).'.php');
	}


	/**
	 * Flag to indicate whether the localization template has already been enqueued or not.
	 */
	private static $localizationTemplateAlreadyEnqueued = false;
}
