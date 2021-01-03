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

	i18n.PIECE_SYMBOLS = { 'K':'R', 'Q':'D', 'R':'T', 'B':'F', 'N':'C', 'P':'P' };

	i18n.ANNOTATED_BY               = 'Commentée par %1$s';
	i18n.INITIAL_POSITION           = 'Position initiale';
	i18n.GO_FIRST_MOVE_TOOLTIP      = 'Aller au début de la partie';
	i18n.GO_PREVIOUS_MOVE_TOOLTIP   = 'Aller au coup précédent';
	i18n.GO_NEXT_MOVE_TOOLTIP       = 'Aller au coup suivant';
	i18n.GO_LAST_MOVE_TOOLTIP       = 'Aller à la fin de la partie';
	i18n.FLIP_TOOLTIP               = 'Tourner l\'échiquier';
	i18n.DOWNLOAD_PGN_TOOLTIP       = 'Télécharger la partie';
	i18n.PGN_DOWNLOAD_ERROR_MESSAGE = 'Impossible de télécharger le fichier PGN.';
	i18n.PGN_PARSING_ERROR_MESSAGE  = 'Erreur lors du décodage du PGN.';

}(/* global jQuery */ jQuery));
