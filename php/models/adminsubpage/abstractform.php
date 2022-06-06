<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/adminsubpage/abstract.php';


/**
 * Base class for the delegate models that manage an admin sub-page with a settings form.
 */
abstract class RPBChessboardAbstractModelAdminSubPageForm extends RPBChessboardAbstractModelAdminSubPage {

	/**
	 * Name of the action performed when the user clicks on "Submit".
	 */
	abstract public function getFormSubmitAction();

	/**
	 * Name of the action performed when the user clicks on "Reset".
	 */
	abstract public function getFormResetAction();

	/**
	 * Name of the template to use to generate the form content.
	 */
	abstract public function getFormTemplateName();


	final public function getTemplateName() {
		return 'generic/form';
	}

}
