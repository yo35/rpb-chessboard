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
 * Helper functions for dynamic class loading.
 */
abstract class RPBChessboardHelperLoader
{
	/**
	 * Load the model corresponding to the given model name.
	 *
	 * @param string $modelName Name of the model.
	 * @param mixed ... Arguments to pass to the model (optional).
	 * @return object New instance of the model.
	 */
	public static function loadModel($modelName)
	{
		$fileName  = strtolower($modelName);
		$className = 'RPBChessboardModel' . $modelName;
		require_once(RPBCHESSBOARD_ABSPATH . 'models/' . $fileName . '.php');
		if(func_num_args() === 1) {
			return new $className;
		}
		else {
			$args  = func_get_args();
			$clazz = new ReflectionClass($className);
			return $clazz->newInstanceArgs(array_slice($args, 1));
		}
	}


	/**
	 * Load the trait corresponding to the given trait name.
	 *
	 * @param string $traitName Name of the trait.
	 * @param mixed ... Arguments to pass to the trait (optional).
	 * @return object New instance of the trait.
	 */
	public static function loadTrait($traitName)
	{
		$fileName  = strtolower($traitName);
		$className = 'RPBChessboardTrait' . $traitName;
		require_once(RPBCHESSBOARD_ABSPATH . 'models/traits/' . $fileName . '.php');
		if(func_num_args() === 1) {
			return new $className;
		}
		else {
			$args  = func_get_args();
			$clazz = new ReflectionClass($className);
			return $clazz->newInstanceArgs(array_slice($args, 1));
		}
	}


	/**
	 * Print the given template to the current output.
	 *
	 * @param string $templateName
	 * @param object $model
	 */
	public static function printTemplate($templateName, $model) {
		$filename = strtolower($templateName);
		include(RPBCHESSBOARD_ABSPATH . 'templates/' . $filename . '.php');
	}


	/**
	 * Print the given template to a string.
	 *
	 * @param string $templateName
	 * @param object $model
	 * @return string
	 */
	public static function printTemplateOffScreen($templateName, $model) {
		ob_start();
		self::printTemplate($templateName, $model);
		return ob_get_clean();
	}
}
