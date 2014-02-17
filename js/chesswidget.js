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
	 * Root URL of the library.
	 *
	 * Use the method `rootURL` to get the root URL of the library,
	 * instead of reading this variable directly.
	 *
	 * @type {string}
	 * @private
	 */
	var _rootURL = null;


	/**
	 * Return the root URL of the library.
	 *
	 * @returns {string}
	 */
	function rootURL()
	{
		if(_rootURL==null) {
			_rootURL = "";
			$("script").each(function(index, e)
			{
				var src = $(e).attr("src");
				if(src!=null && src.match(/^(.*\/)chesswidget.js(?:\?.*)?$/)) {
					_rootURL = RegExp.$1;
				}
			});
		}
		return _rootURL;
	}


	/**
	 * Return the URL to the image containing the sprites for the given square size.
	 *
	 * @param {number} squareSize
	 * @returns {string}
	 */
	function spriteURL(squareSize)
	{
		return rootURL() + 'sprite/all-' + squareSize + '.png';
	}


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
			showCoordinates: true
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
					case 'empty': fen='8/8/8/8/8/8/9/8 w - - 0 1'; break;
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
			var SPRITE_URL   = spriteURL(SQUARE_SIZE);
			var OFFSET_PIECE = { b:0, k:SQUARE_SIZE, n:2*SQUARE_SIZE, p:3*SQUARE_SIZE, q:4*SQUARE_SIZE, r:5*SQUARE_SIZE, x:6*SQUARE_SIZE };
			var OFFSET_COLOR = { b:0, w:SQUARE_SIZE };

			// Open the "table" node.
			var content = '<div class="uichess-chessboard-table">';

			// For each row...
			for(var r=0; r<8; ++r) {
				content += '<div class="uichess-chessboard-row">';

				// If visible, the row coordinates are shown in the left-most column.
				if(this.options.showCoordinates) {
					content += '<div class="uichess-chessboard-rowHeader">' + ROWS[r] + '</div>';
				}

				// Print the squares belonging to the current column.
				for(var c=0; c<8; ++c) {
					var sq = COLUMNS[c] + ROWS[r];
					var cp = this._position.get(sq);
					var clazz = 'uichess-chessboard-cell';
					var style =
						'width: ' + SQUARE_SIZE + 'px; height: ' + SQUARE_SIZE + 'px; ' +
						'background-color: ' + SQUARE_COLOR[this._position.square_color(sq)] + ';';
					if(cp!=null) {
						clazz += ' uichess-chessboard-piece';
						style +=
							' background-image: url(' + SPRITE_URL + ');' +
							' background-position: -' + OFFSET_PIECE[cp.type] + 'px -' + OFFSET_COLOR[cp.color] + 'px;';
					}
					content += '<div class="' + clazz + '" style="' + style + '"></div>';
				}

				// Add a "fake" cell at the end of the row: this last column will contain the turn flag, if necessary.
				content += '<div class="uichess-chessboard-turnCell">';
				var turn = this._position.turn();
				if((ROWS[r]=='8' && turn=='b') || (ROWS[r]=='1' && turn=='w')) {
					content +=
						'<div class="uichess-chessboard-turnFlag" style="' +
							'width: ' + SQUARE_SIZE + 'px; height: ' + SQUARE_SIZE + 'px; ' +
							'background-image: url(' + SPRITE_URL + '); ' +
							'background-position: -' + OFFSET_PIECE['x'] + 'px -' + OFFSET_COLOR[turn] + 'px;' +
						'"></div>';
				}

				// End of the "fake" cell and end of the row.
				content += '</div></div>';
			}

			// If visible, the column coordinates are shown at the bottom of the table.
			if(this.options.showCoordinates) {
				content += '<div class="uichess-chessboard-lastRow">';

				// Empty cell
				content += '<div class="uichess-chessboard-cornerHeader"></div>';

				// Column headers
				for(var c=0; c<8; ++c) {
					content += '<div class="uichess-chessboard-columnHeader">' + COLUMNS[c] + '</div>';
				}

				// Empty cell below the "fake" cell columns + end of the row.
				content += '<div class="uichess-chessboard-turnHeader"></div></div>';
			}

			// Close the "table" node.
			content += '</div>';

			// Clear the target node and render its content.
			this.element.empty();
			$(content).appendTo(this.element);
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
			$('.uichess-chessboard-cell', this.element).each(function()
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
		 * Make the pieces on the board draggable.
		 */
		_makePiecesDraggable: function()
		{
			// Some constants.
			var SQUARE_SIZE  = this.options.squareSize;
			var SPRITE_URL   = spriteURL(SQUARE_SIZE);

			// The pieces must be contained in the area occupied by the chessboard squares.
			var posTopLeft     = $('.uichess-chessboard-cell:first', this.element).position();
			var posBottomRight = $('.uichess-chessboard-cell:last' , this.element).position();
			var draggableArea  = [posTopLeft.left, posTopLeft.top, posBottomRight.left, posBottomRight.top];

			// Make each piece draggable.
			$('.uichess-chessboard-piece', this.element).each(function()
			{
				var background_position = $(this).css('background-position');
				$(this).draggable({
					cursor     : 'move',
					cursorAt   : { top: SQUARE_SIZE/2, left: SQUARE_SIZE/2 },
					containment: draggableArea,
					helper: function()
					{
						return $(
							'<div style="' +
								'width: ' + SQUARE_SIZE + 'px; height: ' + SQUARE_SIZE + 'px; ' +
								'background-image: url(' + SPRITE_URL + '); ' + 'background-position: ' + background_position +
							'"></div>'
						);
					},
					start: function()
					{
						$(this).css('background-position', '').css('background-image', '');
					},
					stop: function()
					{
						$(this).css('background-position', background_position).css('background-image', 'url(' + SPRITE_URL + ')');
					}
				});
			});

			// Make each square an available drop target.
			$('.uichess-chessboard-cell', this.element).each(function()
			{
				$(this).droppable({
					accept    : '.uichess-chessboard-piece',
					hoverClass: 'uichess-chessboard-cellHover',
					drop: function(event, ui)
					{
						console.log('Move from ' + ui.draggable.data('square') + ' to ' + $(this).data('square')); // TODO: trigger an event instead.
					}
				});
			});
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
