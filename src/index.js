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


import './public-path';
import './index.css';

import React from 'react';
import ReactDOM from 'react-dom';
import { Chessboard, colorsets, piecesets, adaptSquareSize } from 'kokopu-react';

window.kokopu = require('kokopu');
window.sanitizeHtml = require('sanitize-html');

// Theming
Object.assign(colorsets, RPBChessboard.customColorsets);
Object.assign(piecesets, RPBChessboard.customPiecesets);

// Special colorsets and piecesets for theming edition
RPBChessboard.editColorset = colorsets['_edit_'] = {};
RPBChessboard.editPieceset = piecesets['_edit_'] = {};

// Re-export some methods
RPBChessboard.adaptSquareSize = adaptSquareSize;

// Chessboard rendering function
RPBChessboard.renderFEN = function(targetJQueryElement, widgetArgs, wrapInDiv) {
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
	/>;
	if (wrapInDiv) {
		widget = <div className="rpbchessboard-diagramAlignment-center">{widget}</div>
	}
	ReactDOM.render(widget, targetJQueryElement.get(0));
};
