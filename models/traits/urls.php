<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH . 'models/traits/abstracttrait.php');


/**
 * Various URLs to the plugin administration pages.
 */
class RPBChessboardTraitURLs extends RPBChessboardAbstractTrait
{

	/**
	 * URL to the main settings page.
	 *
	 * @return string
	 */
	public function getOptionsGeneralURL() {
		return admin_url('admin.php') . '?page=rpbchessboard-options&rpbchessboard_subpage=optionsgeneral';
	}


	/**
	 * URL to the small-screen settings page.
	 *
	 * @return string
	 */
	public function getOptionsSmallScreensURL() {
		return admin_url('admin.php') . '?page=rpbchessboard-options&rpbchessboard_subpage=optionssmallscreens';
	}


	/**
	 * URL to the FEN attribute help page.
	 *
	 * @return string
	 */
	public function getHelpOnFENAttributesURL()
	{
		return admin_url('admin.php') . '?page=rpbchessboard-help&rpbchessboard_subpage=helpfenattributes';
	}


	/**
	 * URL to the PGN attribute help page.
	 *
	 * @return string
	 */
	public function getHelpOnPGNAttributesURL()
	{
		return admin_url('admin.php') . '?page=rpbchessboard-help&rpbchessboard_subpage=helppgnattributes';
	}


	/**
	 * URL to the FEN syntax help page.
	 *
	 * @return string
	 */
	public function getHelpOnFENSyntaxURL() {
		return admin_url('admin.php') . '?page=rpbchessboard-help&rpbchessboard_subpage=helpfensyntax';
	}


	/**
	 * URL to the PGN syntax help page.
	 *
	 * @return string
	 */
	public function getHelpOnPGNSyntaxURL() {
		return admin_url('admin.php') . '?page=rpbchessboard-help&rpbchessboard_subpage=helppgnsyntax';
	}
}
