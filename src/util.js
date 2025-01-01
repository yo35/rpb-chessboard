/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2025  Yoann Le Montagner <yo35 -at- melix.net>       *
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


import './public-path';


/**
 * Replace the placeholders `{0}`, `{1}`, etc... in the given pattern by the given arguments.
 */
export function format(pattern, ...args) {
    return pattern.replace(/{(\d+)}/g, (match, index) => {
        index = Number(index);
        return index < args.length ? args[index] : match;
    });
}


/**
 * If the given string respresents a valid set of (custom) piece symbols, parse it and return an object containing the piece symbol corresponding
 * to each piece K, Q, R, B, N, P.
 */
export function parsePieceSymbols(code) {
    const m = /^([A-Za-z]*),([A-Za-z]*),([A-Za-z]*),([A-Za-z]*),([A-Za-z]*),([A-Za-z]*)$/.exec(code);
    return m ? { K: m[1], Q: m[2], R: m[3], B: m[4], N: m[5], P: m[6] } : false;
}


/**
 * Flatten the given object containing custom piece symbols into a single string. Non-valid piece symbol characters are removed.
 */
export function flattenPieceSymbols(pieceSymbols) {
    return [ ...'KQRBNP' ].map(p => pieceSymbols[p].replace(/[^A-Za-z]/g, '')).join(',');
}
