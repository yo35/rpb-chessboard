<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2026  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Base class for the admin and front-end controllers.
 */
abstract class RPBChessboardAbstractController {

    private $mainModel;
    private $assetFile;


    /**
     * Main entry point. Must be invoked in the `'init'` action hook.
     */
    public function init() {

        // CSS & JS
        add_action( $this->getScriptRegistrationHook(), array( $this, 'registerStylesheets' ) );
        add_action( $this->getScriptRegistrationHook(), array( $this, 'registerScripts' ) );

        // Blocks & shortcodes
        $this->registerBlocks();
        $this->registerShortcodes();
    }


    /**
     * Retrieve the main model of the plugin (contains the value of the current settings, the theming information, etc...
     */
    final protected function getMainModel() {
        if ( ! isset( $this->mainModel ) ) {
            $this->mainModel = RPBChessboardHelperLoader::loadModel( 'Main' );
        }
        return $this->mainModel;
    }


    // --------------------------------------------------------------------------
    // JavaScript & CSS management
    // --------------------------------------------------------------------------

    /**
     * Name of the hook to use for JS and CSS registration.
     */
    abstract protected function getScriptRegistrationHook();


    /**
     * CSS registration entry point.
     */
    public function registerStylesheets() {

        // Dependencies resolved using NPM
        $assetFile = $this->getAssetFile();
        wp_register_style( 'rpbchessboard-npm', RPBCHESSBOARD_URL . 'build/index.css', array( 'wp-jquery-ui-dialog' ), $assetFile['version'] );
    }


    /**
     * JS registration entry point.
     */
    public function registerScripts() {

        // Dependencies resolved using NPM
        $assetFile = $this->getAssetFile();
        $deps      = array_merge( $assetFile['dependencies'], array( 'jquery-ui-dialog' ) );
        wp_register_script( 'rpbchessboard-npm', RPBCHESSBOARD_URL . 'build/index.js', $deps, $assetFile['version'], false );

        // Configure JS
        $model = RPBChessboardHelperLoader::loadModel( 'InitScript', $this->getMainModel() );
        wp_localize_script( 'rpbchessboard-npm', 'RPBChessboard', $model->getParameters() );
    }


    private function getAssetFile() {
        if ( ! isset( $this->assetFile ) ) {
            $this->assetFile = include RPBCHESSBOARD_ABSPATH . 'build/index.asset.php';
        }
        return $this->assetFile;
    }


    // --------------------------------------------------------------------------
    // Block management
    // --------------------------------------------------------------------------

    /**
     * Block registration entry point.
     */
    public function registerBlocks() {
        register_block_type(
            'rpb-chessboard/fen',
            array(
                'api_version'     => 2,
                'editor_script'   => 'rpbchessboard-npm',
                'render_callback' => array( $this, 'callbackBlockFEN' ),
            )
        );
        register_block_type(
            'rpb-chessboard/pgn',
            array(
                'api_version'     => 2,
                'editor_script'   => 'rpbchessboard-npm',
                'render_callback' => array( $this, 'callbackBlockPGN' ),
            )
        );
    }

    final public function callbackBlockFEN( $atts, $content ) {
        return $this->runBlock( 'FEN', $atts, $content );
    }


    final public function callbackBlockPGN( $atts, $content ) {
        return $this->runBlock( 'PGN', $atts, $content );
    }


    private function runBlock( $blockName, $atts, $content ) {
        $model = RPBChessboardHelperLoader::loadModel( 'Block/' . $blockName, $this->getMainModel(), $atts, $content );
        return RPBChessboardHelperLoader::printTemplateOffScreen( 'block/' . strtolower( $blockName ), $model );
    }


    // --------------------------------------------------------------------------
    // Shortcode management
    // --------------------------------------------------------------------------

    /**
     * Shortcode registration entry point.
     */
    public function registerShortcodes() {

        $mainModel    = $this->getMainModel();
        $fenShortcode = $mainModel->getFENShortcode();
        $pgnShortcode = $mainModel->getPGNShortcode();

        // Register the shortcodes
        add_shortcode( $fenShortcode, array( $this, 'callbackShortcodeFEN' ) );
        add_shortcode( $pgnShortcode, array( $this, 'callbackShortcodePGN' ) );
        add_shortcode( 'pgndiagram', array( __CLASS__, 'callbackShortcodePGNDiagram' ) ); // FIXME `[pgndiagram]` deprecated since 5.3.

        // Flag the shortcodes as "non-texturized" to avoid having WP transform their content.
        add_filter( 'no_texturize_shortcodes', array( $this, 'registerNoTexturizeShortcodes' ) );
    }


    final public function callbackShortcodeFEN( $atts, $content ) {
        return $this->runShortcode( 'FEN', false, $atts, $content );
    }


    final public function callbackShortcodePGN( $atts, $content ) {
        return $this->runShortcode( 'PGN', true, $atts, $content );
    }


    public static function callbackShortcodePGNDiagram( $atts, $content ) {
        return '[#]';
    }


    private function runShortcode( $shortcodeName, $lowLevel, $atts, $content ) {
        $model = RPBChessboardHelperLoader::loadModel( 'Block/Shortcode' . $shortcodeName, $this->getMainModel(), $atts, $content );
        return RPBChessboardHelperLoader::printTemplateOffScreen( 'block/' . strtolower( $shortcodeName ), $model );
    }


    /**
     * Register the no-texturize shortcodes defined by the plugin.
     */
    final public function registerNoTexturizeShortcodes( $shortcodes ) {
        $mainModel    = $this->getMainModel();
        $fenShortcode = $mainModel->getFENShortcode();
        $pgnShortcode = $mainModel->getPGNShortcode();
        return array_merge( $shortcodes, array( $fenShortcode, $pgnShortcode ) );
    }


}
