<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2019  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Register the plugin JavaScript scripts.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardScripts {

	public static function register() {
		$ext = self::getJSFileExtension();

		// Moment.js (http://momentjs.com/)
		self::registerLocalizedScript(
			'rpbchessboard-momentjs',
			'third-party-libs/moment-js/moment' . $ext,
			'third-party-libs/moment-js/locales/%1$s.js',
			false,
			'2.13.0',
			false
		);

		// Dependencies resolved using NPM
		wp_register_script(
			'rpbchessboard-externals',
			RPBCHESSBOARD_URL . 'third-party-libs/npm-dependencies' . $ext,
			false,
			RPBCHESSBOARD_VERSION,
			false
		);

		// Chessboard widget
		wp_register_script(
			'rpbchessboard-chessboard',
			RPBCHESSBOARD_URL . 'js/rpbchess-ui-chessboard' . $ext,
			array(
				'rpbchessboard-externals',
				'jquery-ui-widget',
				'jquery-ui-selectable',
			),
			RPBCHESSBOARD_VERSION,
			false
		);

		// Chessgame widget
		self::registerLocalizedScript(
			'rpbchessboard-chessgame',
			'js/rpbchess-ui-chessgame' . $ext,
			'js/chessgame-locales/%1$s' . $ext,
			array(
				'rpbchessboard-externals',
				'rpbchessboard-momentjs',
				'rpbchessboard-chessboard',
				'jquery-ui-widget',
				'jquery-ui-button',
				'jquery-ui-selectable',
				'jquery-color',
				'jquery-ui-dialog',
				'jquery-ui-resizable',
			),
			RPBCHESSBOARD_VERSION
		);

		// Plugin specific
		wp_register_script(
			'rpbchessboard-backend',
			RPBCHESSBOARD_URL . 'js/backend' . $ext,
			array(
				'rpbchessboard-chessboard',
				'jquery-ui-dialog',
				'jquery-ui-accordion',
				'jquery-ui-draggable',
				'jquery-ui-droppable',
			),
			RPBCHESSBOARD_VERSION,
			false
		);

		// Additional scripts for the backend.
		// FIXME Those scripts should be enqueued only if necessary. To achieve that, we need to fix issue concerning inlined scripts,
		// interaction with the TinyMCE/QuickTag editors, and to carefully review what is used on which page.
		if ( is_admin() ) {
			wp_enqueue_script( 'rpbchessboard-chessboard' );
			wp_enqueue_script( 'rpbchessboard-chessgame' );
			wp_enqueue_script( 'jquery-ui-slider' );
			wp_enqueue_script( 'jquery-ui-tabs' );
			wp_enqueue_script( 'iris' );
			wp_enqueue_script( 'rpbchessboard-backend' );
			wp_enqueue_media();

			// QuickTags editor
			wp_enqueue_script(
				'rpbchessboard-quicktags',
				RPBCHESSBOARD_URL . 'js/quicktags' . $ext,
				array(
					'rpbchessboard-backend',
					'quicktags',
				),
				RPBCHESSBOARD_VERSION,
				true
			);
		} else {

			// In frontend, force jQuery to be loaded in the header (should be the case anyway in most themes).
			wp_enqueue_script( 'jquery' );
		}

		// Inlined scripts
		if ( is_admin() ) {
			add_action( 'admin_print_footer_scripts', array( __CLASS__, 'callbackInlinedScripts' ) );
		}

		// TinyMCE editor
		add_filter( 'mce_external_plugins', array( __CLASS__, 'callbackRegisterTinyMCEPlugin' ) );
		add_filter( 'mce_buttons', array( __CLASS__, 'callbackRegisterTinyMCEButtons' ) );
	}


	public static function callbackInlinedScripts() {
		$model = RPBChessboardHelperLoader::loadModel( 'Common/Compatibility' );
		RPBChessboardHelperLoader::printTemplate( 'Localization', $model );
	}


	public static function callbackRegisterTinyMCEPlugin( $plugins ) {
		$plugins['RPBChessboard'] = RPBCHESSBOARD_URL . 'js/tinymce' . self::getJSFileExtension();
		return $plugins;
	}

	public static function callbackRegisterTinyMCEButtons( $buttons ) {
		array_push( $buttons, 'rpb-chessboard' );
		return $buttons;
	}


	/**
	 * Return the extension to use for the included JS files.
	 *
	 * @return string
	 */
	private static function getJSFileExtension() {
		return WP_DEBUG ? '.js' : '.min.js';
	}


	/**
	 * Determine the language code to use to configure a given JavaScript library, and enqueue the required file.
	 *
	 * @param string $handle Handle of the library.
	 * @param string $relativeBasePath Relative path to the core library file.
	 * @param string $relativeLocalizationPathTemplate Relative path to where the localized files should be searched.
	 * @param array $dependencies Dependencies of the core library.
	 * @param string $version Version the library.
	 */
	private static function registerLocalizedScript( $handle, $relativeBasePath, $relativeLocalizationPathTemplate, $dependencies, $version ) {

		$relativeLocalizationPath = self::computeLocalizationPath( $relativeLocalizationPathTemplate );

		if ( isset( $relativeLocalizationPath ) ) {
			$baseHandle = $handle . '-core';
			wp_register_script( $baseHandle, RPBCHESSBOARD_URL . $relativeBasePath, $dependencies, $version, false );
			wp_register_script( $handle, RPBCHESSBOARD_URL . $relativeLocalizationPath, array( $baseHandle ), $version, false );
		} else {
			wp_register_script( $handle, RPBCHESSBOARD_URL . $relativeBasePath, $dependencies, $version, false );
		}
	}


	/**
	 * Find the localization file of a library, based on the current locale.
	 *
	 * @param string $relativeLocalizationPathTemplate Relative path to where the localized files should be searched.
	 * @return string Relative path to the localization file of the target library, or `null` if no such file could be found.
	 */
	private static function computeLocalizationPath( $relativeLocalizationPathTemplate ) {
		foreach ( self::getBlogLangCodes() as $langCode ) {

			// Does the translation script file exist for the current language code?
			$relativeLocalizationPath = sprintf( $relativeLocalizationPathTemplate, $langCode );
			if ( file_exists( RPBCHESSBOARD_ABSPATH . $relativeLocalizationPath ) ) {
				return $relativeLocalizationPath;
			}
		}

		// Otherwise, if no translation file exists, return null.
		return null;
	}


	/**
	 * Return an array of language codes that may be relevant for the blog.
	 *
	 * @return array
	 */
	private static function getBlogLangCodes() {
		if ( ! isset( self::$blogLangCodes ) ) {
			$mainLanguage        = str_replace( '_', '-', strtolower( get_locale() ) );
			self::$blogLangCodes = array( $mainLanguage );

			if ( preg_match( '/([a-z]+)\\-([a-z]+)/', $mainLanguage, $m ) ) {
				self::$blogLangCodes[] = $m[1];
			}
		}
		return self::$blogLangCodes;
	}


	/**
	 * Blog language codes.
	 */
	private static $blogLangCodes;
}
