<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Helper functions to manage cache.
 */
abstract class RPBChessboardHelperCache {

	/**
	 * Return the given cached data
	 *
	 * @param string $cacheKey
	 * @return string
	 */
	public static function get( $cacheKey ) {
		return wp_cache_get( $cacheKey );
	}

	/**
	 * Return the version of the given cached file.
	 *
	 * @param string $cacheKey
	 * @return string
	 */
	public static function getVersion( $cacheKey ) {
		return get_option( 'rpbchessboard_cache_' . $cacheKey, '0' );
	}

	/**
	 * Write a file into the cache. Nothing happens if the file already exists.
	 *
	 * @param string $cacheKey
	 * @param string $templateName Template to use to generate the file, if necessary.
	 * @param string $modelName Model to use to generate the file, if necessary.
	 */
	public static function ensureExists( $cacheKey, $templateName, $modelName ) {
		if ( false !== wp_cache_get( $cacheKey ) ) {
			return;
		}

		$model = RPBChessboardHelperLoader::loadModel( $modelName );
		$text  = RPBChessboardHelperLoader::printTemplateOffScreen( $templateName, $model );

		wp_cache_set( $cacheKey, $text );
		update_option( 'rpbchessboard_cache_' . $cacheKey, uniqid() );
	}

	/**
	 * Remove the given file from the cache.
	 *
	 * @param string $cacheKey
	 */
	public static function remove( $cacheKey ) {
		wp_cache_delete( $cacheKey );
	}
}
