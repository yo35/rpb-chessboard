<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH . 'helpers/loader.php');


/**
 * Register the plugin shortcodes.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardShortcodes
{
	public static function register()
	{
		// Compatibility information -> describe which shortcode should be used to insert FEN diagrams,
		// which one to insert PGN games, etc...
		$compatibility = RPBChessboardHelperLoader::loadTrait('Compatibility');
		$fenShortcode = $compatibility->getFENShortcode();
		$pgnShortcode = $compatibility->getPGNShortcode();
		self::$lowLevelShortcodes = array($fenShortcode, $pgnShortcode);

		// Register the shortcodes
		add_shortcode($fenShortcode, array(__CLASS__, 'callbackShortcodeFEN'       ));
		add_shortcode($pgnShortcode, array(__CLASS__, 'callbackShortcodePGN'       ));
		add_shortcode('pgndiagram' , array(__CLASS__, 'callbackShortcodePGNDiagram'));

		// A high-priority filter is required to prevent the WP engine to perform some nasty operations
		// (e.g. wptexturize, wpautop, etc...) on the text enclosed by the shortcodes.
		//
		// The priority level 8 what is used by the WP engine to process the special [embed] shortcode.
		// As the same type of low-level operation is performed here, using this priority level seems to be a good choice.
		// However, having "official" guidelines or core methods to achieve this would be desirable.
		//
		add_filter('the_content', array(__CLASS__, 'preprocessLowLevelShortcodes'), 8);
	}


	public static function callbackShortcodeFEN       ($atts, $content) { return self::runShortcode('FEN'       , true , $atts, $content); }
	public static function callbackShortcodePGN       ($atts, $content) { return self::runShortcode('PGN'       , true , $atts, $content); }
	public static function callbackShortcodePGNDiagram($atts, $content) { return self::runShortcode('PGNDiagram', false, $atts, $content); }


	/**
	 * Process a shortcode.
	 *
	 * @param string $shortcodeName
	 * @param boolean $lowLevel
	 * @param array $atts
	 * @param string $content
	 * @return string
	 */
	private static function runShortcode($shortcodeName, $lowLevel, $atts, $content)
	{
		// The content of low-level shortcodes is supposed to have been saved in `self::$lowLevelShortcodeContent`.
		if($lowLevel && isset($content) && isset(self::$lowLevelShortcodeContent[$content])) {
			$content = self::$lowLevelShortcodeContent[$content];
		}

		// Load and execute the shortcode controller.
		require_once(RPBCHESSBOARD_ABSPATH . 'controllers/shortcode.php');
		$controller = new RPBChessboardControllerShortcode($shortcodeName, $atts, $content);
		return $controller->run();
	}


	/**
	 * Replace the content of the low-level shortcodes with their respective MD5 digest,
	 * saving the original content in the associative array `self::$lowLevelShortcodeContent`.
	 *
	 * @param string $text
	 * @return $text
	 */
	public static function preprocessLowLevelShortcodes($text)
	{
		$tagMask = implode('|', self::$lowLevelShortcodes);
		$pattern = '/\\[(\\[?)(' . $tagMask . ')\\b([^\\]]*)\\](.*?)\\[\\/\\2\\](\\]?)/s';
		return preg_replace_callback($pattern, array(__CLASS__, 'preprocessLowLevelShortcode'), $text);
	}


	/**
	 * Replacement function for the low-level shortcodes.
	 *
	 * @param array $m Regular expression match array.
	 * @return string
	 */
	private static function preprocessLowLevelShortcode($m)
	{
		// Allow the [[foo]...[/foo]] syntax for escaping a tag.
		if($m[1]=='[' && $m[5]==']') {
			return $m[0];
		}

		// General case: save the shortcode content, and replace it with its MD5 digest.
		$digest = md5($m[4]);
		self::$lowLevelShortcodeContent[$digest] = $m[4];
		return '[' . $m[2] . $m[3] . ']' . $digest . '[/' . $m[2] . ']';
	}


	/**
	 * Shortcodes that need their content to be processed in a low-level manner.
	 */
	private static $lowLevelShortcodes;


	/**
	 * Saved content of the low-level shortcodes, indexed with their respective MD5 digest.
	 */
	private static $lowLevelShortcodeContent = array();
}
