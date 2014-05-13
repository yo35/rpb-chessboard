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
 * Base class for the controllers.
 */
abstract class RPBChessboardAbstractController
{
	private $modelName;
	private $modelArgs;
	private $model;
	private $view;


	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use.
	 * @param array $modelArgs Arguments to pass to the constructor of the model
	 *        (similar to what is passed to the `call_user_func_array()` function).
	 */
	protected function __construct($modelName, $modelArgs=array())
	{
		$this->modelName = $modelName;
		$this->modelArgs = $modelArgs;
	}


	/**
	 * Load (if necessary) and return the model.
	 */
	public function getModel()
	{
		if(!isset($this->model)) {
			$this->model = RPBChessboardHelperLoader::loadModel($this->modelName, $this->modelArgs);
		}
		return $this->model;
	}


	/**
	 * Load (if necessary) and return the view.
	 */
	public function getView()
	{
		if(!isset($this->view)) {
			$this->view = RPBChessboardHelperLoader::loadView($this->getModel());
		}
		return $this->view;
	}


	/**
	 * Entry-point of the controller.
	 */
	public abstract function run();
}
