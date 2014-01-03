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
 * Base class for the controllers.
 */
abstract class RPBChessboardAbstractController
{
	private $modelName;
	private $model = null;


	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use.
	 */
	protected function __construct($modelName)
	{
		$this->modelName = $modelName;
	}


	/**
	 * Load (if necessary) and return the model.
	 */
	public function getModel()
	{
		if(is_null($this->model)) {
			$modelName = $this->modelName;
			$fileName  = strtolower($modelName);
			$className = 'RPBChessboardModel' . $modelName;
			require_once(RPBCHESSBOARD_ABSPATH.'models/'.$fileName.'.php');
			$this->model = new $className();
		}
		return $this->model;
	}


	/**
	 * Entry-point of the controller.
	 */
	public abstract function run();
}
