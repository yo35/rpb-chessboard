<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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
	 * Return the URL of the given cached file.
	 *
	 * @param string $fileName File-name, relative to the cache root.
	 * @return string
	 */
	public static function getURL($fileName) {
		return RPBCHESSBOARD_URL . 'cache/' . $fileName;
	}


	/**
	 * Check whether the given file exists in the cache or not.
	 *
	 * @param string $fileName File-name, relative to the cache root.
	 * @return boolean
	 */
	public static function exists($fileName) {
		return file_exists(self::getRoot() . $fileName);
	}


	/**
	 * Write a file into the cache. Nothing happens if the file already exists.
	 *
	 * @param string $fileName File-name, relative to the cache root.
	 * @param string $templateName Template to use to generate the file, if necessary.
	 * @param string $modelName Model to use to generate the file, if necessary.
	 */
	public static function ensureExists($fileName, $templateName, $modelName) {
		if(!self::exists($fileName)) {
			self::refresh($fileName, $templateName, $modelName);
		}
	}


	/**
	 * Write a file into the cache. If the file already exists, it is overwritten.
	 *
	 * @param string $fileName File-name, relative to the cache root.
	 * @param string $templateName Template to use to generate the file, if necessary.
	 * @param string $modelName Model to use to generate the file, if necessary.
	 */
	private static function refresh($fileName, $templateName, $modelName) {
		$model = RPBChessboardHelperLoader::loadModel($modelName);
		$text = RPBChessboardHelperLoader::printTemplateOffScreen($templateName, $model);

		$fullFileName = self::getRoot() . $fileName;
		$dirName = dirname($fullFileName);
		if(!file_exists($dirName)) {
			mkdir($dirName, 0777, true);
		}
		file_put_contents($fullFileName, $text);
	}


	/**
	 * Remove the given file from the cache.
	 *
	 * @param string $fileName File-name, relative to the cache root.
	 */
	public static function remove($fileName) {
		$fullFileName = self::getRoot() . $fileName;
		if(file_exists($fullFileName)) {
			unlink($fullFileName);
		}
	}


	/**
	 * Return the root of the cache directory.
	 */
	private static function getRoot() {
		return RPBCHESSBOARD_ABSPATH . 'cache/';
	}
}
