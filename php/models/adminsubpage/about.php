<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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


/**
 * Delegate model for the sub-page 'about'.
 */
class RPBChessboardModelAdminSubPageAbout {

	public function getPluginVersion() {
		return RPBCHESSBOARD_VERSION;
	}

	public function getPluginContributors() {
		return array(
			(object) array(
				'name' => 'Marek Śmigielski',
			),
			(object) array(
				'name' => 'Paul Schreiber',
				'link' => 'https://paulschreiber.com/',
			),
			(object) array(
				'name' => 'Adam Silverstein',
				'link' => 'http://www.10up.com/',
			),
		);
	}

	public function getPluginTranslators() {

		// Flag images obtained from https://github.com/niltsh/iso-country-flags-svg-collection
		// Style 'simple'
		// Size 40 x 30

		return array(
			(object) array(
				'code' => 'cz', // WARNING: language code is cs
				'lang' => 'Czech',
				'name' => 'Jan Jílek',
			),
			(object) array(
				'code' => 'de',
				'lang' => 'Deutsch',
				'name' => 'Markus Liebelt',
			),
			(object) array(
				'code' => 'gb', // WARNING: language code is en
				'lang' => 'English',
				'name' => 'Yoann Le Montagner',
			),
			(object) array(
				'code' => 'es',
				'lang' => 'Español',
				'name' => 'Martin Frith',
			),
			(object) array(
				'code' => 'fr',
				'lang' => 'Français',
				'name' => 'Yoann Le Montagner',
			),
			(object) array(
				'code' => 'it',
				'lang' => 'Italiano',
				'name' => 'Andrea Cuccarini',
			),
			(object) array(
				'code' => 'nl',
				'lang' => 'Dutch',
				'name' => 'Ivan Deceuninck',
			),
			(object) array(
				'code' => 'pl',
				'lang' => 'Polski',
				'name' => 'Dawid Ziółkowski',
			),
			(object) array(
				'code' => 'br', // WARNING: language code is pt
				'lang' => 'Brazillian Portuguese',
				'name' => 'Rewbenio Frota',
				'link' => 'http://www.lancesqi.com.br/',
			),
			(object) array(
				'code' => 'ru',
				'lang' => 'Russian',
				'name' => 'Sergey Baravicov',
			),
			(object) array(
				'code' => 'tr',
				'lang' => 'Turkish',
				'name' => 'Ali Nihat Yazıcı',
			),
		);
	}

}
