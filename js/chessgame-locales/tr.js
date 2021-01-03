/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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


(function($) {

	'use strict';

	var i18n = $.chessgame.i18n;

	i18n.PIECE_SYMBOLS = { 'K':'K', 'Q':'Q', 'R':'R', 'B':'B', 'N':'N', 'P':'P' };

	i18n.ANNOTATED_BY               = 'Analiz : %1$s';
	i18n.INITIAL_POSITION           = 'Başlangıç konumu';
	//i18n.GO_FIRST_MOVE_TOOLTIP      = 'Go to the beginning of the game';
	//i18n.GO_PREVIOUS_MOVE_TOOLTIP   = 'Go to the previous move';
	//i18n.GO_NEXT_MOVE_TOOLTIP       = 'Go to the next move';
	//i18n.GO_LAST_MOVE_TOOLTIP       = 'Go to the end of the game';
	i18n.FLIP_TOOLTIP               = 'tahtayı çevir';
	//i18n.DOWNLOAD_PGN_TOOLTIP       = 'Download the game';
	//i18n.PGN_DOWNLOAD_ERROR_MESSAGE = 'Cannot download the PGN file.';
	//i18n.PGN_PARSING_ERROR_MESSAGE  = 'Error while analysing a PGN string.';

}(/* global jQuery */ jQuery));
