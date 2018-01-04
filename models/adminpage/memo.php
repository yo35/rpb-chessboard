<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/adminpage.php';


/**
 * Model associated to the 'Memo' page in the backend.
 */
class RPBChessboardModelAdminPageMemo extends RPBChessboardAbstractModelAdminPage {

	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel( 'Common/Compatibility' );
		$this->loadDelegateModel( 'Common/DefaultOptions' );
	}


	public function getPGNExampleURL() {
		return RPBCHESSBOARD_URL . 'examples.pgn';
	}
}
