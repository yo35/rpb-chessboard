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
 * Process the POST actions.
 *
 * This class is not constructible. Call the static method `run()`
 * to execute the actions (must be called only once).
 */
abstract class RPBChessboardPostActions {

	/**
	 * Look at the POST variable `$_POST['rpbchessboard_action']` and execute the corresponding action, if any.
	 */
	public static function run() {
		switch ( self::getPostAction() ) {
			case 'update-options':
				self::executeAction( 'SaveOptions', 'updateOptions' );
				break;
			case 'set-default-colorset':
				self::executeAction( 'SaveOptions', 'updateDefaultColorset' );
				break;
			case 'set-default-pieceset':
				self::executeAction( 'SaveOptions', 'updateDefaultPieceset' );
				break;
			case 'reset-general':
				self::executeAction( 'ResetOptions', 'resetGeneral' );
				break;
			case 'reset-compatibility':
				self::executeAction( 'ResetOptions', 'resetCompatibility' );
				break;
			case 'reset-smallscreens':
				self::executeAction( 'ResetOptions', 'resetSmallScreens' );
				break;
			case 'add-colorset':
				self::executeAction( 'ThemingColorset', 'add' );
				break;
			case 'edit-colorset':
				self::executeAction( 'ThemingColorset', 'edit' );
				break;
			case 'delete-colorset':
				self::executeAction( 'ThemingColorset', 'delete' );
				break;
			case 'add-pieceset':
				self::executeAction( 'ThemingPieceset', 'add' );
				break;
			case 'edit-pieceset':
				self::executeAction( 'ThemingPieceset', 'edit' );
				break;
			case 'delete-pieceset':
				self::executeAction( 'ThemingPieceset', 'delete' );
				break;
			default:
				break;
		}
	}


	/**
	 * Load the model `$postModelName`, and execute the method `$methodName` supposedly defined by this model.
	 *
	 * @param string $postModelName
	 * @param string $methodName
	 * @param string $capability Required capability to execute the action. Default is `'manage_options'`.
	 */
	private static function executeAction( $postModelName, $methodName, $capability = 'manage_options' ) {
		check_admin_referer( 'rpbchessboard_post_action' );
		if ( ! current_user_can( $capability ) ) {
			return;
		}

		$postModel = RPBChessboardHelperLoader::loadModelLegacy( 'Post/' . $postModelName );
		$message   = $postModel->$methodName();

		require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/adminpage.php';
		RPBChessboardAbstractModelAdminPage::initializePostMessage( $message );
	}


	/**
	 * Return the name of the action that should be performed by the server.
	 * The action is initiated by the user when clicking on a "submit" button in
	 * an HTML form with its method attribute set to POST.
	 *
	 * This function may return an empty string if no action is required.
	 *
	 * @return string
	 */
	private static function getPostAction() {
		return isset( $_POST['rpbchessboard_action'] ) ? $_POST['rpbchessboard_action'] : '';
	}
}
