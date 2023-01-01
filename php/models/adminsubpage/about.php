<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2023  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/adminsubpage/abstract.php';


/**
 * Delegate model for the sub-page 'about'.
 */
class RPBChessboardModelAdminSubPageAbout extends RPBChessboardAbstractModelAdminSubPage {


	public function getTemplateName() {
		return 'about';
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
			(object) array(
				'name' => 'Paolo Fantozzi',
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
				'lang' => 'Čeština',
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
				'code' => 'hu',
				'lang' => 'Magyar',
				'name' => 'Atilla Szvetlik',
			),
			(object) array(
				'code' => 'it',
				'lang' => 'Italiano',
				'name' => 'Andrea Cuccarini',
			),
			(object) array(
				'code' => 'nl',
				'lang' => 'Nederlands',
				'name' => 'Ivan Deceuninck',
			),
			(object) array(
				'code' => 'pl',
				'lang' => 'Polski',
				'name' => 'Dawid Ziółkowski',
			),
			(object) array(
				'code' => 'br', // WARNING: language code is pt
				'lang' => 'Português do Brasil',
				'name' => 'Rewbenio Frota',
				'link' => 'http://www.lancesqi.com.br/',
			),
			(object) array(
				'code' => 'ru',
				'lang' => 'Русский',
				'name' => 'Sergey Baravicov',
			),
			(object) array(
				'code' => 'tr',
				'lang' => 'Türkçe',
				'name' => 'Ali Nihat Yazıcı',
			),
		);
	}

}
