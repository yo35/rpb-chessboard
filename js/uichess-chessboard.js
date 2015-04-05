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
	 * Public static properties.
	 */
	$.chessboard =
	{
		MINIMUM_SQUARE_SIZE: MINIMUM_SQUARE_SIZE,
		MAXIMUM_SQUARE_SIZE: MAXIMUM_SQUARE_SIZE
	};


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
	 * Ensure that the given string is a valid value for the `allowMoves` option.
	 *
	 * @param {string} allowMoves
	 * @returns {string}
	 */
	function filterOptionAllowMoves(allowMoves)
	{
		return (allowMoves === 'all' || allowMoves === 'legal') ? allowMoves : 'none';
	}


	/**
	 * Register a 'chessboard' widget in the jQuery widget framework.
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
			 * Whether the user can moves the pieces or not, and which type of move is allowed.
			 *
			 * Available values are:
			 * * 'none': no move is allowed, drag & drop is disabled.
			 * * 'all': all moves are allowed, legal or not.
			 * * 'legal': only legal moves are allowed.
			 */
			allowMoves: 'none',

			/**
			 * Spare pieces.
			 */
			sparePieces: false
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
		_squareMarker: null,


		/**
		 * Arrow markers.
		 * @type {object}
		 */
		_arrowMarker: null,


		/**
		 * Constructor.
		 */
		_create: function()
		{
			this.element.addClass('uichess-chessboard').disableSelection();
			this.options.position   = this._initializePosition(this.options.position);
			this.options.squareSize = filterOptionSquareSize(this.options.squareSize);
			this.options.allowMoves = filterOptionAllowMoves(this.options.allowMoves);
			this._squareMarker = {};
			this._arrowMarker = {};
			this._refresh();
		},


		/**
		 * Destructor.
		 */
		_destroy: function()
		{
			this.element.empty().removeClass('uichess-chessboard').enableSelection();
		},


		/**
		 * Option setter.
		 */
		_setOption: function(key, value)
		{
			switch(key) {
				case 'position'  : value = this._initializePosition(value); break;
				case 'squareSize': value = filterOptionSquareSize(value); break;
				case 'allowMoves': value = filterOptionAllowMoves(value); break;
			}

			this.options[key] = value;
			this._refresh();

			if(key === 'position') {
				this._trigger('change', null, this.options.position);
			}
		},


		/**
		 * Initialize the internal `RPBChess.Position` object with the given FEN string.
		 *
		 * @returns {string}
		 */
		_initializePosition: function(fen)
		{
			// Trim the input.
			fen = fen.replace(/^\s+|\s+$/g, '');

			// Parse the FEN string.
			try {
				this._position = new RPBChess.Position(fen);
				fen = this._position.fen();
			}
			catch(e) {
				if(e instanceof RPBChess.exceptions.InvalidFEN) {
					this._position = e;
				}
				else {
					this._position = null;
					throw e;
				}
			}

			// Return the validated FEN string.
			return fen;
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

				// Update the widget.
				$('.uichess-chessboard-turnFlag', this.element).toggleClass('uichess-chessboard-inactiveFlag');

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
		 * Return the square markers currently set.
		 *
		 * @returns {string[]}
		 */
		squareMarkers: function() {
			var res = [];
			for(var sq in this._squareMarker) {
				if(this._squareMarker.hasOwnProperty(sq)) {
					res.push(this._squareMarker[sq] + sq);
				}
			}
		},


		/**
		 * Add a square marker.
		 *
		 * @param {string} squareMarker
		 */
		addSquareMarker: function(squareMarker) {
			if(/^([GRY])([a-h][1-8])$/.test(squareMarker)) {
				this._squareMarker[RegExp.$2] = RegExp.$1;
				this._refresh(); // TODO: avoid rebuilding the whole widget
			}
		},


		/**
		 * Remove a square marker.
		 *
		 * @param {string} squareMarker
		 */
		removeSquareMarker: function(squareMarker) {
			if(/^[GRY]?([a-h][1-8])$/.test(squareMarker)) {
				delete this._squareMarker[RegExp.$1];
				this._refresh(); // TODO: avoid rebuilding the whole widget
			}
		},


		/**
		 * Remove all the square markers.
		 */
		removeSquareMarkers: function() {
			this._squareMarker = {};
			this._refresh(); // TODO: avoid rebuilding the whole widget
		},


		/**
		 * Return the arrow markers currently set.
		 *
		 * @returns {string[]}
		 */
		arrowMarkers: function() {
			var res = [];
			for(var sq in this._arrowMarker) {
				if(this._arrowMarker.hasOwnProperty(sq)) {
					res.push(this._arrowMarker[sq] + sq);
				}
			}
		},


		/**
		 * Add an arrow marker.
		 *
		 * @param {string} arrowMarker
		 */
		addArrowMarker: function(arrowMarker) {
			if(/^([GRY])([a-h][1-8][a-h][1-8])$/.test(arrowMarker)) {
				this._arrowMarker[RegExp.$2] = RegExp.$1;
				this._refresh(); // TODO: avoid rebuilding the whole widget
			}
		},


		/**
		 * Remove an arrow marker.
		 *
		 * @param {string} arrowMarker
		 */
		removeArrowMarker: function(arrowMarker) {
			if(/^[GRY]?([a-h][1-8][a-h][1-8])$/.test(arrowMarker)) {
				delete this._arrowMarker[RegExp.$1];
				this._refresh(); // TODO: avoid rebuilding the whole widget
			}
		},


		/**
		 * Remove all the arrow markers.
		 */
		removeArrowMarkers: function() {
			this._arrowMarker = {};
			this._refresh(); // TODO: avoid rebuilding the whole widget
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
		sizeControlledByContainer: function(container, eventName)
		{
			var obj = this;
			container.on(eventName, function(event, ui)
			{
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
				newSquareSize = Math.min(Math.max(newSquareSize, MINIMUM_SQUARE_SIZE), MAXIMUM_SQUARE_SIZE);

				// Update the widget if necessary.
				if(newSquareSize !== obj.options.squareSize) {
					obj.options.squareSize = newSquareSize;
					obj._refresh();
				}
			});
		},


		/**
		 * Destroy the widget content, prior to a refresh or a widget destruction.
		 */
		_destroyContent: function() {
			this.element.empty();
		},


		/**
		 * Build the error message resulting from a FEN parsing error.
		 *
		 * @returns {string}
		 */
		_buildErrorMessage: function() {

			// Build the error report box.
			var retVal = '<div class="uichess-chessboard-error">' +
				'<div class="uichess-chessboard-errorTitle">Error while analysing a FEN string.</div>';

			// Optional message.
			if(this._position.message !== null) {
				retVal += '<div class="uichess-chessboard-errorMessage">' + this._position.message + '</div>';
			}

			// Close the error report box, and return the result.
			retVal += '</div>';
			return retVal;
		},


		/**
		 * Refresh the widget.
		 */
		_refresh: function()
		{
			this._destroyContent();
			if(this._position === null) {
				return;
			}

			// Handle parsing error problems.
			if(this._position instanceof RPBChess.exceptions.InvalidFEN) {
				$(this._buildErrorMessage()).appendTo(this.element);
				return;
			}

			// Aliases
			var ROWS         = this.options.flip ? '12345678' : '87654321';
			var COLUMNS      = this.options.flip ? 'hgfedcba' : 'abcdefgh';
			var SQUARE_SIZE  = this.options.squareSize;
			var SPARE_PIECES = 'pnbrqk 0';

			// Open the "table" node.
			var content = '<div class="uichess-chessboard-table">';


			//////////////////////////////////////////////////////////////////////////
			// Spare pieces top row.
			//////////////////////////////////////////////////////////////////////////
			if(this.options.sparePieces) {
				content += '<div class="uichess-chessboard-row uichess-chessboard-sparePiecesTopRow">';

				// Empty cell (above the column of coordinates 1,2,...,8).
				if(this.options.showCoordinates) {
					content += '<div class="uichess-chessboard-cell"></div>';
				}

				// Spare pieces.
				var color = this.options.flip ? 'w' : 'b';
				for(var c=0; c<8; ++c) {
					content += '<div class="uichess-chessboard-cell">';
					if(SPARE_PIECES[c].match(/^[0-9]$/)) {
						content += '<div class="uichess-chessboard-trash uichess-chessboard-size' + SQUARE_SIZE + '"></div>';
					}
					else if(SPARE_PIECES[c].match(/^[bknpqr]$/)) {
						content += '<div class="uichess-chessboard-sparePiece uichess-chessboard-piece-' + SPARE_PIECES[c] +
							' uichess-chessboard-color-' + color + ' uichess-chessboard-size' + SQUARE_SIZE + '"></div>';
					}
					content += '</div>';
				}

				// Empty cell (above the turn flag) + end of the row.
				content += '<div class="uichess-chessboard-cell"></div></div>';
			}


			//////////////////////////////////////////////////////////////////////////
			// For each row...
			//////////////////////////////////////////////////////////////////////////
			for(var r=0; r<8; ++r) {
				content += '<div class="uichess-chessboard-row">';

				// If visible, the row coordinates are shown in the left-most column.
				if(this.options.showCoordinates) {
					content += '<div class="uichess-chessboard-cell uichess-chessboard-rowCoordinate">' + ROWS[r] + '</div>';
				}

				// Print the squares belonging to the current column.
				for(var c=0; c<8; ++c) {
					var sq = COLUMNS[c] + ROWS[r];
					var cp = this._position.square(sq);
					var squareColor = RPBChess.squareColor(sq) === 'w' ? 'light' : 'dark';
					var clazz = 'uichess-chessboard-cell uichess-chessboard-square uichess-chessboard-size' + SQUARE_SIZE +
						' uichess-chessboard-' + squareColor + 'Square';
					if(sq in this._squareMarker) {
						clazz += ' uichess-chessboard-squareMarker uichess-chessboard-markerColor-' + this._squareMarker[sq];
					}
					content += '<div class="' + clazz + '">';
					if(cp !== '-') {
						content += '<div class="uichess-chessboard-piece uichess-chessboard-piece-' + cp.piece +
							' uichess-chessboard-color-' + cp.color + ' uichess-chessboard-size' + SQUARE_SIZE + '">' +
							'<div class="uichess-chessboard-pieceHandle"></div></div>';
					}
					content += '</div>';
				}

				// Add an additional cell at the end of the row: this last column will contain the turn flag, if necessary.
				content += '<div class="uichess-chessboard-cell">';
				if(ROWS[r] === '8' || ROWS[r] === '1') {
					var color = ROWS[r] === '8' ? 'b' : 'w';
					var turn  = this._position.turn();
					content += '<div class="uichess-chessboard-turnFlag uichess-chessboard-color-' + color +
						' uichess-chessboard-size' + SQUARE_SIZE + (color===turn ? '' : ' uichess-chessboard-inactiveFlag') + '"></div>';
				}

				// End of the additional cell and end of the row.
				content += '</div></div>';
			}


			//////////////////////////////////////////////////////////////////////////
			// If visible, the column coordinates are shown at the bottom of the table.
			//////////////////////////////////////////////////////////////////////////
			if(this.options.showCoordinates) {
				content += '<div class="uichess-chessboard-row uichess-chessboard-columnCoordinateRow">';

				// Empty cell (below the column of coordinates 1,2,...,8).
				content += '<div class="uichess-chessboard-cell"></div>';

				// Column headers
				for(var c=0; c<8; ++c) {
					content += '<div class="uichess-chessboard-cell uichess-chessboard-columnCoordinate">' + COLUMNS[c] + '</div>';
				}

				// Empty cell (below the turn flag) + end of the row.
				content += '<div class="uichess-chessboard-cell"></div></div>';
			}


			//////////////////////////////////////////////////////////////////////////
			// Spare pieces bottom row.
			//////////////////////////////////////////////////////////////////////////
			if(this.options.sparePieces) {
				content += '<div class="uichess-chessboard-row uichess-chessboard-sparePiecesBottomRow">';

				// Empty cell (below the column of coordinates 1,2,...,8).
				if(this.options.showCoordinates) {
					content += '<div class="uichess-chessboard-cell"></div>';
				}

				// Spare pieces.
				var color = this.options.flip ? 'b' : 'w';
				for(var c=0; c<8; ++c) {
					content += '<div class="uichess-chessboard-cell">';
					if(SPARE_PIECES[c].match(/^[0-9]$/)) {
						content += '<div class="uichess-chessboard-trash uichess-chessboard-size' + SQUARE_SIZE + '"></div>';
					}
					else if(SPARE_PIECES[c].match(/^[bknpqr]$/)) {
						content += '<div class="uichess-chessboard-sparePiece uichess-chessboard-piece-' + SPARE_PIECES[c] +
							' uichess-chessboard-color-' + color + ' uichess-chessboard-size' + SQUARE_SIZE + '"></div>';
					}
					content += '</div>';
				}

				// Empty cell (below the turn flag) + end of the row.
				content += '<div class="uichess-chessboard-cell"></div></div>';
			}


			//////////////////////////////////////////////////////////////////////////
			// End of the table
			//////////////////////////////////////////////////////////////////////////

			// Arrow markers
			var arrowMarkerFound = false;
			var annotations = '<svg class="uichess-chessboard-annotations" viewBox="0 0 8 8">';
			for(var arrow in this._arrowMarker) {
				if(this._arrowMarker.hasOwnProperty(arrow) && /^([a-h])([1-8])([a-h])([1-8])$/.test(arrow)) {
					arrowMarkerFound = true;
					var x1 = COLUMNS.indexOf(RegExp.$1) + 0.5;
					var y1 = ROWS.indexOf   (RegExp.$2) + 0.5;
					var x2 = COLUMNS.indexOf(RegExp.$3) + 0.5;
					var y2 = ROWS.indexOf   (RegExp.$4) + 0.5;
					var clazz = 'uichess-chessboard-arrowMarker uichess-chessboard-markerColor-' + this._arrowMarker[arrow];
					annotations += '<line class="' + clazz + '" x1="' + x1 + '" y1="' + y1 + '" x2="' + x2 + '" y2="' + y2 + '" />';
				}
			}
			annotations += '</svg>';
			if(arrowMarkerFound) {
				content += annotations;
			}

			// Close the "table" node.
			content += '</div>';

			// Render the content.
			$(content).appendTo(this.element);

			// Adjust the position of the annotation layer.
			if(arrowMarkerFound) {
				var annotationLayer = $('.uichess-chessboard-annotations', this.element);
				var firstSquare = $('.uichess-chessboard-square', this.element).first();
				annotationLayer.offset(firstSquare.offset());
				annotationLayer.width(firstSquare.width() * 8);
				annotationLayer.height(firstSquare.height() * 8);
			}

			// Enable the drag & drops feature if necessary.
			var draggablePieces = this.options.allowMoves === 'all' || this.options.allowMoves === 'legal';
			var sparePieces     = this.options.sparePieces;
			if(draggablePieces || sparePieces) {
				this._tagSquares();
				this._makeSquareDroppable();
			}
			if(draggablePieces) {
				this._makePiecesDraggable();
			}
			if(sparePieces) {
				this._tagSparePieces();
				this._makeSparePiecesDraggable();
				this._makeTrashDroppable();
			}
		},


		/**
		 * Tag each square of the chessboard with its name (for instance: 'e4').
		 * The name of the square is then available through:
		 *
		 *   $(e).data('square');
		 *
		 * Where `e` is a DOM object with the class `uichess-chessboard-square`.
		 */
		_tagSquares: function()
		{
			var ROWS    = this.options.flip ? '12345678' : '87654321';
			var COLUMNS = this.options.flip ? 'hgfedcba' : 'abcdefgh';
			var r = 0;
			var c = 0;
			$('.uichess-chessboard-square', this.element).each(function() {
				$(this).data('square', COLUMNS[c] + ROWS[r]);
				++c;
				if(c === 8) {
					c = 0;
					++r;
				}
			});
		},


		/**
		 * Tag each spare piece of the chessboard with its name (for instance: `{piece: 'k', color: 'b'}`).
		 * The name of the piece is then available through:
		 *
		 *   $(e).data('piece');
		 *
		 * Where `e` is a DOM object with the class `uichess-chessboard-sparePiece`.
		 */
		_tagSparePieces: function() {
			var PIECES = 'pnbrqk';
			var COLORS = this.options.flip ? 'wb' : 'bw';
			var p = 0;
			var c = 0;
			$('.uichess-chessboard-sparePiece', this.element).each(function() {
				$(this).data('piece', { piece: PIECES[p], color: COLORS[c] });
				++p;
				if(p === 6) {
					p = 0;
					++c;
				}
			});
		},


		/**
		 * Fetch the DOM node corresponding to a given square.
		 *
		 * @param {string} square
		 * @returns {jQuery}
		 */
		_fetchSquare: function(square)
		{
			return $('.uichess-chessboard-square', this.element).filter(function() {
				return $(this).data('square') === square;
			});
		},


		/**
		 * Make the squares of the board acceptable targets for pieces and spare pieces.
		 */
		_makeSquareDroppable: function()
		{
			var obj = this;
			var tableNode = $('.uichess-chessboard-table', this.element).get(0);
			$('.uichess-chessboard-square', this.element).droppable({
				hoverClass: 'uichess-chessboard-squareHover',
				accept: function(e)
				{
					return $(e).closest('.uichess-chessboard-table').get(0) === tableNode;
				},
				drop: function(event, ui)
				{
					var target      = $(event.target);
					var movingPiece = ui.draggable;

					// The draggable is a spare piece.
					if(movingPiece.hasClass('uichess-chessboard-sparePiece')) {
						var value = ui.draggable.data('piece');
						obj._doAddSparePiece(target.data('square'), {piece: value.piece, color: value.color}, target);
					}

					// The draggable is a piece from the board.
					if(movingPiece.hasClass('uichess-chessboard-piece')) {
						var move = { from: movingPiece.parent().data('square'), to: target.data('square') };
						if(move.from !== move.to) {
							obj._doMove(move, movingPiece, target);
						}
					}
				}
			});
		},


		/**
		 * Make the trash icons acceptable targets for pieces.
		 */
		_makeTrashDroppable: function()
		{
			var obj = this;
			var tableNode = $('.uichess-chessboard-table', this.element).get(0);
			$('.uichess-chessboard-trash', this.element).droppable({
				hoverClass: 'uichess-chessboard-trashHover',
				accept: function(e)
				{
					return $(e).hasClass('uichess-chessboard-piece') && $(e).closest('.uichess-chessboard-table').get(0) === tableNode;
				},
				drop: function(event, ui)
				{
					obj._doRemovePiece(ui.draggable.parent().data('square'), ui.draggable, $(event.target));
				}
			});
		},


		/**
		 * Make the pieces on the board draggable.
		 *
		 * @param {jQuery} [target=this.element] Only the children of `target` are affected.
		 */
		_makePiecesDraggable: function(target)
		{
			$('.uichess-chessboard-piece', target===undefined ? this.element : target).draggable({
				cursor        : 'move',
				cursorAt      : { top: this.options.squareSize/2, left: this.options.squareSize/2 },
				revert        : true,
				revertDuration: 0,
				zIndex        : 300
			});
		},


		/**
		 * Make the spare pieces draggable.
		 *
		 * @param {jQuery} [target=this.element] Only the children of `target` are affected.
		 */
		_makeSparePiecesDraggable: function(target)
		{
			$('.uichess-chessboard-sparePiece', target===undefined ? this.element : target).draggable({
				cursor        : 'move',
				cursorAt      : { top: this.options.squareSize/2, left: this.options.squareSize/2 },
				helper        : 'clone',
				revert        : true,
				revertDuration: 0,
				zIndex        : 300
			});
		},


		/**
		 * Called when a piece is dropped on a square.
		 *
		 * @param {{from: string, to: string}} move The origin and destination squares.
		 * @param {jQuery} movingPiece DOM node representing the moving piece.
		 * @param {jQuery} target DOM node representing the destination square.
		 */
		_doMove: function(move, movingPiece, target) {

			// "All moves" mode -> move the moving piece to its destination square,
			// clearing the latter beforehand if necessary.
			if(this.options.allowMoves === 'all') {
				this._position.square(move.to, this._position.square(move.from));
				this._position.square(move.from, '-');
				target.empty().append(movingPiece);
			}

			// "Legal moves" mode -> check if the proposed move is legal, and handle
			// the special situations (promotion, castle, en-passant...) that may be encountered.
			else if(this.options.allowMoves === 'legal') {
				var moveDescriptor = this._position.isMoveLegal(move);
				if(moveDescriptor === false) {
					move.promotion = 'q'; // TODO: allow other types of promoted pieces.
					moveDescriptor = this._position.isMoveLegal(move);
					if(moveDescriptor === false) {
						return;
					}
				}
				this._position.play(moveDescriptor);

				// Move the moving piece to its destination square.
				target.empty().append(movingPiece);

				// Castling move -> move the rook.
				if(moveDescriptor.type() === RPBChess.movetype.CASTLING_MOVE) {
					var rookFrom = this._fetchSquare(moveDescriptor.rookFrom());
					var rookTo   = this._fetchSquare(moveDescriptor.rookTo());
					rookTo.empty().append($('.uichess-chessboard-piece', rookFrom));
				}

				// En-passant move -> remove the taken pawn.
				if(moveDescriptor.type() === RPBChess.movetype.EN_PASSANT_CAPTURE) {
					this._fetchSquare(moveDescriptor.enPassantSquare()).empty();
				}

				// Promotion move -> change the type of the promoted piece.
				if(moveDescriptor.type() === RPBChess.movetype.PROMOTION) {
					movingPiece.removeClass('uichess-chessboard-piece-p').addClass('uichess-chessboard-piece-' + moveDescriptor.promotion());
				}

				// Switch the turn flag.
				$('.uichess-chessboard-turnFlag', this.element).toggleClass('uichess-chessboard-inactiveFlag');
			}

			// Refresh the FEN string coding the position, and trigger the 'move' event.
			this.options.position = this._position.fen();
			this._trigger('move', null, move);
			this._trigger('change', null, this.options.position);
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
