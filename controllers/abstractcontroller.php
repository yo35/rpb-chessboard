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
 * Base class for the controllers.
 */
abstract class RPBChessboardAbstractController
{
	private $model;
	private $view;


	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use.
	 * @param mixed ... Arguments to pass to the model (optional).
	 */
	protected function __construct($modelName)
	{
		$args = func_get_args();
		$this->model = call_user_func_array(array('RPBChessboardHelperLoader', 'loadModel'), $args);
	}


	/**
	 * Load (if necessary) and return the model.
	 */
	public function getModel()
	{
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
