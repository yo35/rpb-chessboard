/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * @requires rpbchess.js
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires jQuery UI Selectable
 * @requires jQuery UI Draggable (optional, only if the moveable piece feature is enabled)
 * @requires jQuery UI Droppable (optional, only if the moveable piece feature is enabled)
 */
(function(RPBChess, $)
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
	 * HTML template for handle nodes.
	 *
	 * @constant
	 */
	var HANDLE_TEMPLATE = '<div class="uichess-chessboard-handle"></div>';


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
			if(markers.hasOwnProperty(item)) {
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
	 * Ensure that the given string is a valid value for the `interactionMode` option.
	 *
	 * @param {string} interactionMode
	 * @returns {string}
	 */
	function filterOptionInteractionMode(interactionMode) {
		return (interactionMode==='play' || interactionMode==='movePieces' || /^addPieces-[wb][kqrbnp]$/.test(interactionMode) ||
			/^add(?:Square|Arrow)Markers-[GRY]$/.test(interactionMode)) ? interactionMode : 'none';
	}


	/**
	 * Initialize the internal `RPBChess.Position` object with the given FEN string.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {string} fen
	 * @returns {string}
	 */
	function initializePosition(widget, fen) {

		// Trim the input.
		fen = fen.replace(/^\s+|\s+$/g, '');

		// Parse the FEN string.
		try {
			widget._position = new RPBChess.Position(fen);
			fen = widget._position.fen();
		}
		catch(e) {
			if(e instanceof RPBChess.exceptions.InvalidFEN) {
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
	 * @param {uichess.chessboard} widget
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
	 * @param {uichess.chessboard} widget
	 * @param {string} value
	 * @returns {string}
	 */
	function initializeArrowMarkers(widget, value) {
		widget._arrowMarkers = parseMarkerList(value, ARROW_MARKER_TOKEN);
		return flattenMarkerList(widget._arrowMarkers);
	}



	// ---------------------------------------------------------------------------
	// Widget rendering
	// ---------------------------------------------------------------------------

	/**
	 * Destroy the widget content, prior to a refresh or a widget destruction.
	 *
	 * @param {uichess.chessboard} widget
	 */
	function destroyContent(widget) {
		widget.element.empty();
	}


	/**
	 * Build the error message resulting from a FEN parsing error.
	 *
	 * @param {uichess.chessboard} widget
	 * @returns {string}
	 */
	function buildErrorMessage(widget) {

		// Build the error report box.
		var res = '<div class="uichess-chessboard-error">' +
			'<div class="uichess-chessboard-errorTitle">Error while analysing a FEN string.</div>';

		// Optional message.
		if(widget._position.message !== null) {
			res += '<div class="uichess-chessboard-errorMessage">' + widget._position.message + '</div>';
		}

		// Close the error report box, and return the result.
		res += '</div>';
		return res;
	}


	/**
	 * Build the widget content.
	 *
	 * @param {uichess.chessboard} widget
	 * @returns {string}
	 */
	function buildContent(widget) {
		var ROWS    = widget.options.flip ? '12345678' : '87654321';
		var COLUMNS = widget.options.flip ? 'hgfedcba' : 'abcdefgh';

		// Open the "table" node.
		var globalClazz = 'uichess-chessboard-table uichess-chessboard-size' + widget.options.squareSize;
		if(!widget.options.showCoordinates) {
			globalClazz += ' uichess-chessboard-hideCoordinates';
		}
		var res = '<div class="' + globalClazz + '">';

		// For each row...
		for(var r=0; r<8; ++r) {

			// Begin row + row coordinate cell.
			res += '<div class="uichess-chessboard-row"><div class="uichess-chessboard-cell uichess-chessboard-rowCoordinate">' + ROWS[r] + '</div>';

			// Chessboard squares
			for(var c=0; c<8; ++c) {

				// Square
				var square = COLUMNS[c] + ROWS[r];
				var squareColor = RPBChess.squareColor(square) === 'w' ? 'light' : 'dark';
				var clazz = 'uichess-chessboard-sized uichess-chessboard-cell uichess-chessboard-square uichess-chessboard-' + squareColor + 'Square';
				if(square in widget._squareMarkers) {
					clazz += ' uichess-chessboard-squareMarker uichess-chessboard-markerColor-' + widget._squareMarkers[square];
				}
				res += '<div class="' + clazz + '">';

				// Colored piece within the square (if any).
				var coloredPiece = widget._position.square(square);
				if(coloredPiece === '-') {
					res += HANDLE_TEMPLATE;
				}
				else {
					res += '<div class="uichess-chessboard-sized uichess-chessboard-piece uichess-chessboard-piece-' + coloredPiece.piece +
						' uichess-chessboard-color-' + coloredPiece.color + '">' + HANDLE_TEMPLATE + '</div>';
				}
				res += '</div>';
			}

			// Additional cell for the turn flag.
			res += '<div class="uichess-chessboard-cell">';
			if(ROWS[r] === '8' || ROWS[r] === '1') {
				var flagColor = ROWS[r] === '8' ? 'b' : 'w';
				var clazz = 'uichess-chessboard-sized uichess-chessboard-turnFlag uichess-chessboard-color-' + flagColor;
				if(flagColor !== widget._position.turn()) {
					clazz += ' uichess-chessboard-inactiveFlag';
				}
				res += '<div class="' + clazz + '"></div>';
			}

			// End additional cell + end row.
			res += '</div></div>';
		}

		// Column coordinates
		res += '<div class="uichess-chessboard-row uichess-chessboard-columnCoordinateRow">' +
			'<div class="uichess-chessboard-cell uichess-chessboard-rowCoordinate"></div>';
		for(var c=0; c<8; ++c) {
			res += '<div class="uichess-chessboard-cell uichess-chessboard-columnCoordinate">' + COLUMNS[c] + '</div>';
		}
		res += '<div class="uichess-chessboard-cell"></div></div>';

		// Arrow markers
		res += '<svg class="uichess-chessboard-annotations" viewBox="0 0 8 8">' +
			'<defs>' +
				'<marker id="uichess-chessboard-arrowMarkerEnd-G" markerWidth="4" markerHeight="4" refX="2.5" refY="2" orient="auto"><path d="M 4,2 L 0,4 L 1,2 L 0,0 Z" /></marker>' +
				'<marker id="uichess-chessboard-arrowMarkerEnd-R" markerWidth="4" markerHeight="4" refX="2.5" refY="2" orient="auto"><path d="M 4,2 L 0,4 L 1,2 L 0,0 Z" /></marker>' +
				'<marker id="uichess-chessboard-arrowMarkerEnd-Y" markerWidth="4" markerHeight="4" refX="2.5" refY="2" orient="auto"><path d="M 4,2 L 0,4 L 1,2 L 0,0 Z" /></marker>' +
			'</defs>';
		for(var arrow in widget._arrowMarkers) {
			if(widget._arrowMarkers.hasOwnProperty(arrow) && /^([a-h][1-8])([a-h][1-8])$/.test(arrow)) {
				var fromSquare = RegExp.$1;
				var toSquare = RegExp.$2;
				if(fromSquare !== toSquare) {
					var vc = getArrowCoordinatesInSVG(widget, fromSquare, toSquare);
					var clazz = 'uichess-chessboard-arrowMarker uichess-chessboard-arrowMarker-' + fromSquare + toSquare +
						' uichess-chessboard-markerColor-' + widget._arrowMarkers[arrow];
					var marker = 'url(#uichess-chessboard-arrowMarkerEnd-' + widget._arrowMarkers[arrow] + ')';
					res += '<line class="' + clazz + '" x1="' + vc.x1 + '" y1="' + vc.y1 + '" x2="' + vc.x2 + '" y2="' + vc.y2 + '" marker-end="' + marker + '" />';
				}
			}
		}
		res += '</svg>';

		// Close the "table" node and return the result.
		res += '</div>';
		return res;
	}


	/**
	 * Refresh the widget.
	 *
	 * @param {uichess.chessboard} widget
	 */
	function refresh(widget) {
		destroyContent(widget);
		if(widget._position === null) {
			return;
		}

		// Handle parsing error problems.
		if(widget._position instanceof RPBChess.exceptions.InvalidFEN) {
			$(buildErrorMessage(widget)).appendTo(widget.element);
		}

		// Regular rendering
		else {
			$(buildContent(widget)).appendTo(widget.element);
			if(widget.options.interactionMode==='play' || widget.options.interactionMode==='movePieces') {
				tagSquares(widget);
				enableMovePieceOrPlayBehavior(widget, widget.options.interactionMode==='play');
			}
			else if(/^addPieces-([wb])([kqrbnp])$/.test(widget.options.interactionMode)) {
				var color = RegExp.$1;
				var piece = RegExp.$2;
				tagSquares(widget);
				enableAddPieceBehavior(widget, { color:color, piece:piece });
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
		}
	}


	/**
	 * Update the widget when the turn gets modified.
	 *
	 * @param {uichess.chessboard} widget
	 */
	function onTurnChanged(widget) {
		$('.uichess-chessboard-turnFlag', widget.element).toggleClass('uichess-chessboard-inactiveFlag');
	}


	/**
	 * Update the widget when the square-size parameter gets modified.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {number} oldValue
	 * @param {number} newValue
	 */
	function onSquareSizeChanged(widget, oldValue, newValue) {
		$('.uichess-chessboard-table', widget.element).removeClass('uichess-chessboard-size' + oldValue).addClass('uichess-chessboard-size' + newValue);
	}


	/**
	 * Update the widget when the show-coordinates parameter gets modified.
	 *
	 * @param {uichess.chessboard} widget
	 */
	function onShowCoordinatesChanged(widget) {
		$('.uichess-chessboard-table', widget.element).toggleClass('uichess-chessboard-hideCoordinates');
	}


	/**
	 * Update the widget when a square marker gets added/changed/removed.
	 *
	 * @param {jQuery} target DOM node corresponding to the targeted square.
	 * @param {string} oldValue `undefined` if the square marker is added.
	 * @param {string} newValue `undefined` if the square marker is removed.
	 */
	function onSquareMarkerChanged(target, oldValue, newValue) {
		if(typeof oldValue === 'undefined') {
			target.addClass('uichess-chessboard-squareMarker').addClass('uichess-chessboard-markerColor-' + newValue);
		}
		else if(typeof newValue === 'undefined') {
			target.removeClass('uichess-chessboard-squareMarker').removeClass('uichess-chessboard-markerColor-' + oldValue);
		}
		else {
			target.removeClass('uichess-chessboard-markerColor-' + oldValue).addClass('uichess-chessboard-markerColor-' + newValue);
		}
	}


	/**
	 * Update the widget when an arrow marker gets added/changed/removed.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {string} key
	 * @param {string} oldValue `undefined` if the square marker is added.
	 * @param {string} newValue `undefined` if the square marker is removed.
	 */
	function onArrowMarkerChanged(widget, key, oldValue, newValue) {
		if(typeof oldValue === 'undefined') {
			var fromSquare = key.substr(0, 2);
			var toSquare = key.substr(2, 2);
			if(fromSquare !== toSquare) {
				var vc = getArrowCoordinatesInSVG(widget, fromSquare, toSquare);
				var clazz = 'uichess-chessboard-arrowMarker uichess-chessboard-arrowMarker-' + key + ' uichess-chessboard-markerColor-' + newValue;
				var marker = 'url(#uichess-chessboard-arrowMarkerEnd-' + newValue + ')';
				var line = $(document.createElementNS('http://www.w3.org/2000/svg', 'line'));
				line.attr({ 'x1':vc.x1, 'y1':vc.y1, 'x2':vc.x2, 'y2':vc.y2, 'class':clazz, 'marker-end':marker });
				$('.uichess-chessboard-annotations', widget.element).append(line);
			}
		}
		else if(typeof newValue === 'undefined') {
			$('.uichess-chessboard-arrowMarker-' + key, widget.element).remove();
		}
		else {
			var clazz = 'uichess-chessboard-arrowMarker uichess-chessboard-arrowMarker-' + key + ' uichess-chessboard-markerColor-' + newValue;
			$('.uichess-chessboard-arrowMarker-' + key, widget.element).attr('class', clazz);
		}
	}


	/**
	 * Fetch the DOM node corresponding to a given square.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {string} square
	 * @returns {jQuery}
	 */
	function fetchSquare(widget, square) {
		var ROWS    = widget.options.flip ? '12345678' : '87654321';
		var COLUMNS = widget.options.flip ? 'hgfedcba' : 'abcdefgh';
		var r = ROWS   .indexOf(square[1]);
		var c = COLUMNS.indexOf(square[0]);
		return $($('.uichess-chessboard-square', widget.element).get(r*8 + c));
	}


	/**
	 * Return the coordinates of the given square in the annotation canvas.
	 *
	 * @param {uichess.chessboard} widget
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
	 * @param {uichess.chessboard} widget
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
	 * Where `e` is a DOM object with the class `uichess-chessboard-square`.
	 *
	 * @param {uichess.chessboard} widget
	 */
	function tagSquares(widget) {
		var ROWS    = widget.options.flip ? '12345678' : '87654321';
		var COLUMNS = widget.options.flip ? 'hgfedcba' : 'abcdefgh';
		var r = 0;
		var c = 0;
		$('.uichess-chessboard-square', widget.element).each(function(index, element) {
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
	 * @param {uichess.chessboard} widget
	 * @param {boolean} playMode
	 */
	function enableMovePieceOrPlayBehavior(widget, playMode) {

		// Enable dragging.
		$('.uichess-chessboard-piece', widget.element).draggable({
			cursor        : 'move',
			cursorAt      : { top: widget.options.squareSize/2, left: widget.options.squareSize/2 },
			revert        : true,
			revertDuration: 0,
			zIndex        : 300
		});

		// Enable dropping.
		var tableNode = $('.uichess-chessboard-table', widget.element).get(0);
		var dropCallback = playMode ? doPlay : doMovePiece;
		$('.uichess-chessboard-square', widget.element).droppable({
			hoverClass: 'uichess-chessboard-squareHover',

			accept: function(e) {
				return $(e).closest('.uichess-chessboard-table').get(0) === tableNode;
			},

			drop: function(event, ui) {
				var target      = $(event.target);
				var movingPiece = ui.draggable;
				if(movingPiece.hasClass('uichess-chessboard-piece')) {
					var move = { from: movingPiece.parent().data('square'), to: target.data('square') };
					dropCallback(widget, move, false);
					// TODO Handle promotions in drag & drop.
				}
			}
		});
	}


	/**
	 * Enable the "add-piece" interaction mode.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {{color:string, piece:string}} coloredPiece
	 */
	function enableAddPieceBehavior(widget, coloredPiece) {
		$('.uichess-chessboard-square', widget.element).mousedown(function() {
			doAddPiece(widget, coloredPiece, $(this).data('square'), $(this));
		});
	}


	/**
	 * Enable the "add-square-marker" interaction mode.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {string} markerColor
	 */
	function enableAddSquareMarkerBehavior(widget, markerColor) {
		$('.uichess-chessboard-square', widget.element).mousedown(function() {
			doAddSquareMarker(widget, markerColor, $(this).data('square'), $(this));
		});
	}


	/**
	 * Enable the "add-arrow-marker" interaction mode.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {string} markerColor
	 */
	function enableAddArrowMarkerBehavior(widget, markerColor) {

		// Must be initialized each time a drag starts.
		var fromSquare = null;
		var canvasOffset = null;

		// Conversion page coordinate -> SVG canvas coordinates.
		var canvas = $('.uichess-chessboard-annotations', widget.element);
		var canvasWidth  = canvas.width();
		var canvasHeight = canvas.height();
		function xInCanvas(x) { return (x - canvasOffset.left) * 8 / canvasWidth; }
		function yInCanvas(y) { return (y - canvasOffset.top) * 8 / canvasHeight; }

		// Enable dragging.
		$('.uichess-chessboard-square .uichess-chessboard-handle', widget.element).draggable({
			cursor  : 'crosshair',
			cursorAt: { top: widget.options.squareSize/2, left: widget.options.squareSize/2 },
			helper  : function() { return $('<div class="uichess-chessboard-sized"></div>'); },

			start: function(event) {

				// Initialized the drag control variables.
				fromSquare = $(event.target).closest('.uichess-chessboard-square').data('square');
				canvasOffset = canvas.offset();

				// Create the temporary arrow marker.
				var clazz = 'uichess-chessboard-arrowMarker uichess-chessboard-markerColor-' + markerColor;
				var marker = 'url(#uichess-chessboard-arrowMarkerEnd-' + markerColor + ')';
				var p = getSquareCoordinatesInSVG(widget, fromSquare);
				var line = $(document.createElementNS('http://www.w3.org/2000/svg', 'line'));
				line.attr({ 'x1':p.x, 'y1':p.y, 'x2':p.x, 'y2':p.y, 'class':clazz, 'marker-end':marker, 'id':'uichess-chessboard-draggedArrowMarker' });
				line.appendTo(canvas);
			},

			drag: function(event) {
				$('#uichess-chessboard-draggedArrowMarker').attr({ 'x2':xInCanvas(event.pageX), 'y2':yInCanvas(event.pageY) });
			},

			stop: function() {
				$('#uichess-chessboard-draggedArrowMarker').remove();
			}
		});

		// Enable dropping.
		var tableNode = $('.uichess-chessboard-table', widget.element).get(0);
		$('.uichess-chessboard-square', widget.element).droppable({
			hoverClass: 'uichess-chessboard-squareHover',

			accept: function(e) {
				return $(e).closest('.uichess-chessboard-table').get(0) === tableNode;
			},

			drop: function(event) {
				var toSquare = $(event.target).data('square');
				doAddArrowMarker(widget, markerColor, fromSquare, toSquare);
			}
		});
	}


	/**
	 * Animate a displacement.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {string} from
	 * @param {string} to
	 * @param {jQuery} movingPiece
	 */
	function animateMove(widget, from, to, movingPiece) {
		from = RPBChess.squareToCoordinates(from);
		to   = RPBChess.squareToCoordinates(to  );
		var deltaTop  = (from.r - to.r) * widget.options.squareSize * (widget.options.flip ? 1 : -1);
		var deltaLeft = (to.c - from.c) * widget.options.squareSize * (widget.options.flip ? 1 : -1);
		movingPiece.css('top', deltaTop + 'px');
		movingPiece.css('left', deltaLeft + 'px');
		movingPiece.animate({ top: '0px', left: '0px' }, widget.options.moveAnimation);
	}


	/**
	 * Callback for the "move pieces" mode -> move the moving piece to its destination square,
	 * clearing the latter beforehand if necessary.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {{from: string, to: string}} move The origin and destination squares.
	 * @param {boolean} animate
	 */
	function doMovePiece(widget, move, animate) {
		if(move.from === move.to || widget._position.square(move.from) === '-') {
			return;
		}
		widget._position.square(move.to, widget._position.square(move.from));
		widget._position.square(move.from, '-');

		// Update the DOM elements.
		var movingPiece = $('.uichess-chessboard-piece', fetchSquare(widget, move.from));
		movingPiece.parent().append(HANDLE_TEMPLATE);
		fetchSquare(widget, move.to).empty().append(movingPiece);
		if(animate) {
			animateMove(widget, move.from, move.to, movingPiece);
		}

		// FEN update + notifications.
		notifyFENChanged(widget);
	}


	/**
	 * Callback for the "play" mode -> check if the proposed move is legal, and handle
	 * the special situations (promotion, castle, en-passant...) that may be encountered.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {string|RPBChess.MoveDescriptor} move
	 * @param {boolean} animate
	 */
	function doPlay(widget, move, animate) {
		var moveDescriptor = widget._position.isMoveLegal(move);
		if(!moveDescriptor) {
			return;
		}
		widget._position.play(moveDescriptor);

		// Move the moving piece to its destination square.
		var movingPiece = $('.uichess-chessboard-piece', fetchSquare(widget, moveDescriptor.from()));
		movingPiece.parent().append(HANDLE_TEMPLATE);
		fetchSquare(widget, moveDescriptor.to()).empty().append(movingPiece);
		if(animate) {
			animateMove(widget, moveDescriptor.from(), moveDescriptor.to(), movingPiece);
		}

		// Castling move -> move the rook.
		if(moveDescriptor.type() === RPBChess.movetype.CASTLING_MOVE) {
			var movingRook = $('.uichess-chessboard-piece', fetchSquare(widget, moveDescriptor.rookFrom()));
			movingRook.parent().append(HANDLE_TEMPLATE);
			fetchSquare(widget, moveDescriptor.rookTo()).empty().append(movingRook);
			if(animate) {
				animateMove(widget, moveDescriptor.rookFrom(), moveDescriptor.rookTo(), movingRook);
			}
		}

		// En-passant move -> remove the taken pawn.
		if(moveDescriptor.type() === RPBChess.movetype.EN_PASSANT_CAPTURE) {
			fetchSquare(widget, moveDescriptor.enPassantSquare()).empty().append(HANDLE_TEMPLATE);
		}

		// Promotion move -> change the type of the promoted piece.
		if(moveDescriptor.type() === RPBChess.movetype.PROMOTION) {
			var callback = function() {
				movingPiece.removeClass('uichess-chessboard-piece-p').addClass('uichess-chessboard-piece-' + moveDescriptor.promotion());
			};
			if(animate) {
				setTimeout(callback, widget.options.moveAnimation * 0.8); // Switch to the promoted piece when the animation is 80%-complete.
			}
			else {
				callback();
			}
		}

		// Switch the turn flag.
		$('.uichess-chessboard-turnFlag', widget.element).toggleClass('uichess-chessboard-inactiveFlag');

		// FEN update + notifications.
		notifyFENChanged(widget);
	}


	/**
	 * Callback for the "add-piece" mode -> add the requested colored piece in the targeted square, or clear
	 * the targeted square if it already contains the requested colored piece.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {{color:string, piece:string}} coloredPiece
	 * @param {string} square Targeted square.
	 * @param {jQuery} target DOM node corresponding to the targeted square.
	 */
	function doAddPiece(widget, coloredPiece, square, target) {
		var oldColoredPiece = widget._position.square(square);

		// Remove-case
		if(typeof oldColoredPiece === 'object' && oldColoredPiece.color === coloredPiece.color && oldColoredPiece.piece === coloredPiece.piece) {
			widget._position.square(square, '-');
			target.empty().append(HANDLE_TEMPLATE);
		}

		// Add-case
		else {
			widget._position.square(square, coloredPiece);
			target.empty().append('<div class="uichess-chessboard-sized uichess-chessboard-piece uichess-chessboard-piece-' +
				coloredPiece.piece + ' uichess-chessboard-color-' + coloredPiece.color + '">' + HANDLE_TEMPLATE + '</div>');
		}

		// FEN update + notifications.
		notifyFENChanged(widget);
	}


	/**
	 * Callback for the "add-square-markers" mode -> toggle the requested square marker on the targeted square.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {string} color Square marker color.
	 * @param {string} square Targeted square.
	 * @param {jQuery} target DOM node corresponding to the targeted square.
	 */
	function doAddSquareMarker(widget, color, square, target) {

		// Remove-case
		if(widget._squareMarkers[square] === color) {
			onSquareMarkerChanged(target, color);
			delete widget._squareMarkers[square];
		}

		// Add-case
		else {
			onSquareMarkerChanged(target, widget._squareMarkers[square], color);
			widget._squareMarkers[square] = color;
		}

		// Square marker list update + notifications.
		notifySquareMarkersChanged(widget);
	}


	/**
	 * Callback for the "add-arrow-markers" mode -> toggle the requested arrow marker between the given squares.
	 *
	 * @param {uichess.chessboard} widget
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
	 * @param {uichess.chessboard} widget
	 */
	function notifyFENChanged(widget) {
		var oldValue = widget.options.position;
		widget.options.position = widget._position.fen();
		widget._trigger('positionChange', null, { oldValue:oldValue, newValue:widget.options.position });
	}


	/**
	 * Update the property holding the list of square markers, and trigger the corresponding event.
	 *
	 * @param {uichess.chessboard} widget
	 */
	function notifySquareMarkersChanged(widget) {
		var oldValue = widget.options.squareMarkers;
		widget.options.squareMarkers = flattenMarkerList(widget._squareMarkers);
		widget._trigger('squareMarkersChange', null, { oldValue:oldValue, newValue:widget.options.squareMarkers });
	}


	/**
	 * Update the property holding the list of arrow markers, and trigger the corresponding event.
	 *
	 * @param {uichess.chessboard} widget
	 */
	function notifyArrowMarkersChanged(widget) {
		var oldValue = widget.options.arrowMarkers;
		widget.options.arrowMarkers = flattenMarkerList(widget._arrowMarkers);
		widget._trigger('arrowMarkersChange', null, { oldValue:oldValue, newValue:widget.options.arrowMarkers });
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
	$.widget('uichess.chessboard',
	{
		/**
		 * Default options.
		 */
		options:
		{
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
			moveAnimation: 200,

			/**
			 * Whether the user can moves the pieces or not, edit the annotations or not, etc... Available values are:
			 * * 'none': no move is allowed, drag & drop is disabled.
			 * * 'play': only legal chess moves are allowed.
			 * * 'movePieces': move the pieces on the board, regardless of the chess rules.
			 * * 'addPieces-[color][piece]': add the corresponding colored piece on the board.
			 * * 'addSquareMarkers-[color]': add square marker annotations on the board.
			 * * 'addArrowMarkers-[color]': add arrow marker annotations on the board.
			 */
			interactionMode: 'none'
		},


		/**
		 * The chess position.
		 * @type {RPBChess.Position}
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
		 * Constructor.
		 */
		_create: function() {
			this.element.addClass('uichess-chessboard').disableSelection();
			this.options.position      = initializePosition     (this, this.options.position     );
			this.options.squareMarkers = initializeSquareMarkers(this, this.options.squareMarkers);
			this.options.arrowMarkers  = initializeArrowMarkers (this, this.options.arrowMarkers );
			this.options.squareSize      = filterOptionSquareSize     (this.options.squareSize     );
			this.options.interactionMode = filterOptionInteractionMode(this.options.interactionMode);
			this.options.flip            = filterBoolean(this.options.flip           , false);
			this.options.showCoordinates = filterBoolean(this.options.showCoordinates, true );
			refresh(this);
		},


		/**
		 * Destructor.
		 */
		_destroy: function() {
			this.element.empty().removeClass('uichess-chessboard').enableSelection();
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
				case 'squareSize'     : value = filterOptionSquareSize     (value); break;
				case 'interactionMode': value = filterOptionInteractionMode(value); break;
				case 'flip'           : value = filterBoolean(value, false); break;
				case 'showCoordinates': value = filterBoolean(value, true ); break;
			}

			// Set the new value.
			var oldValue = this.options[key];
			if(oldValue === value) {
				return;
			}
			this.options[key] = value;

			// Update the widget.
			switch(key) {
				case 'position'     : refresh(this); this._trigger('positionChange'     , null, { oldValue:oldValue, newValue:this.options.position      }); break;
				case 'squareMarkers': refresh(this); this._trigger('squareMarkersChange', null, { oldValue:oldValue, newValue:this.options.squareMarkers }); break;
				case 'arrowMarkers' : refresh(this); this._trigger('arrowMarkersChange' , null, { oldValue:oldValue, newValue:this.options.arrowMarkers  }); break;
				case 'flip'         : refresh(this); this._trigger('flipChange'         , null, { oldValue:oldValue, newValue:this.options.flip          }); break;
				case 'squareSize': onSquareSizeChanged(this, oldValue, value); break;
				case 'showCoordinates': onShowCoordinatesChanged(this); break;
				case 'moveAnimation': break;
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
		 * @param {string} color Either 'w' or 'b'.
		 * @param {string} side Either 'k' or 'q'.
		 * @param {boolean?} value Nothing to get the current value.
		 * @returns {undefined|boolean}
		 */
		castleRights: function(color, side, value) {
			if(typeof value === 'undefined' || value === null) {
				return this._position.castleRights(color, side);
			}
			else if(value !== this._position.castleRights(color, side)) {
				this._position.castleRights(color, side, value);
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
			doMovePiece(this, move, this.options.moveAnimation > 0);
		},


		/**
		 * Play a legal move.
		 *
		 * @param {string|RPBChess.MoveDescriptor} move
		 */
		play: function(move) {
			doPlay(this, move, this.options.moveAnimation > 0);
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
					onSquareMarkerChanged(fetchSquare(this, square), this._squareMarkers[square], color);
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
					onSquareMarkerChanged(fetchSquare(this, square), this._squareMarkers[square]);
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

	}); /* $.widget('uichess.chessboard', { ... }) */

})( /* global RPBChess */ RPBChess, /* global jQuery */ jQuery );
