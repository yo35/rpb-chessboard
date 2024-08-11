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
        self::registerNewsNotice();
    }


    // --------------------------------------------------------------------------
    // POST data (aka. form submitted data) management
    // --------------------------------------------------------------------------

    private function processPostActions() {
        $this->postModel = RPBChessboardHelperLoader::loadModel( 'Post' );
        if ( $this->postModel->process() ) {
            add_action( 'admin_notices', array( $this, 'callbackPostActionMessage' ) );
        }
    }


    public function callbackPostActionMessage() {
        RPBChessboardHelperLoader::printTemplate( 'post-message', $this->postModel );
    }


    // --------------------------------------------------------------------------
    // Custom MIME types
    // --------------------------------------------------------------------------

    public static function registerPgnMimeType( $mimeTypes ) {
        $mimeTypes['pgn'] = 'text/plain';
        return $mimeTypes;
    }


    // --------------------------------------------------------------------------
    // JavaScript & CSS management
    // --------------------------------------------------------------------------

    protected function getScriptRegistrationHook() {
        return 'admin_enqueue_scripts';
    }


    public function registerStylesheets() {
        parent::registerStylesheets();

        $ext = self::getCSSFileExtension();

        // Always enqueue the main CSS files
        wp_enqueue_style( 'rpbchessboard-npm' );

        // CSS files specific to the admin
        wp_register_style( 'rpbchessboard-admin', RPBCHESSBOARD_URL . 'css/admin' . $ext, false, RPBCHESSBOARD_VERSION );
        wp_register_style( 'rpbchessboard-jquery-ui-smoothness', RPBCHESSBOARD_URL . 'third-party-libs/jquery/jquery-ui.smoothness' . $ext, false, '1.13.1-slider' );
    }


    public function registerScripts() {
        parent::registerScripts();

        // Ensure jQuery is enqueued in the admin (should be the case by default anyway).
        wp_enqueue_script( 'jquery' );

        // Always enqueue the main JS files
        wp_enqueue_script( 'rpbchessboard-npm' );
    }


    private static function getCSSFileExtension() {
        return WP_DEBUG ? '.css' : '.min.css';
    }


    // --------------------------------------------------------------------------
    // Plugin content in the admin
    // --------------------------------------------------------------------------

    /**
     * Create the plugin admin page + register it in the main menu.
     */
    public function registerAdminPage() {
        add_submenu_page( 'options-general.php', 'RPB Chessboard', 'RPB Chessboard', 'manage_options', 'rpbchessboard', array( __CLASS__, 'callbackAdminPage' ) );
    }


    /**
     * Callback for admin page rendering.
     */
    public static function callbackAdminPage() {
        $model = RPBChessboardHelperLoader::loadModel( 'AdminPage' );
        RPBChessboardHelperLoader::printTemplate( 'admin-page', $model );
    }


    /**
     * Custom links to the RPB Chessboard's admin page in the general plugin management page.
     */
    public static function registerPluginLink( $links ) {
        $model      = RPBChessboardHelperLoader::loadModel( 'PluginLink' );
        $pluginLink = RPBChessboardHelperLoader::printTemplateOffScreen( 'plugin-link', $model );
        array_unshift( $links, $pluginLink );
        return $links;
    }


    /**
     * Special notice to display some info (advertisement...) regarding the plugin in the admin.
     */
    private static function registerNewsNotice() {
        add_action( 'admin_notices', array( __CLASS__, 'callbackNewsNotice' ) );
        add_action( 'wp_ajax_rpbchessboard-dismissNewsNotice', array( __CLASS__, 'handleNewsNoticeDismissRequest' ) );
    }


    public static function callbackNewsNotice() {
        $model = RPBChessboardHelperLoader::loadModel( 'NewsNotice' );
        if ( $model->hasNoticeToDisplay() ) {
            RPBChessboardHelperLoader::printTemplate( 'news-notice', $model );
        }
    }


    public static function handleNewsNoticeDismissRequest() {
        $model = RPBChessboardHelperLoader::loadModel( 'NewsNotice' );
        $model->handleDismissRequest();
    }

}
