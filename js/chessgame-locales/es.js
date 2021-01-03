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

	i18n.PIECE_SYMBOLS = { 'K':'R', 'Q':'D', 'R':'T', 'B':'A', 'N':'C', 'P':'P' };

	i18n.ANNOTATED_BY               = 'Anotaciones por %1$s';
	i18n.INITIAL_POSITION           = 'Posición inicial';
	i18n.GO_FIRST_MOVE_TOOLTIP      = 'Ir al inicio de la partida';
	i18n.GO_PREVIOUS_MOVE_TOOLTIP   = 'Ir al movimiento anterior';
	i18n.GO_NEXT_MOVE_TOOLTIP       = 'Ir al próximo movimiento';
	i18n.GO_LAST_MOVE_TOOLTIP       = 'Ir al final de la partida';
	i18n.FLIP_TOOLTIP               = 'Girar el tablero';
	i18n.DOWNLOAD_PGN_TOOLTIP       = 'Descargar partida';
	i18n.PGN_DOWNLOAD_ERROR_MESSAGE = 'Error al descargar el archivo PGN';
	i18n.PGN_PARSING_ERROR_MESSAGE  = 'Error al analizar PGN';

}(/* global jQuery */ jQuery));
