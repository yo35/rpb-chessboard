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


require_once(RPBCHESSBOARD_ABSPATH . 'controllers/abstractcontroller.php');


/**
 * Show the requested plugin administration page.
 */
class RPBChessboardControllerAdminPage extends RPBChessboardAbstractController
{
	/**
	 * Constructor
	 *
	 * @param string $adminPageName Name of the administration page.
	 */
	public function __construct($adminPageName)
	{
		parent::__construct('AdminPage' . $adminPageName);
	}


	/**
	 * Entry-point of the controller.
	 */
	public function run()
	{
		// Process the post-action, if any.
		switch($this->getModel()->getPostAction()) {
			case 'update-options'            : $this->executeAction('PostOptions' , 'updateOptions'     ); break;
			case 'reset-optionsgeneral'      : $this->executeAction('ResetOptions', 'resetGeneral'      ); break;
			case 'reset-optionscompatibility': $this->executeAction('ResetOptions', 'resetCompatibility'); break;
			case 'reset-optionssmallscreens' : $this->executeAction('ResetOptions', 'resetSmallScreens' ); break;
			default: break;
		}

		// Create and display the view.
		$this->getView()->display();
	}


	/**
	 * Load the trait `$traitName`, and execute the method `$methodName` supposedly defined by the trait.
	 *
	 * @param string $traitName
	 * @param string $methodName
	 * @param string $capability Required capability to execute the action. Default is `'manage_options'`.
	 */
	private function executeAction($traitName, $methodName, $capability='manage_options')
	{
		if(!current_user_can($capability)) {
			return;
		}
		$model = $this->getModel();
		$model->loadTrait($traitName);
		$model->setPostMessage($model->$methodName());
	}
}
