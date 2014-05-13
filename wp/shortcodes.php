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
 * to trigger the registration operations.
 */
abstract class RPBChessboardShortcodes
{
	/**
	 * Register the plugin shortcodes. Must be called only once.
	 */
	public static function register()
	{
		// Compatibility information -> describe which shortcode should be used to insert FEN diagrams,
		// which one to insert PGN games, etc...
		$compatibility = RPBChessboardHelperLoader::loadTrait('Compatibility');
		$fenShortcode = $compatibility->getFENShortcode();
		$pgnShortcode = $compatibility->getPGNShortcode();

		// Register the shortcodes
		add_shortcode($fenShortcode, array(__CLASS__, 'callbackShortcodeFEN'       ));
		add_shortcode($pgnShortcode, array(__CLASS__, 'callbackShortcodePGN'       ));
		add_shortcode('pgndiagram' , array(__CLASS__, 'callbackShortcodePGNDiagram'));
	}


	public static function callbackShortcodeFEN       ($atts, $content='') { return self::runShortcode('FEN'       , $atts, $content); }
	public static function callbackShortcodePGN       ($atts, $content='') { return self::runShortcode('PGN'       , $atts, $content); }
	public static function callbackShortcodePGNDiagram($atts, $content='') { return self::runShortcode('PGNDiagram', $atts, $content); }


	/**
	 * Process a shortcode.
	 *
	 * @param string $shortcodeName
	 * @param array $atts
	 * @param string $content
	 * @return string
	 */
	private static function runShortcode($shortcodeName, $atts, $content)
	{
		// TODO: reimplement this method with a dedicated controller

		// TODO: rename the shortcode models
		$modelName = $shortcodeName;
		switch($shortcodeName) {
			case 'FEN': $modelName='Fen'; break;
			case 'PGN': $modelName='Pgn'; break;
			case 'PGNDiagram': $modelName='PgnDiagram'; break;
			default: break;
		}

		// Load the model and the view
		$model = RPBChessboardHelperLoader::loadModel($modelName, array($atts, $content));
		$view  = RPBChessboardHelperLoader::loadView($model);

		// Display the view
		ob_start();
		$view->display();
		return ob_get_clean();
	}
}
