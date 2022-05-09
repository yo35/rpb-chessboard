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
 * Register the plugin blocks.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardBlocks {

	public static function register() {

		register_block_type(
			'rpb-chessboard/fen',
			array(
				'api_version'     => 2,
				'editor_script'   => 'rpbchessboard-npm',
				'render_callback' => array( __CLASS__, 'callbackBlockFEN' ),
			)
		);
	}


	public static function callbackBlockFEN( $atts, $content ) {
		return self::runBlock( 'FEN', $atts, $content );
	}


	private static function runBlock( $blockName, $atts, $content ) {
		$model = RPBChessboardHelperLoader::loadModel( 'Block/' . $blockName, $atts, $content );
		return RPBChessboardHelperLoader::printTemplateOffScreen( 'Block/' . $blockName, $model );
	}
}
