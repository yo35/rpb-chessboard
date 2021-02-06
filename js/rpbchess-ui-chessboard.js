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


/**
 * jQuery widget to create chess diagrams.
 *
 * @requires kokopu
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires jQuery UI Selectable
 * @requires jQuery UI Draggable (optional, only if the moveable piece feature is enabled)
 * @requires jQuery UI Droppable (optional, only if the moveable piece feature is enabled)
 */
(function(kokopu, $)
{
	'use strict';


	/**
	 * Minimal value for the square-size parameter.
	 *
	 * @constant
	 * @public
	 */
	var MINIMUM_SQUARE_SIZE = 12;


	/**
	 * Maximal value for the square-size parameter.
	 *
	 * @constant
	 * @public
	 */
	var MAXIMUM_SQUARE_SIZE = 64;


	/**
	 * Regular expression matching a square marker.
	 *
	 * @constant
	 */
	var SQUARE_MARKER_TOKEN = /^\s*([GRY])([a-h][1-8])\s*$/;


	/**
	 * Regular expression matching an arrow marker.
	 *
	 * @constant
	 */
	var ARROW_MARKER_TOKEN = /^\s*([GRY])([a-h][1-8][a-h][1-8])\s*$/;


	/**
	 * Regular expression matching an text marker.
	 *
	 * @constant
	 */
	var TEXT_MARKER_TOKEN = /^\s*([GRY][A-Za-z0-9])([a-h][1-8])\s*$/;


	/**
	 * Regular expression matching a square marker, regardless of its color.
	 *
	 * @constant
	 */
	var SQUARE_MARKER_TOKEN_NO_COLOR = /^\s*[GRY]?([a-h][1-8])\s*$/;


	/**
	 * Regular expression matching an arrow marker, regardless of its color.
	 *
	 * @constant
	 */
	var ARROW_MARKER_TOKEN_NO_COLOR = /^\s*[GRY]?([a-h][1-8][a-h][1-8])\s*$/;


	/**
	 * Regular expression matching an text marker, regardless of its color and glyph.
	 *
	 * @constant
	 */
	var TEXT_MARKER_TOKEN_NO_DESCRIPTOR = /^\s*(?:[GRY][A-Za-z0-9])?([a-h][1-8])\s*$/;


	/**
	 * Parse a string specifying a list of annotation markers.
	 *
	 * @param {string} value
	 * @param {RegExp} re Regular expression to validate an annotation token in the list.
	 * @return {object}
	 */
	function parseMarkerList(value, re) {
		var res = {};
		var tokens = value.split(',');
		for(var k=0; k<tokens.length; ++k) {
			if(re.test(tokens[k])) {
				res[RegExp.$2] = RegExp.$1;
			}
		}
		return res;
	}


	/**
	 * Flatten a list of annotation markers into a single string.
	 *
	 * @param {object} markers
	 * @return {string}
	 */
	function flattenMarkerList(markers) {
		var res = [];
		for(var item in markers) {
			if(item in markers) {
				res.push(markers[item] + item);
			}
		}
		return res.join(',');
	}


	/**
	 * Ensure that the given value is a valid boolean.
	 *
	 * @param {mixed} value
	 * @param {boolean} defaultValue
	 * @returns {boolean}
	 */
	function filterBoolean(value, defaultValue) {
		if(typeof value === 'boolean') {
			return value;
		}
		else if(typeof value === 'number') {
			return Boolean(value);
		}
		else if(typeof value === 'string') {
			value = value.toLowerCase();
			if(value === 'true' || value === '1' || value === 'on') {
				return true;
			}
			else if(value === 'false' || value === '0' || value === 'off') {
				return false;
			}
			else {
				return defaultValue;
			}
		}
		else {
			return defaultValue;
		}
	}


	/**
	 * Ensure that the given number is a valid square size.
	 *
	 * @param {number} squareSize
	 * @returns {number}
	 */
	function filterOptionSquareSize(squareSize) {
		return Math.min(Math.max(squareSize, MINIMUM_SQUARE_SIZE), MAXIMUM_SQUARE_SIZE);
	}


	/**
	 * Ensure that the given argument is a valid piece-set or color-set code.
	 */
	function filterSetCode(code) {
		if(typeof code === 'string') {
			code = code.toLowerCase();
			if(/^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(code)) {
				return code;
			}
		}
		return '';
	}


	/**
	 * Ensure that the given string is a valid value for the `interactionMode` option.
	 *
	 * @param {string} interactionMode
	 * @returns {string}
	 */
	function filterOptionInteractionMode(interactionMode) {
		return (interactionMode==='play' || interactionMode==='movePieces' || /^addPieces-[wb][kqrbnp]$/.test(interactionMode) ||
			/^add(?:Square|Arrow)Markers-[GRY]$/.test(interactionMode) || /^addTextMarkers-[GRY][A-Za-z0-9]$/.test(interactionMode)) ? interactionMode : 'none';
	}


	/**
	 * Ensure that the given number is a valid animation spedd.
	 *
	 * @param {number} animationSpeed
	 * @returns {number}
	 */
	function filterOptionAnimationSpeed(animationSpeed) {
		return Math.max(0, animationSpeed);
	}


	/**
	 * Initialize the internal `kokopu.Position` object with the given FEN string.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} fen
	 * @returns {string}
	 */
	function initializePosition(widget, fen) {

		// Trim the input.
		fen = fen.replace(/^\s+|\s+$/g, '');

		// Parse the FEN string.
		try {
			widget._position = /^([-\w]+):(.*)$/.test(fen) ? new kokopu.Position(RegExp.$1, RegExp.$2) : new kokopu.Position(fen);
			fen = widget._position.fen();
		}
		catch(e) {
			if(e instanceof kokopu.exception.InvalidFEN) {
				widget._position = e;
			}
			else {
				widget._position = null;
				throw e;
			}
		}

		// Return the validated FEN string.
		return fen;
	}


	/**
	 * Initialize the internal square marker buffer with the given string.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} value
	 * @returns {string}
	 */
	function initializeSquareMarkers(widget, value) {
		widget._squareMarkers = parseMarkerList(value, SQUARE_MARKER_TOKEN);
		return flattenMarkerList(widget._squareMarkers);
	}


	/**
	 * Initialize the internal arrow marker buffer with the given string.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} value
	 * @returns {string}
	 */
	function initializeArrowMarkers(widget, value) {
		widget._arrowMarkers = parseMarkerList(value, ARROW_MARKER_TOKEN);
		return flattenMarkerList(widget._arrowMarkers);
	}


	/**
	 * Initialize the internal text marker buffer with the given string.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} value
	 * @returns {string}
	 */
	function initializeTextMarkers(widget, value) {
		widget._textMarkers = parseMarkerList(value, TEXT_MARKER_TOKEN);
		return flattenMarkerList(widget._textMarkers);
	}



	// ---------------------------------------------------------------------------
	// Widget rendering
	// ---------------------------------------------------------------------------

	/**
	 * Destroy the widget content, prior to a refresh or a widget destruction.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function destroyContent(widget) {
		widget.element.empty();
	}


	/**
	 * Build the error message resulting from a FEN parsing error.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @returns {Element}
	 */
	function buildErrorMessage(widget) {

		// Build the error report box.
		var result = document.createElement('div');
		result.className = 'rpbui-chessboard-error';

		// Title
		var title = document.createElement('div');
		title.className = 'rpbui-chessboard-errorTitle';
		title.appendChild(document.createTextNode('Error while analysing a FEN string.'));
		result.appendChild(title);

		// Optional message.
		if(widget._position.message !== null) {
			var message = document.createElement('div');
			message.className = 'rpbui-chessboard-errorMessage';
			message.appendChild(document.createTextNode(widget._position.message));
			result.appendChild(message);
		}

		return result;
	}


	/**
	 * Build the widget content.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @returns {Element}
	 */
	function buildContent(widget) {
		var ROWS    = widget.options.flip ? '12345678' : '87654321';
		var COLUMNS = widget.options.flip ? 'hgfedcba' : 'abcdefgh';

		// Open the "table" node.
		var result = document.createElement('div');
		var globalClazz = 'rpbui-chessboard-table rpbui-chessboard-size' + widget.options.squareSize;
		if(!widget.options.showCoordinates) {
			globalClazz += ' rpbui-chessboard-hideCoordinates';
		}
		if(widget.options.colorset !== '') {
			globalClazz += ' rpbui-chessboard-colorset-' + widget.options.colorset;
		}
		if(widget.options.pieceset !== '') {
			globalClazz += ' rpbui-chessboard-pieceset-' + widget.options.pieceset;
		}
		result.className = globalClazz;

		// Background annotation layer
		var svgBack = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
		svgBack.setAttribute('class', 'rpbui-chessboard-annotations rpbui-chessboard-annotations-bg');
		svgBack.setAttribute('viewBox', '0 0 8 8');
		result.appendChild(svgBack);

		// For each row...
		for(var r = 0; r < 8; ++r) {

			// The row container...
			var row = document.createElement('div');
			row.className = 'rpbui-chessboard-row';
			result.appendChild(row);

			// Begin row + row coordinate cell.
			var rowHeader = document.createElement('div');
			rowHeader.className = 'rpbui-chessboard-cell rpbui-chessboard-rowCoordinate';
			rowHeader.appendChild(document.createTextNode(ROWS[r]));
			row.appendChild(rowHeader);

			// Chessboard squares
			for(var c = 0; c < 8; ++c) {

				var sq = COLUMNS[c] + ROWS[r];

				// Square
				var square = document.createElement('div');
				var squareColor = kokopu.squareColor(sq) === 'w' ? 'light' : 'dark';
				square.className = 'rpbui-chessboard-sized rpbui-chessboard-cell rpbui-chessboard-square rpbui-chessboard-' + squareColor + 'Square';
				row.appendChild(square);

				// Colored piece within the square (if any).
				var cp = widget._position.square(sq);
				square.appendChild(cp === '-' ? buildHandle() : buildColoredPiece(cp));
			}

			// Additional cell for the turn flag.
			var flagCell = document.createElement('div');
			flagCell.className = 'rpbui-chessboard-cell';
			if(ROWS[r] === '8' || ROWS[r] === '1') {
				var flag = document.createElement('div');
				var flagColor = ROWS[r] === '8' ? 'b' : 'w';
				var clazz = 'rpbui-chessboard-sized rpbui-chessboard-turnFlag rpbui-chessboard-color-' + flagColor;
				if(flagColor !== widget._position.turn()) {
					clazz += ' rpbui-chessboard-inactiveFlag';
				}
				flag.className = clazz;
				flagCell.appendChild(flag);
			}
			row.appendChild(flagCell);
		}

		// Column coordinates
		var columnHeaderRow = document.createElement('div');
		columnHeaderRow.className = 'rpbui-chessboard-row rpbui-chessboard-columnCoordinateRow';
		var firstColumnHeader = document.createElement('div');
		firstColumnHeader.className = 'rpbui-chessboard-cell rpbui-chessboard-rowCoordinate';
		columnHeaderRow.appendChild(firstColumnHeader);
		for(var c=0; c<8; ++c) {
			var columnHeader = document.createElement('div');
			columnHeader.className = 'rpbui-chessboard-cell rpbui-chessboard-columnCoordinate';
			columnHeader.appendChild(document.createTextNode(COLUMNS[c]));
			columnHeaderRow.appendChild(columnHeader);
		}
		var lastColumnHeader = document.createElement('div');
		lastColumnHeader.className = 'rpbui-chessboard-cell';
		columnHeaderRow.appendChild(lastColumnHeader);
		result.appendChild(columnHeaderRow);

		// Frontground annotation layer
		var svgFront = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
		svgFront.setAttribute('class', 'rpbui-chessboard-annotations rpbui-chessboard-annotations-fg');
		svgFront.setAttribute('viewBox', '0 0 8 8');
		var defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
		defs.appendChild(buildArrowMarkerStyle(widget, 'G'));
		defs.appendChild(buildArrowMarkerStyle(widget, 'R'));
		defs.appendChild(buildArrowMarkerStyle(widget, 'Y'));
		defs.appendChild(buildArrowMarkerStyle(widget, 'B'));
		svgFront.appendChild(defs);
		result.appendChild(svgFront);

		// Square markers
		for(var square in widget._squareMarkers) {
			if(square in widget._squareMarkers) {
				var sc = getSquareCoordinatesInSVG(widget, square);
				var identifierClazz = 'rpbui-chessboard-squareMarker-' + square;
				svgBack.appendChild(buildSquareMarker(identifierClazz, widget._squareMarkers[square], sc.x, sc.y));
			}
		}

		// Text markers
		for(var square in widget._textMarkers) {
			if(square in widget._textMarkers) {
				var sc = getSquareCoordinatesInSVG(widget, square);
				var identifierClazz = 'rpbui-chessboard-textMarker-' + square;
				svgFront.appendChild(buildTextMarker(identifierClazz, widget._textMarkers[square], sc.x, sc.y));
			}
		}

		// Arrows
		for(var arrow in widget._arrowMarkers) {
			if(arrow in widget._arrowMarkers && /^([a-h][1-8])([a-h][1-8])$/.test(arrow)) {
				var fromSquare = RegExp.$1;
				var toSquare = RegExp.$2;
				if(fromSquare !== toSquare) {
					var vc = getArrowCoordinatesInSVG(widget, fromSquare, toSquare);
					var identifierClazz = 'rpbui-chessboard-arrowMarker-' + fromSquare + toSquare;
					svgFront.appendChild(buildArrow(widget, identifierClazz, widget._arrowMarkers[arrow], vc.x1, vc.y1, vc.x2, vc.y2));
				}
			}
		}
		if(widget._moveArrow !== null) {
			var vc = getArrowCoordinatesInSVG(widget, widget._moveArrow.from, widget._moveArrow.to);
			svgFront.appendChild(buildArrow(widget, 'rpbui-chessboard-moveArrow', 'B', vc.x1, vc.y1, vc.x2, vc.y2));
		}

		return result;
	}


	/**
	 * Create a DOM node corresponding to a style for an arrow termination.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} color
	 * @returns {Element}
	 */
	function buildArrowMarkerStyle(widget, color) {

		var marker = document.createElementNS('http://www.w3.org/2000/svg', 'marker');
		marker.setAttribute('id', arrowMarkerEndId(widget, color));
		marker.setAttribute('class', 'rpbui-chessboard-markerFill-' + color);
		marker.setAttribute('markerWidth', 4);
		marker.setAttribute('markerHeight', 4);
		marker.setAttribute('refX', 2.5);
		marker.setAttribute('refY', 2);
		marker.setAttribute('orient', 'auto');

		var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
		path.setAttribute('d', 'M 4,2 L 0,4 L 1,2 L 0,0 Z');
		marker.appendChild(path);

		return marker;
	}


	/**
	 * Create a DOM node corresponding to a square marker.
	 *
	 * @param {string} identifierClazz
	 * @param {string} color
	 * @param {number} x
	 * @param {number} y
	 * @returns {Element}
	 */
	function buildSquareMarker(identifierClazz, color, x, y) {
		var rectangle = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
		rectangle.setAttribute('class', 'rpbui-chessboard-squareMarker ' + identifierClazz + ' rpbui-chessboard-markerFill-' + color);
		rectangle.setAttribute('x', x - 0.5);
		rectangle.setAttribute('y', y - 0.5);
		rectangle.setAttribute('width', 1);
		rectangle.setAttribute('height', 1);
		return rectangle;
	}


	/**
	 * Create a DOM node corresponding to a text marker.
	 *
	 * @param {string} identifierClazz
	 * @param {string} descriptor 2 characters: the first one is the color, the second one the glyph.
	 * @param {number} x
	 * @param {number} y
	 * @returns {Element}
	 */
	function buildTextMarker(identifierClazz, descriptor, x, y) {
		var color = descriptor.substr(0, 1);
		var glyph = descriptor.substr(1, 1);
		var text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
		text.setAttribute('class', 'rpbui-chessboard-textMarker ' + identifierClazz + ' rpbui-chessboard-markerFill-' + color);
		text.setAttribute('x', x);
		text.setAttribute('y', y);
		text.appendChild(document.createTextNode(glyph));
		return text;
	}


	/**
	 * Create a DOM node corresponding to an arrow between the given squares.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} identifierClazz
	 * @param {string} color
	 * @param {number} x1
	 * @param {number} y1
	 * @param {number} x2
	 * @param {number} y2
	 * @returns {Element}
	 */
	function buildArrow(widget, identifierClazz, color, x1, y1, x2, y2) {
		var line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
		line.setAttribute('class', 'rpbui-chessboard-arrowMarker ' + identifierClazz + ' rpbui-chessboard-markerStroke-' + color);
		line.setAttribute('x1', x1);
		line.setAttribute('y1', y1);
		line.setAttribute('x2', x2);
		line.setAttribute('y2', y2);
		line.setAttribute('marker-end', 'url(#' + arrowMarkerEndId(widget, color) + ')');
		return line;
	}


	/**
	 * Return the ID of the arrow marker tip corresponding to the given color.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} color
	 */
	function arrowMarkerEndId(widget, color) {
		if(widget._arrowMarkerEndIdSuffix === null) {
			var buffer = new Uint32Array(8);
			crypto.getRandomValues(buffer);
			widget._arrowMarkerEndIdSuffix = '';
			for(var i = 0; i < buffer.length; ++i) {
				widget._arrowMarkerEndIdSuffix += buffer[i].toString(16);
			}
		}
		return 'rpbui-chessboard-arrowMarkerEnd-' + color + '-' + widget._arrowMarkerEndIdSuffix;
	}


	/**
	 * Build an element corresponding to the given colored piece code.
	 *
	 * @param {string} cp For example: `'wr'` for a white rook.
	 * @returns {Element}
	 */
	function buildColoredPiece(cp) {
		var coloredPiece = document.createElement('div');
		coloredPiece.className = 'rpbui-chessboard-sized rpbui-chessboard-piece rpbui-chessboard-piece-' + cp;
		coloredPiece.appendChild(buildHandle());
		return coloredPiece;
	}


	/**
	 * Build a handle element (each empty square and piece must have a handle).
	 *
	 * @returns {Element}
	 */
	function buildHandle() {
		var handle = document.createElement('div');
		handle.className = 'rpbui-chessboard-handle';
		return handle;
	}


	/**
	 * Refresh the widget.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function refresh(widget) {
		destroyContent(widget);
		if(widget._position === null) {
			return;
		}

		// Handle parsing error problems.
		if(widget._position instanceof kokopu.exception.InvalidFEN) {
			widget.element.append(buildErrorMessage(widget));
		}

		// Regular rendering
		else {
			widget.element.append(buildContent(widget));
			if(widget.options.interactionMode==='play' || widget.options.interactionMode==='movePieces') {
				tagSquares(widget);
				enableMovePieceOrPlayBehavior(widget, widget.options.interactionMode==='play');
			}
			else if(/^addPieces-([wb][kqrbnp])$/.test(widget.options.interactionMode)) {
				var coloredPiece = RegExp.$1;
				tagSquares(widget);
				enableAddPieceBehavior(widget, coloredPiece);
			}
			else if(/^addSquareMarkers-([GRY])$/.test(widget.options.interactionMode)) {
				var markerColor = RegExp.$1;
				tagSquares(widget);
				enableAddSquareMarkerBehavior(widget, markerColor);
			}
			else if(/^addArrowMarkers-([GRY])$/.test(widget.options.interactionMode)) {
				var markerColor = RegExp.$1;
				tagSquares(widget);
				enableAddArrowMarkerBehavior(widget, markerColor);
			}
			else if(/^addTextMarkers-([GRY][A-Za-z0-9])$/.test(widget.options.interactionMode)) {
				var markerDescriptor = RegExp.$1;
				tagSquares(widget);
				enableAddTextMarkerBehavior(widget, markerDescriptor);
			}
		}
	}


	/**
	 * Update the widget when the turn gets modified.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function onTurnChanged(widget) {
		$('.rpbui-chessboard-turnFlag', widget.element).toggleClass('rpbui-chessboard-inactiveFlag');
	}


	/**
	 * Update the widget when the square-size parameter gets modified.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {number} oldValue
	 * @param {number} newValue
	 */
	function onSquareSizeChanged(widget, oldValue, newValue) {
		$('.rpbui-chessboard-table', widget.element).removeClass('rpbui-chessboard-size' + oldValue).addClass('rpbui-chessboard-size' + newValue);
	}


	/**
	 * Update the widget when the show-coordinates parameter gets modified.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function onShowCoordinatesChanged(widget) {
		$('.rpbui-chessboard-table', widget.element).toggleClass('rpbui-chessboard-hideCoordinates');
	}


	/**
	 * Update the widget when a square marker gets added/changed/removed.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} square
	 * @param {string} oldValue `undefined` if the square marker is added.
	 * @param {string} newValue `undefined` if the square marker is removed.
	 */
	function onSquareMarkerChanged(widget, square, oldValue, newValue) {
		var identifierClazz = 'rpbui-chessboard-squareMarker-' + square;
		if(typeof oldValue === 'undefined') {
			var sc = getSquareCoordinatesInSVG(widget, square);
			$('.rpbui-chessboard-annotations-bg', widget.element).append(buildSquareMarker(identifierClazz, newValue, sc.x, sc.y));
		}
		else if(typeof newValue === 'undefined') {
			$('.' + identifierClazz, widget.element).remove();
		}
		else {
			var clazz = 'rpbui-chessboard-squareMarker ' + identifierClazz + ' rpbui-chessboard-markerFill-' + newValue;
			$('.' + identifierClazz, widget.element).attr('class', clazz);
		}
	}


	/**
	 * Update the widget when all the square markers get changed.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function onSquareMarkersChanged(widget) {
		$('.rpbui-chessboard-squareMarker', widget.element).remove();

		for(var square in widget._squareMarkers) {
			if(square in widget._squareMarkers) {
				var sc = getSquareCoordinatesInSVG(widget, square);
				var identifierClazz = 'rpbui-chessboard-squareMarker-' + square;
				$('.rpbui-chessboard-annotations-bg', widget.element).append(buildSquareMarker(identifierClazz, widget._squareMarkers[square], sc.x, sc.y));
			}
		}
	}


	/**
	 * Update the widget when an arrow marker gets added/changed/removed.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} key
	 * @param {string} oldValue `undefined` if the square marker is added.
	 * @param {string} newValue `undefined` if the square marker is removed.
	 */
	function onArrowMarkerChanged(widget, key, oldValue, newValue) {
		var identifierClazz = 'rpbui-chessboard-arrowMarker-' + key;
		if(typeof oldValue === 'undefined') {
			var fromSquare = key.substr(0, 2);
			var toSquare = key.substr(2, 2);
			if(fromSquare !== toSquare) {
				var vc = getArrowCoordinatesInSVG(widget, fromSquare, toSquare);
				$('.rpbui-chessboard-annotations-fg', widget.element).append(buildArrow(widget, identifierClazz, newValue, vc.x1, vc.y1, vc.x2, vc.y2));
			}
		}
		else if(typeof newValue === 'undefined') {
			$('.' + identifierClazz, widget.element).remove();
		}
		else {
			var clazz = 'rpbui-chessboard-arrowMarker ' + identifierClazz + ' rpbui-chessboard-markerStroke-' + newValue;
			var marker = 'url(#' +  arrowMarkerEndId(widget, newValue) + ')';
			$('.' + identifierClazz, widget.element).attr('class', clazz).attr('marker-end', marker);
		}
	}


	/**
	 * Update the widget when all the arrow markers get changed.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function onArrowMarkersChanged(widget) {
		$('.rpbui-chessboard-arrowMarker', widget.element).remove();

		for(var key in widget._arrowMarkers) {
			if(key in widget._arrowMarkers) {
				var fromSquare = key.substr(0, 2);
				var toSquare = key.substr(2, 2);
				if(fromSquare !== toSquare) {
					var vc = getArrowCoordinatesInSVG(widget, fromSquare, toSquare);
					var arrow = buildArrow(widget, 'rpbui-chessboard-arrowMarker-' + key, widget._arrowMarkers[key], vc.x1, vc.y1, vc.x2, vc.y2);
					$('.rpbui-chessboard-annotations-fg', widget.element).append(arrow);
				}
			}
		}
	}


	/**
	 * Update the widget when a text marker gets added/changed/removed.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} square
	 * @param {string} oldValue `undefined` if the text marker is added.
	 * @param {string} newValue `undefined` if the text marker is removed.
	 */
	function onTextMarkerChanged(widget, square, oldValue, newValue) {
		var identifierClazz = 'rpbui-chessboard-textMarker-' + square;
		if(typeof oldValue === 'undefined') {
			var sc = getSquareCoordinatesInSVG(widget, square);
			$('.rpbui-chessboard-annotations-fg', widget.element).append(buildTextMarker(identifierClazz, newValue, sc.x, sc.y));
		}
		else if(typeof newValue === 'undefined') {
			$('.' + identifierClazz, widget.element).remove();
		}
		else {
			var newColor = newValue.substr(0, 1);
			var newGlyph = newValue.substr(1, 1);
			var clazz = 'rpbui-chessboard-textMarker ' + identifierClazz + ' rpbui-chessboard-markerFill-' + newColor;
			$('.' + identifierClazz, widget.element).attr('class', clazz);
			$('.' + identifierClazz, widget.element).text(newGlyph);
		}
	}


	/**
	 * Update the widget when all the text markers get changed.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function onTextMarkersChanged(widget) {
		$('.rpbui-chessboard-textMarker', widget.element).remove();

		for(var square in widget._textMarkers) {
			if(square in widget._textMarkers) {
				var sc = getSquareCoordinatesInSVG(widget, square);
				var identifierClazz = 'rpbui-chessboard-textMarker-' + square;
				$('.rpbui-chessboard-annotations-fg', widget.element).append(buildTextMarker(identifierClazz, widget._textMarkers[square], sc.x, sc.y));
			}
		}
	}


	/**
	 * Fetch the DOM node corresponding to a given square.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} square
	 * @returns {jQuery}
	 */
	function fetchSquare(widget, square) {
		var ROWS    = widget.options.flip ? '12345678' : '87654321';
		var COLUMNS = widget.options.flip ? 'hgfedcba' : 'abcdefgh';
		var r = ROWS   .indexOf(square[1]);
		var c = COLUMNS.indexOf(square[0]);
		return $($('.rpbui-chessboard-square', widget.element).get(r*8 + c));
	}


	/**
	 * Return the coordinates of the given square in the annotation canvas.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} square
	 * @returns {{x:number, y:number}}
	 */
	function getSquareCoordinatesInSVG(widget, square) {
		var ROWS    = widget.options.flip ? '12345678' : '87654321';
		var COLUMNS = widget.options.flip ? 'hgfedcba' : 'abcdefgh';
		return { x: COLUMNS.indexOf(square[0])+0.5, y: ROWS.indexOf(square[1])+0.5 };
	}


	/**
	 * Return the coordinates of the given arrow in the annotation canvas.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} fromSquare
	 * @param {string} toSquare
	 * @returns {{x1:number, y1:number, x2:number, y2:number}}
	 */
	function getArrowCoordinatesInSVG(widget, fromSquare, toSquare) {
		var p1 = getSquareCoordinatesInSVG(widget, fromSquare);
		var p2 = getSquareCoordinatesInSVG(widget, toSquare);
		p2.x += p1.x < p2.x ? -0.3 : p1.x > p2.x ? 0.3 : 0;
		p2.y += p1.y < p2.y ? -0.3 : p1.y > p2.y ? 0.3 : 0;
		return { x1:p1.x, y1:p1.y, x2:p2.x, y2:p2.y };
	}



	// ---------------------------------------------------------------------------
	// Drag & drop interactions
	// ---------------------------------------------------------------------------

	/**
	 * Tag each square of the chessboard with its name (for instance: 'e4').
	 * The name of the square is then available through:
	 *
	 *   $(e).data('square');
	 *
	 * Where `e` is a DOM object with the class `rpbui-chessboard-square`.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function tagSquares(widget) {
		var ROWS    = widget.options.flip ? '12345678' : '87654321';
		var COLUMNS = widget.options.flip ? 'hgfedcba' : 'abcdefgh';
		var r = 0;
		var c = 0;
		$('.rpbui-chessboard-square', widget.element).each(function(index, element) {
			$(element).data('square', COLUMNS[c] + ROWS[r]);
			++c;
			if(c === 8) {
				c = 0;
				++r;
			}
		});
	}


	/**
	 * Enable the "move-pieces" or "play" interaction modes.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {boolean} playMode
	 */
	function enableMovePieceOrPlayBehavior(widget, playMode) {

		// Enable dragging.
		$('.rpbui-chessboard-piece', widget.element).draggable({
			cursor        : 'move',
			cursorAt      : { top: widget.options.squareSize/2, left: widget.options.squareSize/2 },
			revert        : true,
			revertDuration: 0,
			zIndex        : 300
		});

		// Enable dropping.
		var tableNode = $('.rpbui-chessboard-table', widget.element).get(0);
		$('.rpbui-chessboard-square', widget.element).droppable({
			hoverClass: 'rpbui-chessboard-squareHover',

			accept: function(e) {
				return $(e).closest('.rpbui-chessboard-table').get(0) === tableNode;
			},

			drop: function(event, ui) {
				var target      = $(event.target);
				var movingPiece = ui.draggable;
				if(movingPiece.hasClass('rpbui-chessboard-piece')) {
					if(playMode) {
						var moveDescriptor = widget._position.isMoveLegal(movingPiece.parent().data('square'), target.data('square'));
						if(!moveDescriptor) {
							return;
						}
						else {
							switch(moveDescriptor.status) {
								case 'regular':
									moveDescriptor = moveDescriptor();
									break;

								case 'promotion':
									moveDescriptor = moveDescriptor('q'); // TODO handle non-queen promotions in drag & drop.
									break;

								case 'castle960':
									moveDescriptor = moveDescriptor('castle'); // TODO handle ambiguous chess960 moves in drag & drop.
									break;

								default:
									return;
							}
						}
						doPlay(widget, moveDescriptor, false, widget.options.showMoveArrow);
					}
					else {
						var move = { from: movingPiece.parent().data('square'), to: target.data('square') };
						doMovePiece(widget, move, false, widget.options.showMoveArrow);
					}
				}
			}
		});
	}


	/**
	 * Enable the "add-piece" interaction mode.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} coloredPiece
	 */
	function enableAddPieceBehavior(widget, coloredPiece) {
		$('.rpbui-chessboard-square', widget.element).mousedown(function() {
			doAddPiece(widget, coloredPiece, $(this).data('square'), $(this)); // eslint-disable-line no-invalid-this
		});
	}


	/**
	 * Enable the "add-square-marker" interaction mode.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} markerColor
	 */
	function enableAddSquareMarkerBehavior(widget, markerColor) {
		$('.rpbui-chessboard-square', widget.element).mousedown(function() {
			doAddSquareMarker(widget, markerColor, $(this).data('square')); // eslint-disable-line no-invalid-this
		});
	}


	/**
	 * Enable the "add-text-marker" interaction mode.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} markerDescriptor
	 */
	function enableAddTextMarkerBehavior(widget, markerDescriptor) {
		$('.rpbui-chessboard-square', widget.element).mousedown(function() {
			doAddTextMarker(widget, markerDescriptor, $(this).data('square')); // eslint-disable-line no-invalid-this
		});
	}


	/**
	 * Enable the "add-arrow-marker" interaction mode.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} markerColor
	 */
	function enableAddArrowMarkerBehavior(widget, markerColor) {

		// Must be initialized each time a drag starts.
		var fromSquare = null;
		var canvasOffset = null;

		// Conversion page coordinate -> SVG canvas coordinates.
		var canvas = $('.rpbui-chessboard-annotations-fg', widget.element);
		var canvasWidth  = canvas.width();
		var canvasHeight = canvas.height();
		function xInCanvas(x) { return (x - canvasOffset.left) * 8 / canvasWidth; }
		function yInCanvas(y) { return (y - canvasOffset.top) * 8 / canvasHeight; }

		// Enable dragging.
		$('.rpbui-chessboard-square .rpbui-chessboard-handle', widget.element).draggable({
			cursor  : 'crosshair',
			cursorAt: { top: widget.options.squareSize/2, left: widget.options.squareSize/2 },
			helper  : function() { return $('<div class="rpbui-chessboard-sized"></div>'); },

			start: function(event) {

				// Initialized the drag control variables.
				fromSquare = $(event.target).closest('.rpbui-chessboard-square').data('square');
				canvasOffset = canvas.offset();

				// Create the temporary arrow marker.
				var p = getSquareCoordinatesInSVG(widget, fromSquare);
				$('.rpbui-chessboard-annotations-fg', widget.element).append(buildArrow(widget, 'rpbui-chessboard-draggedArrow', markerColor, p.x, p.y, p.x, p.y));
			},

			drag: function(event) {
				$('.rpbui-chessboard-draggedArrow', widget.element).attr({ 'x2':xInCanvas(event.pageX), 'y2':yInCanvas(event.pageY) });
			},

			stop: function() {
				$('.rpbui-chessboard-draggedArrow', widget.element).remove();
			}
		});

		// Enable dropping.
		var tableNode = $('.rpbui-chessboard-table', widget.element).get(0);
		$('.rpbui-chessboard-square', widget.element).droppable({
			hoverClass: 'rpbui-chessboard-squareHover',

			accept: function(e) {
				return $(e).closest('.rpbui-chessboard-table').get(0) === tableNode;
			},

			drop: function(event) {
				var toSquare = $(event.target).data('square');
				doAddArrowMarker(widget, markerColor, fromSquare, toSquare);
			}
		});
	}


	/**
	 * Callback for the "move pieces" mode -> move the moving piece to its destination square,
	 * clearing the latter beforehand if necessary.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {{from: string, to: string}} move The origin and destination squares.
	 * @param {boolean} animate
	 * @param {boolean} withArrow
	 */
	function doMovePiece(widget, move, animate, withArrow) {
		if(move.from === move.to || widget._position.square(move.from) === '-') {
			return;
		}
		widget._position.square(move.to, widget._position.square(move.from));
		widget._position.square(move.from, '-');
		++widget._animationCounter;

		// Update the DOM elements.
		clearMoveArrow(widget);
		doDisplacement(widget, move.from, move.to, animate, withArrow);

		// FEN update + notifications.
		notifyFENChanged(widget);
	}


	/**
	 * Callback for the "play" mode -> check if the proposed move is legal, and handle
	 * the special situations (promotion, castle, en-passant...) that may be encountered.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {kokopu.MoveDescriptor} move
	 * @param {boolean} animate
	 * @param {boolean} withArrow
	 */
	function doPlay(widget, moveDescriptor, animate, withArrow) {
		widget._position.play(moveDescriptor);
		++widget._animationCounter;

		// Move the moving piece to its destination square.
		clearMoveArrow(widget);
		var movingPiece = doDisplacement(widget, moveDescriptor.from(), moveDescriptor.to(), animate, withArrow);

		// Castling move -> move the rook.
		if(moveDescriptor.isCastling()) {
			doDisplacement(widget, moveDescriptor.rookFrom(), moveDescriptor.rookTo(), animate, false);
		}

		// En-passant move -> remove the taken pawn.
		if(moveDescriptor.isEnPassant()) {
			fetchSquare(widget, moveDescriptor.enPassantSquare()).empty().append(buildHandle());
		}

		// Promotion move -> change the type of the promoted piece.
		if(moveDescriptor.isPromotion()) {

			// Switch to the promoted piece when the animation is 80%-complete.
			scheduleMoveAnimation(widget, animate, 0.8, function() {
				var oldClazz = 'rpbui-chessboard-piece-' + moveDescriptor.movingColoredPiece();
				var newClazz = 'rpbui-chessboard-piece-' + moveDescriptor.coloredPromotion();
				movingPiece.removeClass(oldClazz).addClass(newClazz);
			});
		}

		// Switch the turn flag.
		$('.rpbui-chessboard-turnFlag', widget.element).toggleClass('rpbui-chessboard-inactiveFlag');

		// FEN update + notifications.
		notifyFENChanged(widget);
	}


	/**
	 * Remove the move arrow if it exist.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function clearMoveArrow(widget) {
		widget._moveArrow = null;
		$('.rpbui-chessboard-moveArrow', widget.element).remove();
	}


	/**
	 * Execute a move.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} from
	 * @param {string} to
	 * @param {boolean} animate
	 * @param {boolean} withArrow
	 * @returns {jQuery} DOM object corresponding to the moving piece.
	 */
	function doDisplacement(widget, from, to, animate, withArrow) {
		var movingPiece = $('.rpbui-chessboard-piece', fetchSquare(widget, from));
		movingPiece.parent().append(buildHandle());
		fetchSquare(widget, to).empty().append(movingPiece);

		// Create the move arrow
		if(withArrow && from !== to) {
			var vc = getArrowCoordinatesInSVG(widget, from, to);
			scheduleMoveAnimation(widget, animate, 0.5, function() {
				$('.rpbui-chessboard-annotations-fg', widget.element).append(buildArrow(widget, 'rpbui-chessboard-moveArrow', 'B', vc.x1, vc.y1, vc.x2, vc.y2));
			});
			widget._moveArrow = { from: from, to: to };
		}

		// Animation
		if(animate) {
			var p1 = kokopu.squareToCoordinates(from);
			var p2 = kokopu.squareToCoordinates(to  );
			var deltaTop  = (p1.rank - p2.rank) * movingPiece.height() * (widget.options.flip ? 1 : -1);
			var deltaLeft = (p2.file - p1.file) * movingPiece.width() * (widget.options.flip ? 1 : -1);
			movingPiece.css('top', deltaTop + 'px');
			movingPiece.css('left', deltaLeft + 'px');
			movingPiece.animate({ top: '0px', left: '0px' }, widget.options.animationSpeed);
		}

		return movingPiece;
	}


	/**
	 * Schedule an animation related to a move animation.
	 */
	function scheduleMoveAnimation(widget, animate, delayFactor, callback) {
		if(animate) {
			var currentAnimationCounter = widget._animationCounter;
			setTimeout(function() {
				if(widget._animationCounter === currentAnimationCounter) {
					callback();
				}
			}, widget.options.animationSpeed * delayFactor);
		}
		else {
			callback();
		}
	}


	/**
	 * Callback for the "add-piece" mode -> add the requested colored piece in the targeted square, or clear
	 * the targeted square if it already contains the requested colored piece.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} coloredPiece
	 * @param {string} square Targeted square.
	 * @param {jQuery} target DOM node corresponding to the targeted square.
	 */
	function doAddPiece(widget, coloredPiece, square, target) {
		var oldColoredPiece = widget._position.square(square);

		// Remove-case
		if(oldColoredPiece === coloredPiece) {
			widget._position.square(square, '-');
			target.empty().append(buildHandle());
		}

		// Add-case
		else {
			widget._position.square(square, coloredPiece);
			target.empty().append(buildColoredPiece(coloredPiece));
		}

		// FEN update + notifications.
		notifyFENChanged(widget);
	}


	/**
	 * Callback for the "add-square-markers" mode -> toggle the requested square marker on the targeted square.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} color Square marker color.
	 * @param {string} square Targeted square.
	 * @param {jQuery} target DOM node corresponding to the targeted square.
	 */
	function doAddSquareMarker(widget, color, square) {

		// Remove-case
		if(widget._squareMarkers[square] === color) {
			onSquareMarkerChanged(widget, square, color);
			delete widget._squareMarkers[square];
		}

		// Add-case
		else {
			onSquareMarkerChanged(widget, square, widget._squareMarkers[square], color);
			widget._squareMarkers[square] = color;
		}

		// Square marker list update + notifications.
		notifySquareMarkersChanged(widget);
	}


	/**
	 * Callback for the "add-text-markers" mode -> toggle the requested text marker on the targeted square.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} markerDescriptor 2 characters: the first one is the color, the second one the glyph.
	 * @param {string} square Targeted square.
	 * @param {jQuery} target DOM node corresponding to the targeted square.
	 */
	function doAddTextMarker(widget, markerDescriptor, square) {

		// Remove-case
		if(widget._textMarkers[square] === markerDescriptor) {
			onTextMarkerChanged(widget, square, markerDescriptor);
			delete widget._textMarkers[square];
		}

		// Add-case
		else {
			onTextMarkerChanged(widget, square, widget._textMarkers[square], markerDescriptor);
			widget._textMarkers[square] = markerDescriptor;
		}

		// Text marker list update + notifications.
		notifyTextMarkersChanged(widget);
	}


	/**
	 * Callback for the "add-arrow-markers" mode -> toggle the requested arrow marker between the given squares.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 * @param {string} color Arrow marker color.
	 * @param {string} fromSquare
	 * @param {string} toSquare
	 */
	function doAddArrowMarker(widget, color, fromSquare, toSquare) {
		var key = fromSquare + toSquare;

		// Remove-case
		if(widget._arrowMarkers[key] === color) {
			onArrowMarkerChanged(widget, key, widget._arrowMarkers[key]);
			delete widget._arrowMarkers[key];
		}

		// Add-case
		else {
			onArrowMarkerChanged(widget, key, widget._arrowMarkers[key], color);
			widget._arrowMarkers[key] = color;
		}

		// Arrow marker list update + notifications.
		notifyArrowMarkersChanged(widget);
	}


	/**
	 * Update the property holding the current position in FEN format, and trigger the corresponding event.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function notifyFENChanged(widget) {
		var oldValue = widget.options.position;
		widget.options.position = widget._position.fen();
		widget._trigger('positionChange', null, { oldValue:oldValue, newValue:widget.options.position });
	}


	/**
	 * Update the property holding the list of square markers, and trigger the corresponding event.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function notifySquareMarkersChanged(widget) {
		var oldValue = widget.options.squareMarkers;
		widget.options.squareMarkers = flattenMarkerList(widget._squareMarkers);
		widget._trigger('squareMarkerChange', null, { oldValue:oldValue, newValue:widget.options.squareMarkers });
	}


	/**
	 * Update the property holding the list of arrow markers, and trigger the corresponding event.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function notifyArrowMarkersChanged(widget) {
		var oldValue = widget.options.arrowMarkers;
		widget.options.arrowMarkers = flattenMarkerList(widget._arrowMarkers);
		widget._trigger('arrowMarkerChange', null, { oldValue:oldValue, newValue:widget.options.arrowMarkers });
	}


	/**
	 * Update the property holding the list of text markers, and trigger the corresponding event.
	 *
	 * @param {rpbchess-ui.chessboard} widget
	 */
	function notifyTextMarkersChanged(widget) {
		var oldValue = widget.options.textMarkers;
		widget.options.textMarkers = flattenMarkerList(widget._textMarkers);
		widget._trigger('textMarkerChange', null, { oldValue:oldValue, newValue:widget.options.textMarkers });
	}



	// ---------------------------------------------------------------------------
	// Widget registration in the jQuery widget framework.
	// ---------------------------------------------------------------------------

	/**
	 * Public static properties.
	 */
	$.chessboard = {
		MINIMUM_SQUARE_SIZE: MINIMUM_SQUARE_SIZE,
		MAXIMUM_SQUARE_SIZE: MAXIMUM_SQUARE_SIZE
	};


	/**
	 * Widget registration.
	 */
	$.widget('rpbchess-ui.chessboard', {

		/**
		 * Default options.
		 */
		options: {

			/**
			 * String describing the chess position (FEN format).
			 */
			position: 'empty',

			/**
			 * Square markers (used to highlight some particular squares of interest).
			 * Specified as a list of comma-separated tokens such as:
			 * `'Gc4,Rd5,Ye6'` (highlight square c4 in green, d5 in red, and e6 in yellow).
			 */
			squareMarkers: '',

			/**
			 * Arrow markers (used to highlight displacements, threats, etc...).
			 * Specified as a list of comma-separated tokens such as:
			 * `'Ga1a5,Re8g8,Ye2e4'` (put a red arrow from a1 to a5, a red from e8 to g8, and a yellow on from e2 to e4).
			 */
			arrowMarkers: '',

			/**
			 * Text markers (used to highlight and identify some particular squares of interest).
			 * Specified as a list of comma-separated tokens such as:
			 * `'GAc4,Rzd5,Y9e6'` (character 'A' in square c4 in green, 'z' in d5 in red, and '9' in e6 in yellow).
			 */
			textMarkers: '',

			/**
			 * Whether the chessboard is flipped or not.
			 */
			flip: false,

			/**
			 * Size of the squares (in pixel).
			 */
			squareSize: 32,

			/**
			 * Whether the row and column coordinates are shown or not.
			 */
			showCoordinates: true,

			/**
			 * Duration of the animations when playing moves (in milliseconds).
			 */
			animationSpeed: 200,

			/**
			 * Whether moves should be highlighted with an arrow or not.
			 */
			showMoveArrow: false,

			/**
			 * Whether the user can moves the pieces or not, edit the annotations or not, etc... Available values are:
			 * * 'none': no move is allowed, drag & drop is disabled.
			 * * 'play': only legal chess moves are allowed.
			 * * 'movePieces': move the pieces on the board, regardless of the chess rules.
			 * * 'addPieces-[color][piece]': add the corresponding colored piece on the board.
			 * * 'addSquareMarkers-[color]': add square marker annotations on the board.
			 * * 'addArrowMarkers-[color]': add arrow marker annotations on the board.
			 */
			interactionMode: 'none',

			/**
			 * Colorset to use for the squares.
			 */
			colorset: '',

			/**
			 * Pieceset to use to represent chess pieces.
			 */
			pieceset: ''
		},


		/**
		 * The chess position.
		 * @type {kokopu.Position}
		 */
		_position: null,


		/**
		 * Square markers.
		 * @type {object}
		 */
		_squareMarkers: null,


		/**
		 * Arrow markers.
		 * @type {object}
		 */
		_arrowMarkers: null,


		/**
		 * Text markers.
		 * @type {object}
		 */
		_textMarkers: null,


		/**
		 * Initial and destination squares of the move arrow, if any.
		 * @type {object?} `null` if there is no move arrow.
		 */
		_moveArrow: null,


		/**
		 * Counter to validate/invalidate animations.
		 */
		_animationCounter: 0,


		/**
		 * ID suffix of the arrow marker tip.
		 */
		_arrowMarkerEndIdSuffix: null,


		/**
		 * Constructor.
		 */
		_create: function() {
			this.element.addClass('rpbui-chessboard').disableSelection();
			this.options.position      = initializePosition     (this, this.options.position     );
			this.options.squareMarkers = initializeSquareMarkers(this, this.options.squareMarkers);
			this.options.arrowMarkers  = initializeArrowMarkers (this, this.options.arrowMarkers );
			this.options.textMarkers   = initializeTextMarkers  (this, this.options.textMarkers  );
			this.options.squareSize      = filterOptionSquareSize     (this.options.squareSize     );
			this.options.interactionMode = filterOptionInteractionMode(this.options.interactionMode);
			this.options.animationSpeed  = filterOptionAnimationSpeed (this.options.animationSpeed );
			this.options.flip            = filterBoolean(this.options.flip           , false);
			this.options.showCoordinates = filterBoolean(this.options.showCoordinates, true );
			this.options.showMoveArrow   = filterBoolean(this.options.showMoveArrow  , false);
			this.options.colorset = filterSetCode(this.options.colorset);
			this.options.pieceset = filterSetCode(this.options.pieceset);
			refresh(this);
		},


		/**
		 * Destructor.
		 */
		_destroy: function() {
			this.element.empty().removeClass('rpbui-chessboard').enableSelection();
		},


		/**
		 * Option setter.
		 */
		_setOption: function(key, value) {

			// Validate the new value.
			switch(key) {
				case 'position'     : value = initializePosition     (this, value); break;
				case 'squareMarkers': value = initializeSquareMarkers(this, value); break;
				case 'arrowMarkers' : value = initializeArrowMarkers (this, value); break;
				case 'textMarkers'  : value = initializeTextMarkers  (this, value); break;
				case 'squareSize'     : value = filterOptionSquareSize     (value); break;
				case 'interactionMode': value = filterOptionInteractionMode(value); break;
				case 'animationSpeed' : value = filterOptionAnimationSpeed (value); break;
				case 'flip'           : value = filterBoolean(value, false); break;
				case 'showCoordinates': value = filterBoolean(value, true ); break;
				case 'showMoveArrow'  : value = filterBoolean(value, false); break;
				case 'colorset': value = filterSetCode(value); break;
				case 'pieceset': value = filterSetCode(value); break;
			}

			// Set the new value.
			var oldValue = this.options[key];
			if(oldValue === value) {
				return;
			}
			this.options[key] = value;

			// Update the widget.
			switch(key) {
				case 'position': this._moveArrow=null; refresh(this); this._trigger('positionChange', null, { oldValue:oldValue, newValue:this.options.position }); break;
				case 'flip': refresh(this); this._trigger('flipChange', null, { oldValue:oldValue, newValue:this.options.flip }); break;
				case 'squareMarkers': onSquareMarkersChanged(this); this._trigger('squareMarkersChange', null, { oldValue:oldValue, newValue:this.options.squareMarkers }); break;
				case 'arrowMarkers' : onArrowMarkersChanged (this); this._trigger('arrowMarkersChange' , null, { oldValue:oldValue, newValue:this.options.arrowMarkers  }); break;
				case 'textMarkers'  : onTextMarkersChanged  (this); this._trigger('textMarkersChange'  , null, { oldValue:oldValue, newValue:this.options.textMarkers   }); break;
				case 'squareSize': onSquareSizeChanged(this, oldValue, value); break;
				case 'showCoordinates': onShowCoordinatesChanged(this); break;
				case 'animationSpeed': break;
				case 'showMoveArrow' : break;
				default: refresh(this); break;
			}
		},


		/**
		 * Get or set the turn flag.
		 *
		 * @param {string} value 'w' or 'b' (or nothing to get the current value).
		 * @returns {undefined|string}
		 */
		turn: function(value) {
			if(typeof value === 'undefined' || value === null) {
				return this._position.turn();
			}
			else if(value !== this._position.turn()) {
				this._position.turn(value);
				onTurnChanged(this);
				notifyFENChanged(this);
			}
		},


		/**
		 * Get or set the castle right flags.
		 *
		 * @param {string} castle
		 * @param {boolean?} value Nothing to get the current value.
		 * @returns {undefined|boolean}
		 */
		castling: function(castle, value) {
			if(typeof value === 'undefined' || value === null) {
				return this._position.castling(castle);
			}
			else if(value !== this._position.castling(castle)) {
				this._position.castling(castle, value);
				notifyFENChanged(this);
			}
		},


		/**
		 * Get or set the "en-passant" flag.
		 *
		 * @param {string} value 'a', 'b', ... , 'h', or '-' (or nothing to get the current value).
		 * @returns {undefined|string}
		 */
		enPassant: function(value) {
			if(typeof value === 'undefined' || value === null) {
				return this._position.enPassant();
			}
			else if(value !== this._position.enPassant()) {
				this._position.enPassant(value);
				notifyFENChanged(this);
			}
		},


		/**
		 * Move a piece from square `from` to square `to`.
		 *
		 * @param {{from: string, to: string}} move
		 */
		movePiece: function(move) {
			doMovePiece(this, move, this.options.animationSpeed > 0, this.options.showMoveArrow);
		},


		/**
		 * Play a legal move.
		 *
		 * @param {string|kokopu.MoveDescriptor} move
		 */
		play: function(move) {
			var moveDescriptor = kokopu.isMoveDescriptor(move) ? move : this._position.notation(move);
			doPlay(this, moveDescriptor, this.options.animationSpeed > 0, this.options.showMoveArrow);
		},


		/**
		 * Add a square marker.
		 *
		 * @param {string} squareMarker
		 */
		addSquareMarker: function(squareMarker) {
			if(SQUARE_MARKER_TOKEN.test(squareMarker)) {
				var color = RegExp.$1;
				var square = RegExp.$2;
				if(this._squareMarkers[square] !== color) {
					onSquareMarkerChanged(this, square, this._squareMarkers[square], color);
					this._squareMarkers[square] = color;
					notifySquareMarkersChanged(this);
				}
			}
		},


		/**
		 * Remove a square marker.
		 *
		 * @param {string} squareMarker
		 */
		removeSquareMarker: function(squareMarker) {
			if(SQUARE_MARKER_TOKEN_NO_COLOR.test(squareMarker)) {
				var square = RegExp.$1;
				if(typeof this._squareMarkers[square] !== 'undefined') {
					onSquareMarkerChanged(this, square, this._squareMarkers[square]);
					delete this._squareMarkers[square];
					notifySquareMarkersChanged(this);
				}
			}
		},


		/**
		 * Add an arrow marker.
		 *
		 * @param {string} arrowMarker
		 */
		addArrowMarker: function(arrowMarker) {
			if(ARROW_MARKER_TOKEN.test(arrowMarker)) {
				var color = RegExp.$1;
				var key = RegExp.$2;
				if(this._arrowMarkers[key] !== color) {
					onArrowMarkerChanged(this, key, this._arrowMarkers[key], color);
					this._arrowMarkers[key] = color;
					notifyArrowMarkersChanged(this);
				}
			}
		},


		/**
		 * Remove an arrow marker.
		 *
		 * @param {string} arrowMarker
		 */
		removeArrowMarker: function(arrowMarker) {
			if(ARROW_MARKER_TOKEN_NO_COLOR.test(arrowMarker)) {
				var key = RegExp.$1;
				if(typeof this._arrowMarkers[key] !== 'undefined') {
					onArrowMarkerChanged(this, key, this._arrowMarkers[key]);
					delete this._arrowMarkers[key];
					notifyArrowMarkersChanged(this);
				}
			}
		},


		/**
		 * Add a text marker.
		 *
		 * @param {string} textMarker
		 */
		addTextMarker: function(textMarker) {
			if(TEXT_MARKER_TOKEN.test(textMarker)) {
				var descriptor = RegExp.$1;
				var square = RegExp.$2;
				if(this._textMarkers[square] !== descriptor) {
					onTextMarkerChanged(this, square, this._textMarkers[square], descriptor);
					this._textMarkers[square] = descriptor;
					notifyTextMarkersChanged(this);
				}
			}
		},


		/**
		 * Remove a text marker.
		 *
		 * @param {string} textMarker
		 */
		removeTextMarker: function(textMarker) {
			if(TEXT_MARKER_TOKEN_NO_DESCRIPTOR.test(textMarker)) {
				var square = RegExp.$1;
				if(typeof this._textMarkers[square] !== 'undefined') {
					onTextMarkerChanged(this, square, this._textMarkers[square]);
					delete this._textMarkers[square];
					notifyTextMarkersChanged(this);
				}
			}
		},


		/**
		 * When the widget is placed in a resizable container (such as a jQuery resizable frame or dialog),
		 * it is often suitable to let the container control the widget size. This can be done
		 * as follows:
		 *
		 *   $('#my-chessboard-widget).chessboard('sizeControlledByContainer', $('#container'));
		 *
		 * The options that controls the aspect of the chessboard ('squareSize', 'showCoordinates', etc...)
		 * should not be modified in this mode.
		 *
		 * TODO: Does not work if for instance only the height of the container is constrained by the chessboard,
		 * but not its width.
		 *
		 * @param {jQuery} container
		 * @param {string} eventName Name of the event triggered when the container is resized.
		 */
		sizeControlledByContainer: function(container, eventName) {
			var obj = this;
			container.on(eventName, function(event, ui) {

				// Save the initial information about the geometry of the widget and its container.
				if(obj._initialGeometryInfo === undefined) {
					obj._initialGeometryInfo = {
						squareSize: obj.options.squareSize,
						height    : ui.originalSize.height,
						width     : ui.originalSize.width
					};
				}

				// Compute the new square size parameter.
				var deltaW = ui.size.width  - obj._initialGeometryInfo.width ;
				var deltaH = ui.size.height - obj._initialGeometryInfo.height;
				var deltaWPerSq = Math.floor(deltaW / 9);
				var deltaHPerSq = Math.floor(deltaH / 8);
				var newSquareSize = obj._initialGeometryInfo.squareSize + Math.min(deltaWPerSq, deltaHPerSq);

				// Update the widget if necessary.
				obj._setOption('squareSize', newSquareSize);
			});
		}

	}); /* $.widget('rpbchess-ui.chessboard', { ... }) */

}( /* global kokopu */ kokopu, /* global jQuery */ jQuery ));
