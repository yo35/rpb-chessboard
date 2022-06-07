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


class RPBChessboardModelPostActionResetChessGameSettings extends RPBChessboardAbstractModelPostActionReset {

	public function run() {
		delete_option( 'rpbchessboard_pieceSymbols' );
		delete_option( 'rpbchessboard_navigationBoard' );

		// FIXME Deprecated parameters (since 7.2)
		delete_option( 'rpbchessboard_squareSize' );
		delete_option( 'rpbchessboard_showCoordinates' );
		delete_option( 'rpbchessboard_colorset' );
		delete_option( 'rpbchessboard_pieceset' );

		delete_option( 'rpbchessboard_nboSquareSize' );
		delete_option( 'rpbchessboard_nboShowCoordinates' );
		delete_option( 'rpbchessboard_nboColorset' );
		delete_option( 'rpbchessboard_nboPieceset' );
		delete_option( 'rpbchessboard_idoSquareSize' );
		delete_option( 'rpbchessboard_idoShowCoordinates' );
		delete_option( 'rpbchessboard_idoColorset' );
		delete_option( 'rpbchessboard_idoPieceset' );

		delete_option( 'rpbchessboard_showFlipButton' );
		delete_option( 'rpbchessboard_showDownloadButton' );

		return self::getSuccessMessage();
	}

}
