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
 * Helper functions to manage cache.
 */
abstract class RPBChessboardHelperCache {

	const GROUP = 'rpbchessboard';

	/**
	 * Return the text data corresponding to the given key.
	 *
	 * @param string $cacheKey
	 * @param string $templateName Template to use to generate the text data, if necessary.
	 * @param string $modelName Model to use to generate the text data, if necessary.
	 * @return string
	 */
	public static function get( $cacheKey, $templateName, $modelName ) {
		$result = wp_cache_get( $cacheKey, self::GROUP );
		if ( false !== $result ) {
			return $result;
		}

		$model  = RPBChessboardHelperLoader::loadModel( $modelName );
		$result = RPBChessboardHelperLoader::printTemplateOffScreen( $templateName, $model );

		wp_cache_set( $cacheKey, $result, self::GROUP );
		return $result;
	}

	/**
	 * Remove the given text data from the cache.
	 *
	 * @param string $cacheKey
	 */
	public static function remove( $cacheKey ) {
		wp_cache_delete( $cacheKey, self::GROUP );
	}
}
