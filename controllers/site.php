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


require_once(RPBCHESSBOARD_ABSPATH.'controllers/abstractcontroller.php');


/**
 * Controller for the frontend.
 */
class RPBChessboardControllerSite extends RPBChessboardAbstractController
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct('Site');
	}


	/**
	 * Entry-point of the controller.
	 */
	public function run()
	{
		// Load the model
		$model = $this->getModel();

		// Register the shortcodes
		add_shortcode($model->getFENShortcode(), array('RPBChessboardControllerSite', 'runShortcodeFen'       ));
		add_shortcode('pgndiagram'             , array('RPBChessboardControllerSite', 'runShortcodePgnDiagram'));
		add_shortcode($model->getPGNShortcode(), array('RPBChessboardControllerSite', 'runShortcodePgn'       ));
	}


	/**
	 * Callback method for the [fen][/fen] shortcode.
	 */
	public static function runShortcodeFen($atts, $content)
	{
		return self::runShortcode('Fen', $atts, $content);
	}


	/**
	 * Callback method for the [pgndiagram] shortcode.
	 */
	public static function runShortcodePgnDiagram($atts)
	{
		return self::runShortcode('PgnDiagram', $atts, '');
	}


	/**
	 * Callback method for the [pgn][/pgn] shortcode.
	 */
	public static function runShortcodePgn($atts, $content)
	{
		return self::runShortcode('Pgn', $atts, $content);
	}


	/**
	 * Generic callback method for the shortcodes.
	 */
	private static function runShortcode($modelName, $atts, $content)
	{
		// Load the model
		$fileName  = strtolower($modelName);
		$className = 'RPBChessboardModel' . $modelName;
		require_once(RPBCHESSBOARD_ABSPATH.'models/'.$fileName.'.php');
		$model = new $className($atts, $content);

		// Load the view
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
