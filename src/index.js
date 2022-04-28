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


import './public-path';
import './index.css';

import React from 'react';
import ReactDOM from 'react-dom';
import { Chessboard } from 'kokopu-react';

import './editor';
import Chessgame from './chessgame';

window.kokopu = require('kokopu');
window.sanitizeHtml = require('sanitize-html');

// Theming
Object.assign(Chessboard.colorsets(), RPBChessboard.customColorsets);
Object.assign(Chessboard.piecesets(), RPBChessboard.customPiecesets);

// Special colorsets and piecesets for theming edition
RPBChessboard.editColorset = Chessboard.colorsets()['_edit_'] = {};
RPBChessboard.editPieceset = Chessboard.piecesets()['_edit_'] = {};

// Re-export some methods
RPBChessboard.adaptSquareSize = Chessboard.adaptSquareSize;


function getSmallScreenLimits(withSmallScreenLimits) {
	let result = [];
	if (withSmallScreenLimits) {
		RPBChessboard.smallScreenModes.forEach(mode => result.push({
			width: mode.maxScreenWidth,
			squareSize: mode.squareSize,
			coordinateVisible: !mode.hideCoordinates,
		}));
	}
	return result;
}


// Chessboard rendering function
RPBChessboard.renderFEN = function(targetJQueryElement, widgetArgs, wrapInDiv, withSmallScreenLimits) {
	let widget = <Chessboard
		position={widgetArgs.position}
		move={widgetArgs.move}
		squareMarkers={widgetArgs.csl}
		textMarkers={widgetArgs.ctl}
		arrowMarkers={widgetArgs.cal}
		flipped={widgetArgs.flip}
		squareSize={widgetArgs.squareSize}
		coordinateVisible={widgetArgs.showCoordinates}
		colorset={widgetArgs.colorset}
		pieceset={widgetArgs.pieceset}
		animated={widgetArgs.animated}
		moveArrowVisible={widgetArgs.showMoveArrow}
		smallScreenLimits={getSmallScreenLimits(withSmallScreenLimits)}
	/>;
	if (wrapInDiv) {
		widget = <div className="rpbchessboard-diagramAlignment-center">{widget}</div>
	}
	ReactDOM.render(widget, targetJQueryElement.get(0));
};


// Chessgame rendering function
RPBChessboard.renderPGN = function(targetJQueryElement, widgetArgs) {
	let diagramOptions = {
		flipped: widgetArgs.diagramOptions.flip,
		squareSize: widgetArgs.diagramOptions.squareSize,
		coordinateVisible: widgetArgs.diagramOptions.showCoordinates,
		colorset: widgetArgs.diagramOptions.colorset,
		pieceset: widgetArgs.diagramOptions.pieceset,
		smallScreenLimits: getSmallScreenLimits(true),
	};
	let widget = <Chessgame
		game={widgetArgs.pgn}
		gameIndex={widgetArgs.gameIndex}
		pieceSymbols={widgetArgs.pieceSymbols}
		diagramOptions={diagramOptions}
		navigationBoardOptions={diagramOptions}
		animated={widgetArgs.diagramOptions.animated}
		moveArrowVisible={widgetArgs.diagramOptions.showMoveArrow}
		navigationBoard={widgetArgs.navigationBoard}
	/>; // TODO missing props
	ReactDOM.render(widget, targetJQueryElement.get(0));
};
