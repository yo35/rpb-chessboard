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
 * Register the plugin shortcodes.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 *
 * @deprecated Only for legacy content (old pages/posts that may contain the shortcodes previously used by the plugin).
 */
abstract class RPBChessboardShortcodes {

	public static function register() {
		// Compatibility information -> describe which shortcode should be used to insert FEN diagrams,
		// which one to insert PGN games, etc...
		$compatibility               = RPBChessboardHelperLoader::loadModel( 'Common/Compatibility' );
		$fenShortcode                = $compatibility->getFENShortcode();
		$pgnShortcode                = $compatibility->getPGNShortcode();
		self::$noTexturizeShortcodes = array( $fenShortcode, $pgnShortcode );
		self::$lowLevelShortcodes    = array( $pgnShortcode );

		// Register the shortcodes
		add_shortcode( $fenShortcode, array( __CLASS__, 'callbackShortcodeFEN' ) );
		add_shortcode( $pgnShortcode, array( __CLASS__, 'callbackShortcodePGN' ) );
		add_shortcode( 'pgndiagram', array( __CLASS__, 'callbackShortcodePGNDiagram' ) );

		// Register the no-texturize shortcodes
		add_filter( 'no_texturize_shortcodes', array( __CLASS__, 'registerNoTexturizeShortcodes' ) );

		// A high-priority filter is required to prevent the WP engine to perform some nasty operations
		// (e.g. wptexturize, wpautop, etc...) on the text enclosed by the shortcodes.
		// Use of priority level 5 to be run before 'gutenberg_wpautop'.
		add_filter( 'the_content', array( __CLASS__, 'preprocessLowLevelShortcodes' ), 5 );
		add_filter( 'comment_text', array( __CLASS__, 'preprocessLowLevelShortcodes' ), 5 );
	}


	public static function callbackShortcodeFEN( $atts, $content ) {
		return self::runShortcode( 'FEN', false, $atts, $content );
	}


	public static function callbackShortcodePGN( $atts, $content ) {
		return self::runShortcode( 'PGN', true, $atts, $content );
	}


	/**
	 * Legacy support of the `[pgndiagram]` shortcode.
	 */
	public static function callbackShortcodePGNDiagram( $atts, $content ) {
		return '[#]';
	}


	/**
	 * Process a shortcode.
	 *
	 * @param string  $shortcodeName
	 * @param boolean $lowLevel
	 * @param array   $atts
	 * @param string  $content
	 * @return string
	 */
	private static function runShortcode( $shortcodeName, $lowLevel, $atts, $content ) {
		// The content of low-level shortcodes is supposed to have been saved in `self::$lowLevelShortcodeContent`.
		if ( $lowLevel && isset( $content ) && isset( self::$lowLevelShortcodeContent[ $content ] ) ) {
			$content = self::$lowLevelShortcodeContent[ $content ];
		}

		// Print the shortcode.
		$model = RPBChessboardHelperLoader::loadModel( 'Shortcode/' . $shortcodeName, $atts, $content );
		return RPBChessboardHelperLoader::printTemplateOffScreen( 'Block/' . $shortcodeName, $model );
	}


	/**
	 * Register the no-texturize shortcodes defined by the plugin with WP engine.
	 *
	 * @param array $shortcodes Global list of no-texturize shortcodes.
	 * @return array
	 */
	public static function registerNoTexturizeShortcodes( $shortcodes ) {
		return array_merge( $shortcodes, self::$noTexturizeShortcodes );
	}


	/**
	 * Replace the content of the low-level shortcodes with their respective MD5 digest,
	 * saving the original content in the associative array `self::$lowLevelShortcodeContent`.
	 *
	 * @param string $text
	 * @return $text
	 */
	public static function preprocessLowLevelShortcodes( $text ) {
		$tagMask = implode( '|', self::$lowLevelShortcodes );
		$pattern = '/\\[(\\[?)(' . $tagMask . ')\\b([^\\]]*)\\](.*?)\\[\\/\\2\\](\\]?)/s';
		return preg_replace_callback( $pattern, array( __CLASS__, 'preprocessLowLevelShortcode' ), $text );
	}


	/**
	 * Replacement function for the low-level shortcodes.
	 *
	 * @param array $m Regular expression match array.
	 * @return string
	 */
	private static function preprocessLowLevelShortcode( $m ) {
		// Allow the [[foo]...[/foo]] syntax for escaping a tag.
		if ( '[' === $m[1] && ']' === $m[5] ) {
			return $m[0];
		}

		// General case: save the shortcode content, and replace it with its MD5 digest.
		$digest                                    = md5( $m[4] );
		self::$lowLevelShortcodeContent[ $digest ] = $m[4];
		return '[' . $m[2] . $m[3] . ']' . $digest . '[/' . $m[2] . ']';
	}


	/**
	 * Shortcodes for which the "texturize" filter performed by the WP engine on post content
	 * must be disabled.
	 */
	private static $noTexturizeShortcodes;


	/**
	 * Shortcodes that need their content to be processed in a low-level manner.
	 */
	private static $lowLevelShortcodes;


	/**
	 * Saved content of the low-level shortcodes, indexed with their respective MD5 digest.
	 */
	private static $lowLevelShortcodeContent = array();
}
