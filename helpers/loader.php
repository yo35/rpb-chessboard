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


/**
 * Helper functions for dynamic class loading.
 */
abstract class RPBChessboardHelperLoader
{
	/**
	 * Load the model corresponding to the given model name.
	 */
	public static function loadModel($modelName, $arg1=null, $arg2=null, $arg3=null)
	{
		$fileName  = strtolower($modelName);
		$className = 'RPBChessboardModel' . $modelName;
		require_once(RPBCHESSBOARD_ABSPATH . 'models/' . $fileName . '.php');
		return new $className($arg1, $arg2, $arg3);
	}


	/**
	 * Load the model corresponding to the given trait name.
	 */
	public static function loadTrait($traitName, $arg1=null, $arg2=null, $arg3=null)
	{
		$fileName  = strtolower($traitName);
		$className = 'RPBChessboardTrait' . $traitName;
		require_once(RPBCHESSBOARD_ABSPATH . 'models/traits/' . $fileName . '.php');
		return new $className($arg1, $arg2, $arg3);
	}


	/**
	 * Load the view whose name is returned by `$model->getViewName()`.
	 */
	public static function loadView($model)
	{
		$viewName  = $model->getViewName();
		$fileName  = strtolower($viewName);
		$className = 'RPBChessboardView' . $viewName;
		require_once(RPBCHESSBOARD_ABSPATH . 'views/' . $fileName . '.php');
		return new $className($model);
	}
}
