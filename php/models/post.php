<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2025  Yoann Le Montagner <yo35 -at- melix.net>       *
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
        'Settings/ChessDiagram'  => array( 'update', 'reset' ),
        'Settings/ChessGame'     => array( 'update', 'reset' ),
        'Settings/Compatibility' => array( 'update', 'reset' ),
        'Settings/SmallScreens'  => array( 'update', 'reset' ),
        'Theming/Colorset'       => array( 'add', 'edit', 'delete' ),
        'Theming/Pieceset'       => array( 'add', 'edit', 'delete' ),
    );

    private $message     = '';
    private $messageType = 'success';
    private $processModel;
    private $processMethod;


    /**
     * Process the post action, if any. Return `true` if there is a message to display as a result.
     */
    public function process() {

        // Load and validate the action code.
        if ( ! $this->loadFormAction() ) {
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
        $processModel      = RPBChessboardHelperLoader::loadModel( 'PostAction/' . $this->processModel );
        $processMethod     = $this->processMethod;
        $result            = $processModel->$processMethod();
        $this->message     = $result->message;
        $this->messageType = $result->messageType;
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
     * Read and validate the value of the hidden field `rpbchessboard_action` that exists in each form created by this plugin.
     *
     * This field contains the name of the sub-model and the method that will be in charge of processing the action.
     * `false` is returned if the value is undefined or invalid.
     */
    private function loadFormAction() {
        if ( ! isset( $_POST['rpbchessboard_action'] ) ) {
            return false;
        }

        $formAction = $_POST['rpbchessboard_action'];
        $sep        = strpos( $formAction, ':' );
        if ( $sep < 0 ) {
            return false;
        }

        // formAction = processModel:processMethod
        $this->processModel  = substr( $formAction, 0, $sep );
        $this->processMethod = substr( $formAction, $sep + 1, strlen( $formAction ) - $sep - 1 );
        return isset( self::$VALID_FORM_ACTIONS[ $this->processModel ] ) && in_array( $this->processMethod, self::$VALID_FORM_ACTIONS[ $this->processModel ], true );
    }

}
