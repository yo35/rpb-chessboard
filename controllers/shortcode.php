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
 * Controller for the frontend.
 */
class RPBChessboardControllerShortcode extends RPBChessboardAbstractController
{
	private $atts   ;
	private $content;


	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use. It is supposed to refer
	 *        to a model that inherits from the class RPBChessboardAbstractShortcodeModel.
	 * @param array $atts Attributes passed with the short-code.
	 * @param string $content Enclosed short-code content.
	 */
	public function __construct($modelName, $atts, $content)
	{
		parent::__construct($modelName);
		$this->atts    = $atts   ;
		$this->content = $content;
	}


	/**
	 * Instantiate a model object.
	 */
	protected function loadModel($className)
	{
		return new $className($this->atts, $this->content);
	}


	/**
	 * Entry-point of the controller.
	 */
	public function run()
	{
		// Load the model
		$model = $this->getModel();

		// Create the view
		$viewName  = $model->getViewName();
		$fileName  = strtolower($viewName);
		$className = 'RPBChessboardView' . $viewName;
		require_once(RPBCHESSBOARD_ABSPATH.'views/'.$fileName.'.php');
		$view = new $className($model);

		// Display the view
		ob_start();
		$view->display();
		return ob_get_clean();
	}
}
