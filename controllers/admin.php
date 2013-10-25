<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013  Yoann Le Montagner <yo35 -at- melix.net>            *
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


require_once(RPBCHESSBOARD_ABSPATH.'controllers/abstractcontroller.php');


/**
 * Controller for the backend.
 */
class RPBChessboardControllerAdmin extends RPBChessboardAbstractController
{
	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use. It is supposed to refer
	 *        to a model that inherits from the class RPBChessboardAbstractAdminModel.
	 */
	public function __construct($modelName)
	{
		parent::__construct($modelName);
	}


	/**
	 * Entry-point of the controller.
	 */
	public function run()
	{
		// Load the model
		$model = $this->getModel();

		// Process the post-action, if any.
		$action = $model->getPostAction();
		if(!is_null($action)) {
			$model->loadTrait('Action' . $action);
			$model->processRequest();
		}

		// Create the view
		require_once(RPBCHESSBOARD_ABSPATH.'views/admin.php');
		$view = new RPBChessboardViewAdmin($model);

		// Display the view
		$view->display();
	}
}
