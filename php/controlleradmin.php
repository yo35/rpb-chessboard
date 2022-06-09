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


require_once RPBCHESSBOARD_ABSPATH . 'php/abstractcontroller.php';


/**
 * Controller for the admin part of the website.
 */
class RPBChessboardControllerAdmin extends RPBChessboardAbstractController {

	private $postModel;


	public function init() {

		// Post actions. MUST BE PERFORMED FIRST !!
		$this->processPostActions();

		parent::init();

		// Allow to upload .pgn files in the media
		add_filter( 'upload_mimes', array( __CLASS__, 'registerPgnMimeType' ) );

		// Admin pages
		add_action( 'admin_menu', array( $this, 'registerAdminPage' ) );
		add_filter( 'plugin_action_links_' . RPBCHESSBOARD_BASENAME, array( __CLASS__, 'registerPluginLink' ) );
	}


	private function processPostActions() {
		$this->postModel = RPBChessboardHelperLoader::loadModel( 'Post' );
		if ( $this->postModel->process() ) {
			add_action( 'admin_notices', array( $this, 'callbackPostActionMessage' ) );
		}
	}


	public function callbackPostActionMessage() {
		RPBChessboardHelperLoader::printTemplate( 'post-message', $this->postModel );
	}


	public static function registerPgnMimeType( $mimeTypes ) {
		$mimeTypes['pgn'] = 'text/plain';
		return $mimeTypes;
	}


	protected function getScriptRegistrationHook() {
		return 'admin_enqueue_scripts';
	}


	public function registerStylesheets() {
		parent::registerStylesheets();

		$ext = self::getCSSFileExtension();

		// Always enqueue the main CSS files
		wp_enqueue_style( 'rpbchessboard-npm' );

		// CSS files specific to the admin
		wp_enqueue_style( 'rpbchessboard-admin', RPBCHESSBOARD_URL . 'css/admin' . $ext, false, RPBCHESSBOARD_VERSION );
		wp_register_style( 'rpbchessboard-jquery-ui-smoothness', RPBCHESSBOARD_URL . 'third-party-libs/jquery/jquery-ui.smoothness' . $ext, false, '1.11.4' );
	}


	public function registerScripts() {
		parent::registerScripts();

		// Always enqueue the main JS files
		wp_enqueue_script( 'rpbchessboard-npm' );
	}


	private static function getCSSFileExtension() {
		return WP_DEBUG ? '.css' : '.min.css';
	}


	public function registerAdminPage() {
		add_submenu_page( 'options-general.php', 'RPB Chessboard', 'RPB Chessboard', 'manage_options', 'rpbchessboard', array( __CLASS__, 'callbackAdminPage' ) );
	}


	public static function callbackAdminPage() {
		$model = RPBChessboardHelperLoader::loadModel( 'AdminPage' );
		RPBChessboardHelperLoader::printTemplate( 'admin-page', $model );
	}


	public static function registerPluginLink( $links ) {
		$model      = RPBChessboardHelperLoader::loadModel( 'PluginLink' );
		$pluginLink = RPBChessboardHelperLoader::printTemplateOffScreen( 'plugin-link', $model );
		array_unshift( $links, $pluginLink );
		return $links;
	}

}
