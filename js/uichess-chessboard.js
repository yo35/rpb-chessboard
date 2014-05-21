/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * @author Yoann Le Montagner
 *
 * @requires chess.js {@link https://github.com/jhlywa/chess.js}
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires jQuery UI Selectable
 * @requires jQuery UI Draggable (optional, only if the moveable piece feature is enabled)
 * @requires jQuery UI Droppable (optional, only if the moveable piece feature is enabled)
 */
(function(Chess, $)
{
	'use strict';


	/**
	 * Minimal value for the square-size parameter.
	 *
	 * @constant
	 * @public
	 */
	var MINIMUM_SQUARE_SIZE = 20;


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
			position: '8/8/8/8/8/8/8/8 w - - 0 1',

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
		 * @type {Chess}
		 */
		_position: null,


		/**
		 * Constructor.
		 */
		_create: function()
		{
			this.element.addClass('uichess-chessboard').disableSelection();
			this.options.position   = this._initializePosition(this.options.position);
			this.options.squareSize = filterOptionSquareSize(this.options.squareSize);
			this.options.allowMoves = filterOptionAllowMoves(this.options.allowMoves);
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
		 * Initialize the internal `Chess` object with the given FEN string.
		 *
		 * @returns {string}
		 */
		_initializePosition: function(fen)
		{
			// Trim the input.
			fen = fen.replace(/^\s+|\s+$/g, '');

			// Convert the special input values into FEN.
			switch(fen) {
				case 'start': fen='rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'; break;
				case 'empty': fen='8/8/8/8/8/8/8/8 w - - 0 1'; break;
			}

			// Parse the FEN string.
			this._position = new Chess(fen);
			fen = this._position.fen();

			// Return the validated FEN string.
			return fen;
		},


		/**
		 * Get or set the turn flag.
		 *
		 * @param {string} turn 'w' or 'b' (or nothing to get the current value).
		 * @returns {undefined|string}
		 */
		turn: function(turn)
		{
			// No value passed, act as a getter.
			if(turn===undefined) {
				return this._position.turn();
			}

			// Otherwise, act as a setter.
			else {
				if(turn !== 'w' && turn !== 'b') {
					return;
				}

				// Compose the new FEN string.
				var fields = this.options.position.split(/\s+/);
				if(fields[1] === turn) {
					return;
				}
				fields[1] = turn;
				if(fields[3].length === 2) { // update the "en-passant" field if necessary
					fields[3] = fields[3].charAt(0) + (turn === 'w' ? '6' : '3');
				}
				var newFen = fields.join(' ');

				// Update the widget.
				$('.uichess-chessboard-turnFlag', this.element).toggleClass('uichess-chessboard-inactiveFlag');
				this._position = new Chess(newFen);
				this.options.position = this._position.fen();
				this._trigger('change', null, this.options.position);
			}
		},


		/**
		 * Get or set the castle right flags.
		 *
		 * @param {string} castleRights 'K', 'Q', 'k', 'q', or a combination of these flags (or nothing to get the current value).
		 * @returns {undefined|string}
		 */
		castleRights: function(castleRights)
		{
			var fields = this.options.position.split(/\s+/);
			var currentCastleRights = fields[2] === '-' ? '' : fields[2];

			// No value passed, act as a getter.
			if(castleRights===undefined) {
				return currentCastleRights;
			}

			// Otherwise, act as a setter.
			else {
				if(castleRights === currentCastleRights || !castleRights.match(/^K?Q?k?q?$/)) {
					return;
				}

				// Compose the new FEN string.
				fields[2] = castleRights === '' ? '-' : castleRights;
				var newFen = fields.join(' ');

				// Update the widget.
				this._position = new Chess(newFen);
				this.options.position = this._position.fen();
				this._trigger('change', null, this.options.position);
			}
		},


		/**
		 * Get or set the "en-passant" flag.
		 *
		 * @param {string} enPassant 'a', 'b', ... , 'h', or '' (or nothing to get the current value).
		 * @returns {undefined|string}
		 */
		enPassant: function(enPassant)
		{
			var fields = this.options.position.split(/\s+/);
			var currentEnPassant = fields[3].length === 2 ? fields[3].charAt(0) : '';

			// No value passed, act as a getter.
			if(enPassant===undefined) {
				return currentEnPassant;
			}

			// Otherwise, act as a setter.
			else {
				if(enPassant === currentEnPassant || !currentEnPassant.match(/^[a-h]?$/)) {
					return;
				}

				// Compose the new FEN string.
				fields[3] = enPassant === '' ? '-' : (enPassant + (fields[1] === 'w' ? '6' : '3'));
				var newFen = fields.join(' ');

				// Update the widget.
				this._position = new Chess(newFen);
				this.options.position = this._position.fen();
				this._trigger('change', null, this.options.position);
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
		 * Refresh the widget.
		 */
		_refresh: function()
		{
			// Square colors
			var SQUARE_COLOR = { light: '#f0dec7', dark: '#b5876b' };

			// Rows, columns
			var ROWS    = this.options.flip ? ['1','2','3','4','5','6','7','8'] : ['8','7','6','5','4','3','2','1'];
			var COLUMNS = this.options.flip ? ['h','g','f','e','d','c','b','a'] : ['a','b','c','d','e','f','g','h'];

			// Offset for image alignment
			var SQUARE_SIZE  = this.options.squareSize;
			var OFFSET_PIECE = { b:0, k:SQUARE_SIZE, n:2*SQUARE_SIZE, p:3*SQUARE_SIZE, q:4*SQUARE_SIZE, r:5*SQUARE_SIZE, x:6*SQUARE_SIZE };
			var OFFSET_COLOR = { b:0, w:SQUARE_SIZE, x:2*SQUARE_SIZE };

			// Spare pieces (per column)
			var SPARE_PIECES = ['p','n','b','r','q','k','','0'];

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
						content +=
							'<div class="uichess-chessboard-trash uichess-chessboard-sprite' + SQUARE_SIZE + '" style="' +
								'background-position: -' + (SPARE_PIECES[c] * SQUARE_SIZE) + 'px -' + OFFSET_COLOR['x'] + 'px;' +
							'"></div>';
					}
					else if(SPARE_PIECES[c].match(/^[bknpqr]$/)) {
						content +=
							'<div class="uichess-chessboard-sparePiece uichess-chessboard-sprite' + SQUARE_SIZE + '" style="' +
								'background-position: -' + OFFSET_PIECE[SPARE_PIECES[c]] + 'px -' + OFFSET_COLOR[color] + 'px;' +
							'"></div>';
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
					var cp = this._position.get(sq);
					content +=
						'<div class="uichess-chessboard-cell uichess-chessboard-square" style="' +
							'width: ' + SQUARE_SIZE + 'px; height: ' + SQUARE_SIZE + 'px; ' +
							'background-color: ' + SQUARE_COLOR[this._position.square_color(sq)] + ';' + /* jshint ignore:line */
						'">';
					if(cp !== null) {
						content +=
							'<div class="uichess-chessboard-piece uichess-chessboard-sprite' + SQUARE_SIZE + '" style="' +
								'background-position: -' + OFFSET_PIECE[cp.type] + 'px -' + OFFSET_COLOR[cp.color] + 'px;' +
							'"></div>';
					}
					content += '</div>';
				}

				// Add an additional cell at the end of the row: this last column will contain the turn flag, if necessary.
				content += '<div class="uichess-chessboard-cell">';
				if(ROWS[r] === '8' || ROWS[r] === '1') {
					var style = 'background-position: -' + OFFSET_PIECE['x'] + 'px -' + OFFSET_COLOR[ROWS[r] === '8' ? 'b' : 'w'] + 'px;';
					var clazz = 'uichess-chessboard-turnFlag uichess-chessboard-sprite' + SQUARE_SIZE;
					var turn  = this._position.turn();
					if((ROWS[r] === '8' && turn === 'w') || (ROWS[r] === '1' && turn === 'b')) {
						clazz += ' uichess-chessboard-inactiveFlag';
					}
					content += '<div class="' + clazz + '" style="' + style + '"></div>';
				}

				// End of the additional cell and end of the row.
				content += '</div></div>';
			}


			//////////////////////////////////////////////////////////////////////////
			// If visible, the column coordinates are shown at the bottom of the table.
			//////////////////////////////////////////////////////////////////////////
			if(this.options.showCoordinates) {
				content += '<div class="uichess-chessboard-row">';

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
						content +=
							'<div class="uichess-chessboard-trash uichess-chessboard-sprite' + SQUARE_SIZE + '" style="' +
								'background-position: -' + (SPARE_PIECES[c] * SQUARE_SIZE) + 'px -' + OFFSET_COLOR['x'] + 'px;' +
							'"></div>';
					}
					else if(SPARE_PIECES[c].match(/^[bknpqr]$/)) {
						content +=
							'<div class="uichess-chessboard-sparePiece uichess-chessboard-sprite' + SQUARE_SIZE + '" style="' +
								'background-position: -' + OFFSET_PIECE[SPARE_PIECES[c]] + 'px -' + OFFSET_COLOR[color] + 'px;' +
							'"></div>';
					}
					content += '</div>';
				}

				// Empty cell (below the turn flag) + end of the row.
				content += '<div class="uichess-chessboard-cell"></div></div>';
			}


			//////////////////////////////////////////////////////////////////////////
			// End of the table
			//////////////////////////////////////////////////////////////////////////

			// Close the "table" node.
			content += '</div>';

			// Clear the target node and render its content.
			this.element.empty();
			$(content).appendTo(this.element);

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
			var ROWS    = this.options.flip ? ['1','2','3','4','5','6','7','8'] : ['8','7','6','5','4','3','2','1'];
			var COLUMNS = this.options.flip ? ['h','g','f','e','d','c','b','a'] : ['a','b','c','d','e','f','g','h'];
			var r = 0;
			var c = 0;
			$('.uichess-chessboard-square', this.element).each(function()
			{
				$(this).data('square', COLUMNS[c] + ROWS[r]);
				++c;
				if(c === 8) {
					c = 0;
					++r;
				}
			});
		},


		/**
		 * Tag each spare piece of the chessboard with its name (for instance: `{type: 'k', color: 'b'}`).
		 * The name of the piece is then available through:
		 *
		 *   $(e).data('piece');
		 *
		 * Where `e` is a DOM object with the class `uichess-chessboard-sparePiece`.
		 */
		_tagSparePieces: function()
		{
			var TYPES  = ['p','n','b','r','q','k'];
			var COLORS = this.options.flip ? ['w','b'] : ['b','w'];
			var t = 0;
			var c = 0;
			$('.uichess-chessboard-sparePiece', this.element).each(function()
			{
				$(this).data('piece', { type: TYPES[t], color: COLORS[c] });
				++t;
				if(t === 6) {
					t = 0;
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
			return $('.uichess-chessboard-square', this.element).filter(function()
			{
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
						var piece = ui.draggable.data('piece');
						obj._doAddSparePiece(target.data('square'), {type: piece.type, color: piece.color}, target);
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
			var SQUARE_SIZE = this.options.squareSize;
			$('.uichess-chessboard-piece', target===undefined ? this.element : target).draggable({
				cursor        : 'move',
				cursorAt      : { top: SQUARE_SIZE/2, left: SQUARE_SIZE/2 },
				revert        : true,
				revertDuration: 0,
				zIndex        : 100
			});
		},


		/**
		 * Make the spare pieces draggable.
		 *
		 * @param {jQuery} [target=this.element] Only the children of `target` are affected.
		 */
		_makeSparePiecesDraggable: function(target)
		{
			var SQUARE_SIZE = this.options.squareSize;
			$('.uichess-chessboard-sparePiece', target===undefined ? this.element : target).draggable({
				cursor        : 'move',
				cursorAt      : { top: SQUARE_SIZE/2, left: SQUARE_SIZE/2 },
				helper        : 'clone',
				revert        : true,
				revertDuration: 0,
				zIndex        : 100
			});
		},


		/**
		 * Called when a piece is dropped on a square.
		 *
		 * @param {{from: string, to: string}} move The origin and destination squares.
		 * @param {jQuery} movingPiece DOM node representing the moving piece.
		 * @param {jQuery} target DOM node representing the destination square.
		 */
		_doMove: function(move, movingPiece, target)
		{
			// "All moves" mode -> move the moving piece to its destination square,
			// clearing the latter beforehand if necessary.
			if(this.options.allowMoves === 'all') {
				this._position.put(this._position.remove(move.from), move.to);
				target.empty().append(movingPiece);
			}

			// "Legal moves" mode -> check if the proposed move is legal, and handle
			// the special situations (promotion, castle, en-passant...) that may be encountered.
			else if(this.options.allowMoves === 'legal') {
				var newMove = this._position.move(move);
				if(newMove === null) {
					move.promotion = 'q'; // TODO: allow other types of promoted pieces.
					newMove = this._position.move(move);
					if(newMove === null) {
						return;
					}
				}
				move = newMove;

				// Move the moving piece to its destination square.
				target.empty().append(movingPiece);

				// Castling move -> move the rook.
				if(move.flags.indexOf('k') >= 0 || move.flags.indexOf('q') >= 0) {
					var row   = move.color === 'w' ? '1' : '8';
					var rookFrom = this._fetchSquare((move.flags.indexOf('k') >= 0 ? 'h' : 'a') + row);
					var rookTo   = this._fetchSquare((move.flags.indexOf('k') >= 0 ? 'f' : 'd') + row);
					rookTo.empty().append($('.uichess-chessboard-piece', rookFrom));
				}

				// En-passant move -> remove the taken pawn.
				if(move.flags.indexOf('e') >= 0) {
					this._fetchSquare(move.to[0] + move.from[1]).empty();
				}

				// Promotion move -> change the type of the promoted piece.
				if(move.flags.indexOf('p') >= 0) {
					var SQUARE_SIZE  = this.options.squareSize;
					var OFFSET_PIECE = { b:0, k:SQUARE_SIZE, n:2*SQUARE_SIZE, p:3*SQUARE_SIZE, q:4*SQUARE_SIZE, r:5*SQUARE_SIZE, x:6*SQUARE_SIZE };
					var OFFSET_COLOR = { b:0, w:SQUARE_SIZE };
					movingPiece.css('background-position', '-' + OFFSET_PIECE[move.promotion] + 'px -' + OFFSET_COLOR[move.color] + 'px');
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
		 * @param {{type: string, color: string}} piece The dropped piece.
		 * @param {jQuery} target DOM node representing the targeted square.
		 */
		_doAddSparePiece: function(square, piece, target)
		{
			// Update the internal chess object.
			this._position.put(piece, square);

			// Update the DOM tree.
			var SQUARE_SIZE  = this.options.squareSize;
			var OFFSET_PIECE = { b:0, k:SQUARE_SIZE, n:2*SQUARE_SIZE, p:3*SQUARE_SIZE, q:4*SQUARE_SIZE, r:5*SQUARE_SIZE, x:6*SQUARE_SIZE };
			var OFFSET_COLOR = { b:0, w:SQUARE_SIZE };
			$(
				'<div class="uichess-chessboard-piece uichess-chessboard-sprite' + SQUARE_SIZE + '" style="' +
					'background-position: -' + OFFSET_PIECE[piece.type] + 'px -' + OFFSET_COLOR[piece.color] + 'px;' +
				'"></div>'
			).appendTo(target.empty());

			// Make the new piece draggable if necessary.
			if(this.options.allowMoves === 'all' || this.options.allowMoves === 'legal') {
				this._makePiecesDraggable(target);
			}

			// Refresh the FEN string coding the position, and trigger the 'add' event.
			this.options.position = this._position.fen();
			this._trigger('add', null, {square: square, piece: piece});
			this._trigger('change', null, this.options.position);
		},


		/**
		 * Called when a piece is sent to the trash.
		 *
		 * @param {string} square The name of the square that contains the piece to trash.
		 * @param {jQuery} movingPiece DOM node representing the moving piece.
		 * @param {jQuery} target DOM node representing the trash.
		 */
		_doRemovePiece: function(square, movingPiece, target)
		{
			// Update the internal chess object.
			this._position.remove(square);

			// Update the DOM tree. The moving piece must not be directly deleted in order
			// to complete the drag process.
			target.empty().append(movingPiece);

			// Refresh the FEN string coding the position, and trigger the 'remove' event.
			this.options.position = this._position.fen();
			this._trigger('remove', null, square);
			this._trigger('change', null, this.options.position);
		}

	}); /* $.widget('uichess.chessboard', { ... }) */

})( /* global Chess */ Chess, /* global jQuery */ jQuery );
