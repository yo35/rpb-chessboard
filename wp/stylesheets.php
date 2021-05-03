<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Register the plugin CSS.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardStyleSheets {

	public static function register() {
		$ext = self::getCSSFileExtension();

		// Dependencies resolved using NPM
		$asset_file = include RPBCHESSBOARD_ABSPATH . 'build/index.asset.php';
		wp_register_style( 'rpbchessboard-npm', RPBCHESSBOARD_URL . 'build/index.css', false, $asset_file['version'] );

		// Chessgame widget
		wp_register_style( 'rpbchessboard-jquery-ui-smoothness', RPBCHESSBOARD_URL . 'third-party-libs/jquery/jquery-ui.smoothness' . $ext, false, '1.11.4' );
		wp_register_style(
			'rpbchessboard-chessgame',
			RPBCHESSBOARD_URL . 'css/rpbchess-ui-chessgame' . $ext,
			array(
				'rpbchessboard-npm',
				'wp-jquery-ui-dialog',
				'rpbchessboard-jquery-ui-smoothness',
			),
			RPBCHESSBOARD_VERSION
		);

		// CSS generated on the fly.
		// TODO replug self::enqueueCachedStyle( 'rpbchessboard-chessboard', 'small-screens.css', 'CSS/SmallScreens', 'CSS/SmallScreens' );
		// TODO replug self::enqueueCachedStyle( 'rpbchessboard-chessboard', 'theming.css', 'CSS/Theming', 'CSS/Theming' );

		// Additional CSS for the frontend/backend.
		if ( is_admin() ) {
			wp_enqueue_style( 'rpbchessboard-chessgame' );
			wp_enqueue_style( 'rpbchessboard-backend', RPBCHESSBOARD_URL . 'css/backend' . $ext, false, RPBCHESSBOARD_VERSION );
		} else {

			// Enqueue the CSS if lazy-loading is disabled.
			$compatibility = RPBChessboardHelperLoader::loadModel( 'Common/Compatibility' );
			if ( ! $compatibility->getLazyLoadingForCSSAndJS() ) {
				wp_enqueue_style( 'rpbchessboard-chessgame' );
			}
		}
	}


	private static function enqueueCachedStyle( $handle, $cacheKey, $templateName, $modelName ) {
		wp_add_inline_style( $handle, RPBChessboardHelperCache::get( $cacheKey, $templateName, $modelName ) );
	}


	/**
	 * Return the extension to use for the included CSS files.
	 *
	 * @return string
	 */
	private static function getCSSFileExtension() {
		return WP_DEBUG ? '.css' : '.min.css';
	}
}
