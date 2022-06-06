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


require_once RPBCHESSBOARD_ABSPATH . 'php/models/postaction/abstractreset.php';

/**
 * Base class for the models in charge of processing reset forms.
 */
class RPBChessboardModelPostActionResetCompatibility extends RPBChessboardAbstractModelPostActionReset {

	public function run() {
		delete_option( 'rpbchessboard_fenCompatibilityMode' );
		delete_option( 'rpbchessboard_pgnCompatibilityMode' );
		delete_option( 'rpbchessboard_lazyLoadingForCSSAndJS' );
		return self::getSuccessMessage();
	}

}
