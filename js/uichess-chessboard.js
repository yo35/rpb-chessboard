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
	 * Ensure that the given number is a valid square size.
	 *
	 * @param {number} squareSize
	 * @returns {number}
	 */
	function filterOptionSquareSize(squareSize)
	{
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
		res += '<svg class="uichess-chessboard-annotations" viewBox="0 0 8 8">';
		for(var arrow in widget._arrowMarkers) {
			if(widget._arrowMarkers.hasOwnProperty(arrow) && /^([a-h])([1-8])([a-h])([1-8])$/.test(arrow)) {
				var x1 = COLUMNS.indexOf(RegExp.$1) + 0.5;
				var y1 = ROWS.indexOf   (RegExp.$2) + 0.5;
				var x2 = COLUMNS.indexOf(RegExp.$3) + 0.5;
				var y2 = ROWS.indexOf   (RegExp.$4) + 0.5;
				if(x1 !== x2 || y1 !== y2) {
					x2 += x1 < x2 ? -0.3 : x1 > x2 ? 0.3 : 0;
					y2 += y1 < y2 ? -0.3 : y1 > y2 ? 0.3 : 0;
					var clazz = 'uichess-chessboard-arrowMarker uichess-chessboard-markerColor-' + widget._arrowMarkers[arrow];
					res += '<line class="' + clazz + '" x1="' + x1 + '" y1="' + y1 + '" x2="' + x2 + '" y2="' + y2 + '" />';
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

			// TODO: enable interactions
			if(widget.options.interactionMode==='play' || widget.options.interactionMode==='movePieces') {
				tagSquares(widget);
				makePiecesDraggable(widget);
				makeSquaresDroppable(widget);
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
		$('.uichess-chessboard-table', widget.element).toogleClass('uichess-chessboard-hideCoordinates');
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
	 * Make the pieces on the board draggable.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {jQuery} [target=widget.element] Only the children of `target` are affected.
	 */
	function makePiecesDraggable(widget, target) {
		$('.uichess-chessboard-piece', target===undefined ? widget.element : target).draggable({
			cursor        : 'move',
			cursorAt      : { top: widget.options.squareSize/2, left: widget.options.squareSize/2 },
			revert        : true,
			revertDuration: 0,
			zIndex        : 300
		});
	}


	/**
	 * Make the squares of the board acceptable drop targets for pieces.
	 *
	 * @param {uichess.chessboard} widget
	 */
	function makeSquaresDroppable(widget) {
		var tableNode = $('.uichess-chessboard-table', widget.element).get(0);
		$('.uichess-chessboard-square', widget.element).droppable({
			hoverClass: 'uichess-chessboard-squareHover',

			accept: function(e){
				return $(e).closest('.uichess-chessboard-table').get(0) === tableNode;
			},

			drop: function(event, ui) {
				var target      = $(event.target);
				var movingPiece = ui.draggable;
				if(movingPiece.hasClass('uichess-chessboard-piece')) {
					var move = { from: movingPiece.parent().data('square'), to: target.data('square') };
					if(move.from !== move.to) {
						if(widget.options.interactionMode === 'movePieces') {
							doMovePiece(widget, move, movingPiece, target);
						}
						else if(widget.options.interactionMode==='play' || widget.options.interactionMode==='movePieces') {
							doPlay(widget, move, movingPiece, target);
						}
					}
				}
			}

		});
	}


	/**
	 * Callback for the "move pieces" mode -> move the moving piece to its destination square,
	 * clearing the latter beforehand if necessary.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {{from: string, to: string}} move The origin and destination squares.
	 * @param {jQuery} movingPiece DOM node representing the moving piece.
	 * @param {jQuery} target DOM node representing the destination square.
	 */
	function doMovePiece(widget, move, movingPiece, target) {
		widget._position.square(move.to, widget._position.square(move.from));
		widget._position.square(move.from, '-');
		movingPiece.parent().append(HANDLE_TEMPLATE);
		target.empty().append(movingPiece);

		// Refresh the FEN string coding the position, and trigger the 'move' event.
		widget.options.position = widget._position.fen();
		widget._trigger('move', null, move);
		widget._trigger('change', null, widget.options.position);
	}


	/**
	 * Callback for the "play" mode -> check if the proposed move is legal, and handle
	 * the special situations (promotion, castle, en-passant...) that may be encountered.
	 *
	 * @param {uichess.chessboard} widget
	 * @param {{from: string, to: string}} move The origin and destination squares.
	 * @param {jQuery} movingPiece DOM node representing the moving piece.
	 * @param {jQuery} target DOM node representing the destination square.
	 */
	function doPlay(widget, move, movingPiece, target) {
		var moveDescriptor = widget._position.isMoveLegal(move);
		if(moveDescriptor === false) {
			move.promotion = 'q'; // TODO: allow other types of promoted pieces.
			moveDescriptor = widget._position.isMoveLegal(move);
			if(moveDescriptor === false) {
				return;
			}
		}
		widget._position.play(moveDescriptor);

		// Move the moving piece to its destination square.
		movingPiece.parent().append(HANDLE_TEMPLATE);
		target.empty().append(movingPiece);

		// Castling move -> move the rook.
		if(moveDescriptor.type() === RPBChess.movetype.CASTLING_MOVE) {
			var rookFrom = fetchSquare(widget, moveDescriptor.rookFrom());
			var rookTo   = fetchSquare(widget, moveDescriptor.rookTo());
			rookFrom.append(HANDLE_TEMPLATE);
			rookTo.empty().append($('.uichess-chessboard-piece', rookFrom));
		}

		// En-passant move -> remove the taken pawn.
		if(moveDescriptor.type() === RPBChess.movetype.EN_PASSANT_CAPTURE) {
			fetchSquare(widget, moveDescriptor.enPassantSquare()).empty().append(HANDLE_TEMPLATE);
		}

		// Promotion move -> change the type of the promoted piece.
		if(moveDescriptor.type() === RPBChess.movetype.PROMOTION) {
			movingPiece.removeClass('uichess-chessboard-piece-p').addClass('uichess-chessboard-piece-' + moveDescriptor.promotion());
		}

		// Switch the turn flag.
		$('.uichess-chessboard-turnFlag', widget.element).toggleClass('uichess-chessboard-inactiveFlag');

		// Refresh the FEN string coding the position, and trigger the 'move' event.
		widget.options.position = widget._position.fen();
		widget._trigger('move', null, move);
		widget._trigger('change', null, widget.options.position);
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
			}

			// Set the new value.
			var oldValue = this.options[key];
			if(oldValue === value) {
				return;
			}
			this.options[key] = value;

			// Update the widget.
			switch(key) {
				case 'position': refresh(this); this._trigger('change', null, this.options.position); break;
				case 'squareSize': onSquareSizeChanged(this, oldValue, value); break;
				case 'showCoordinates': onShowCoordinatesChanged(this); break;
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
				this.options.position = this._position.fen();
				onTurnChanged(this);

				// Notify the listeners.
				this._trigger('change', null, this.options.position);
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
				this.options.position = this._position.fen();

				// Notify the listeners.
				this._trigger('change', null, this.options.position);
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
				this.options.position = this._position.fen();

				// Notify the listeners.
				this._trigger('change', null, this.options.position);
			}
		},


		/**
		 * Add a square marker.
		 *
		 * @param {string} squareMarker
		 */
		addSquareMarker: function(squareMarker) {
			if(SQUARE_MARKER_TOKEN.test(squareMarker)) {
				this._squareMarkers[RegExp.$2] = RegExp.$1;
				this.options.squareMarkers = flattenMarkerList(this._squareMarkers);
				refresh(this); // TODO: avoid rebuilding the whole widget
			}
		},


		/**
		 * Remove a square marker.
		 *
		 * @param {string} squareMarker
		 */
		removeSquareMarker: function(squareMarker) {
			if(SQUARE_MARKER_TOKEN_NO_COLOR.test(squareMarker)) {
				delete this._squareMarkers[RegExp.$1];
				this.options.squareMarkers = flattenMarkerList(this._squareMarkers);
				refresh(this); // TODO: avoid rebuilding the whole widget
			}
		},


		/**
		 * Add an arrow marker.
		 *
		 * @param {string} arrowMarker
		 */
		addArrowMarker: function(arrowMarker) {
			if(ARROW_MARKER_TOKEN.test(arrowMarker)) {
				this._arrowMarkers[RegExp.$2] = RegExp.$1;
				this.options.arrowMarkers = flattenMarkerList(this._arrowMarkers);
				refresh(this); // TODO: avoid rebuilding the whole widget
			}
		},


		/**
		 * Remove an arrow marker.
		 *
		 * @param {string} arrowMarker
		 */
		removeArrowMarker: function(arrowMarker) {
			if(ARROW_MARKER_TOKEN_NO_COLOR.test(arrowMarker)) {
				delete this._arrowMarkers[RegExp.$1];
				this.options.arrowMarkers = flattenMarkerList(this._arrowMarkers);
				refresh(this); // TODO: avoid rebuilding the whole widget
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
		},


		/**
		 * Called when a spare piece is dropped on a square.
		 *
		 * @param {string} square The name of the square on which the piece is dropped.
		 * @param {{piece: string, color: string}} value The dropped piece.
		 * @param {jQuery} target DOM node representing the targeted square.
		 */
		_doAddSparePiece: function(square, value, target) {

			// Update the internal chess object.
			this._position.square(square, value);

			// Update the DOM tree.
			$('<div class="uichess-chessboard-piece uichess-chessboard-piece-' + value.piece + ' uichess-chessboard-color-' + value.color +
				' uichess-chessboard-size' + this.options.squareSize + '"></div>').appendTo(target.empty());

			// Make the new piece draggable if necessary.
			if(this.options.allowMoves === 'all' || this.options.allowMoves === 'legal') {
				this._makePiecesDraggable(target);
			}

			// Refresh the FEN string coding the position, and trigger the 'add' event.
			this.options.position = this._position.fen();
			this._trigger('add', null, {square: square, piece: value});
			this._trigger('change', null, this.options.position);
		},


		/**
		 * Called when a piece is sent to the trash.
		 *
		 * @param {string} square The name of the square that contains the piece to trash.
		 * @param {jQuery} movingPiece DOM node representing the moving piece.
		 * @param {jQuery} target DOM node representing the trash.
		 */
		_doRemovePiece: function(square, movingPiece, target) {

			// Update the internal chess object.
			this._position.square(square, '-');

			// Update the DOM tree. The moving piece must not be directly deleted in order
			// to complete the drag process.
			target.empty().append(movingPiece);

			// Refresh the FEN string coding the position, and trigger the 'remove' event.
			this.options.position = this._position.fen();
			this._trigger('remove', null, square);
			this._trigger('change', null, this.options.position);
		}

	}); /* $.widget('uichess.chessboard', { ... }) */

})( /* global RPBChess */ RPBChess, /* global jQuery */ jQuery );
