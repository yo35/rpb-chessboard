<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2024  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'php/abstractcontroller.php';


/**
 * Controller for the front-end part of the website.
 */
class RPBChessboardControllerFrontend extends RPBChessboardAbstractController {


	protected function getScriptRegistrationHook() {
		return 'wp_enqueue_scripts';
	}


	public function registerStylesheets() {
		parent::registerStylesheets();

		// Enqueue the main CSS files if lazy-loading is disabled.
		if ( ! $this->getMainModel()->getLazyLoadingForCSSAndJS() ) {
			wp_enqueue_style( 'rpbchessboard-npm' );
		}
	}


	public function registerScripts() {
		parent::registerScripts();

		// Force jQuery to be loaded in the header (should be the case anyway in most themes).
		wp_enqueue_script( 'jquery' );

		// Enqueue the main JS files if lazy-loading is disabled.
		if ( ! $this->getMainModel()->getLazyLoadingForCSSAndJS() ) {
			wp_enqueue_script( 'rpbchessboard-npm' );
		}
	}

}
