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
 * Tools to create chessboard widgets in HTML pages.
 *
 * @author Yoann Le Montagner
 *
 * @requires chess.js {@link https://github.com/jhlywa/chess.js}
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires jQuery UI Selectable
 * @requires jQuery UI Draggable
 * @requires jQuery UI Droppable
 */
(function(Chess, $)
{
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
	 * Ensure that the given string is trimmed.
	 *
	 * @param {string} position
	 * @returns {string}
	 */
	function filterOptionPosition(position)
	{
		return position.replace(/^\s+|\s+$/g, '');
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
	 * Ensure that the given string is a valid value for the `allowMoves` option.
	 *
	 * @param {string} allowMoves
	 * @returns {string}
	 */
	function filterOptionAllowMoves(allowMoves)
	{
		return (allowMoves=='all' || allowMoves=='legal') ? allowMoves : 'none';
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
			this.options.position   = filterOptionPosition  (this.options.position  );
			this.options.squareSize = filterOptionSquareSize(this.options.squareSize);
			this.options.allowMoves = filterOptionAllowMoves(this.options.allowMoves);
			this._refresh();
		},


		/**
		 * Destructor.
		 */
		_destroy: function()
		{
			this.element.removeClass('uichess-chessboard').enableSelection();
		},


		/**
		 * Option setter.
		 */
		_setOption: function(key, value)
		{
			if(key=='position') {
				value = filterOptionPosition(value);
				this._position = null; // The FEN needs to be re-parsed.
			}

			else if(key=='squareSize') {
				value = filterOptionSquareSize(value);
			}

			else if(key=='allowMoves') {
				value = filterOptionAllowMoves(value);
			}

			this.options[key] = value;
			this._refresh();
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
				if(obj._initialGeometryInfo==null) {
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
				if(newSquareSize!=obj.options.squareSize) {
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
			// Parse the FEN-formatted position string, if necessary.
			if(this._position==null) {
				var fen = this.options.position;
				switch(fen) {
					case 'start': fen='rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'; break;
					case 'empty': fen='8/8/8/8/8/8/8/8 w - - 0 1'; break;
				}
				this._position = new Chess(fen);
			}

			// Square colors
			var SQUARE_COLOR = { light: '#f0dec7', dark: '#b5876b' };

			// Rows, columns
			var ROWS    = this.options.flip ? ['1','2','3','4','5','6','7','8'] : ['8','7','6','5','4','3','2','1'];
			var COLUMNS = this.options.flip ? ['h','g','f','e','d','c','b','a'] : ['a','b','c','d','e','f','g','h'];

			// Offset for image alignment
			var SQUARE_SIZE  = this.options.squareSize;
			var OFFSET_PIECE = { b:0, k:SQUARE_SIZE, n:2*SQUARE_SIZE, p:3*SQUARE_SIZE, q:4*SQUARE_SIZE, r:5*SQUARE_SIZE, x:6*SQUARE_SIZE };
			var OFFSET_COLOR = { b:0, w:SQUARE_SIZE };

			// Spare pieces (per column)
			var SPARE_PIECES = ['','p','n','b','r','q','k',''];

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
					if(SPARE_PIECES[c] != '') {
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
							'background-color: ' + SQUARE_COLOR[this._position.square_color(sq)] + ';' +
						'">';
					if(cp!=null) {
						content +=
							'<div class="uichess-chessboard-piece uichess-chessboard-sprite' + SQUARE_SIZE + '" style="' +
								'background-position: -' + OFFSET_PIECE[cp.type] + 'px -' + OFFSET_COLOR[cp.color] + 'px;' +
							'"></div>';
					}
					content += '</div>';
				}

				// Add an additional cell at the end of the row: this last column will contain the turn flag, if necessary.
				content += '<div class="uichess-chessboard-cell">';
				if(ROWS[r]=='8' || ROWS[r]=='1') {
					var style = 'background-position: -' + OFFSET_PIECE['x'] + 'px -' + OFFSET_COLOR[ROWS[r]=='8' ? 'b' : 'w'] + 'px;';
					var clazz = 'uichess-chessboard-turnFlag uichess-chessboard-sprite' + SQUARE_SIZE;
					var turn  = this._position.turn();
					if((ROWS[r]=='8' && turn=='w') || (ROWS[r]=='1' && turn=='b')) {
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
					if(SPARE_PIECES[c] != '') {
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

			// Enable the drag & drop feature if necessary.
			if(this.options.allowMoves=='all' || this.options.allowMoves=='legal') {
				this._tagSquares();
				this._makePiecesDraggable();
				this._makeSquareDroppable();
			}
		},


		/**
		 * Tag each square of the chessboard with its name (for instance: 'e4').
		 * The name of the square is then available through:
		 *
		 *   $(e).data('square');
		 *
		 * Where `e` is a DOM object with the class `uichess-chessboard-cell`.
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
				if(c==8) {
					c = 0;
					++r;
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
			return $('.uichess-chessboard-square', this.element).filter(function(index)
			{
				return $(this).data('square')==square;
			});
		},


		/**
		 * Make the squares of the board acceptable targets for pieces and spare pieces.
		 */
		_makeSquareDroppable: function()
		{
			var obj = this;
			$('.uichess-chessboard-square', this.element).droppable({
				hoverClass: 'uichess-chessboard-squareHover',
				accept: function(e)
				{
					return $(e).closest('.uichess-chessboard-table').get(0)==$('.uichess-chessboard-table', obj.element).get(0);
				},
				drop: function(event, ui)
				{
					var target      = $(event.target);
					var movingPiece = ui.draggable;
					var move        = { from: movingPiece.parent().data('square'), to: target.data('square') };
					if(move.from==move.to) {
						return;
					}
					obj._dragDropCallback(move, movingPiece, target);
				}
			});
		},


		/**
		 * Make the pieces on the board draggable.
		 */
		_makePiecesDraggable: function()
		{
			var SQUARE_SIZE = this.options.squareSize;
			$('.uichess-chessboard-piece', this.element).draggable({
				cursor        : 'move',
				cursorAt      : { top: SQUARE_SIZE/2, left: SQUARE_SIZE/2 },
				revert        : true,
				revertDuration: 0
			});
		},


		/**
		 * Called when a piece is dropped on a square.
		 *
		 * @param {{from: string, to: string}} move The origin and destination squares.
		 * @param {jQuery} movingPiece DOM node representing the moving piece.
		 * @param {jQuery} target DOM node representing the destination square.
		 */
		_dragDropCallback: function(move, movingPiece, target)
		{
			// "All moves" mode -> move the moving piece to its destination square,
			// clearing the latter beforehand if necessary.
			if(this.options.allowMoves=='all') {
				this._position.put(this._position.remove(move.from), move.to);
				target.empty().append(movingPiece);
			}

			// "Legal moves" mode -> check if the proposed move is legal, and handle
			// the special situations (promotion, castle, en-passant...) that may be encountered.
			else if(this.options.allowMoves=='legal') {
				var newMove = this._position.move(move);
				if(newMove==null) {
					move.promotion = 'q'; // TODO: allow other types of promoted pieces.
					newMove = this._position.move(move);
					if(newMove==null) {
						return;
					}
				}
				move = newMove;

				// Move the moving piece to its destination square.
				target.empty().append(movingPiece);

				// Castling move -> move the rook.
				if(move.flags.contains('k') || move.flags.contains('q')) {
					var row   = move.color=='w' ? '1' : '8';
					var rookFrom = this._fetchSquare((move.flags.contains('k') ? 'h' : 'a') + row);
					var rookTo   = this._fetchSquare((move.flags.contains('k') ? 'f' : 'd') + row);
					rookTo.empty().append($('.uichess-chessboard-piece', rookFrom));
				}

				// En-passant move -> remove the taken pawn.
				if(move.flags.contains('e')) {
					this._fetchSquare(move.to[0] + move.from[1]).empty();
				}

				// Promotion move -> change the type of the promoted piece.
				if(move.flags.contains('p')) {
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
		}

	}); /* End of $.widget('uichess.chessboard', ... ) */


	/**
	 * Public constants.
	 */
	$.chessboard =
	{
		MINIMUM_SQUARE_SIZE: MINIMUM_SQUARE_SIZE,
		MAXIMUM_SQUARE_SIZE: MAXIMUM_SQUARE_SIZE
	};

})(Chess, jQuery);
