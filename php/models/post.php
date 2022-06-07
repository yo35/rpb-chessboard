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
 * Model handling the post data.
 */
class RPBChessboardModelPost {

	private static $VALID_FORM_ACTIONS = array(
		'ResetChessDiagramSettings',
		'ResetChessGameSettings',
		'ResetCompatibility',
		'ResetSmallScreens',
		'UpdateChessDiagramSettings',
		'UpdateChessGameSettings',
		'UpdateCompatibility',
		'UpdateSmallScreens',
	);

	private $message     = '';
	private $messageType = 'success';


	/**
	 * Process the post action, if any. Return `true` if there is a message to display as a result.
	 */
	public function process() {

		// Retrieve and validate the action code.
		$formAction = self::getFormAction();
		if ( ! $formAction || ! in_array( $formAction, self::$VALID_FORM_ACTIONS, true ) ) {
			return false;
		}

		// Validate the nonce.
		if ( ! check_admin_referer( 'rpbchessboard_post_action' ) ) {
			return false;
		}

		// Validate the user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			$this->message     = __( 'The current user is not allowed to change RPB Chessboard settings.', 'rpb-chessboard' );
			$this->messageType = 'error';
			return true;
		}

		// Load the process model and run it.
		$processModel = RPBChessboardHelperLoader::loadModel( 'PostAction/' . $formAction );
		$result       = $processModel->run();
		if ( $result ) {
			$this->message = $result;
		} else {
			$this->message     = __( 'An error has occurred while processing the request. Please retry.', 'rpb-chessboard' );
			$this->messageType = 'error';
		}
		return true;
	}


	/**
	 * Message to display as a result of the action.
	 */
	public function getMessage() {
		return $this->message;
	}


	/**
	 * `'success'`, `'warning'` or `'error'`.
	 */
	public function getMessageType() {
		return $this->messageType;
	}


	/**
	 * Value of the hidden field `rpbchessboard_action` that exists in each form created by this plugin. This is also the name of the
	 * sub-model that will be in charge of processing the action. `false` is returned if not defined. Validation is let to the responsability of the caller.
	 */
	private static function getFormAction() {
		return isset( $_POST['rpbchessboard_action'] ) ? $_POST['rpbchessboard_action'] : false;
	}

}
