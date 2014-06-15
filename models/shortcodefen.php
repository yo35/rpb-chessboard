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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/shortcode.php');


/**
 * Model associated to the [fen][/fen] shortcode.
 */
class RPBChessboardModelShortcodeFen extends RPBChessboardAbstractModelShortcode
{
	public function __construct($atts, $content)
	{
		parent::__construct($atts, $content);
		$this->loadTrait('DefaultOptions');
	}


	/**
	 * Return the FEN string describing the position.
	 *
	 * @return string
	 */
	public function getFENString()
	{
		return $this->getContent();
	}
}
