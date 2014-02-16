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
 * @namespace ChessWidget
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
	 * @memberof ChessWidget
	 */
	var MINIMUM_SQUARE_SIZE = 24;

	/**
	 * Maximal value for the square-size parameter.
	 *
	 * @constant
	 * @public
	 * @memberof ChessWidget
	 */
	var MAXIMUM_SQUARE_SIZE = 64;

	/**
	 * Increment value for the square-size parameter.
	 *
	 * @constant
	 * @public
	 * @memberof ChessWidget
	 */
	var STEP_SQUARE_SIZE = 4;


	/**
	 * Root URL of the library.
	 *
	 * Use the method ChessWidget.rootURL to get the root URL of the library,
	 * instead of reading this variable directly.
	 *
	 * @type {string}
	 * @private
	 * @memberof ChessWidget
	 */
	var _rootURL = null;


	/**
	 * Return the root URL of the library.
	 *
	 * @private
	 *
	 * @returns {string}
	 *
	 * @memberof ChessWidget
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
	 * Return the URL to the folder containing the sprites (images representing the chess pieces),
	 * with the trailing "/" character.
	 *
	 * @private
	 *
	 * @param {number} squareSize
	 * @returns {String}
	 *
	 * @memberof ChessWidget
	 */
	function spriteBaseURL(squareSize)
	{
		var retVal = rootURL() + 'sprite/' + squareSize + '/';
		return retVal;
	}


	/**
	 * Return the URL to the sprite (a PNG image) corresponding to a given colored piece.
	 *
	 * @private
	 *
	 * @param {string} coloredPiece
	 * @param {number} squareSize
	 * @returns {String}
	 *
	 * @memberof ChessWidget
	 */
	function coloredPieceURL(coloredPiece, squareSize)
	{
		var retVal =
			spriteBaseURL(squareSize) +
			(coloredPiece==null ? 'clear' : (coloredPiece.color + coloredPiece.type)) +
			'.png';
		return retVal;
	}


	/**
	 * Return the URL to the sprite (a PNG image) corresponding to a given color flag.
	 *
	 * @private
	 *
	 * @param {string} color
	 * @param {number} squareSize
	 * @returns {string}
	 *
	 * @memberof ChessWidget
	 */
	function colorURL(color, squareSize)
	{
		var retVal = spriteBaseURL(squareSize);
		switch(color) {
			case 'w': retVal+='white.png'; break;
			case 'b': retVal+='black.png'; break;
			default: retVal+='clear.png'; break;
		}
		return retVal;
	}


	/**
	 * Ensure that the given string is trimmed.
	 *
	 * @private
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
	 * @private
	 *
	 * @param {number} squareSize
	 * @returns {number}
	 */
	function filterOptionSquareSize(squareSize)
	{
		squareSize = Math.min(Math.max(squareSize, MINIMUM_SQUARE_SIZE), MAXIMUM_SQUARE_SIZE);
		squareSize = STEP_SQUARE_SIZE * Math.round(squareSize / STEP_SQUARE_SIZE);
		return squareSize;
	}


	/**
	 * Register a 'chessboard' widget in the jQuery widget framework.
	 */
	$.widget('chess.chessboard',
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
			this.element.addClass('chess-chessboard').disableSelection();
			this.options.position   = filterOptionPosition  (this.options.position  );
			this.options.squareSize = filterOptionSquareSize(this.options.squareSize);
			this._refresh();
		},


		/**
		 * Destructor.
		 */
		_destroy: function()
		{
			this.element.removeClass('chess-chessboard').enableSelection();
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
		 */
		sizeControlledByContainer: function(container)
		{
			var obj = this;
			container.on('resize', function(event, ui)
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
				var deltaWPerSq = Math.floor(deltaW / 9 / STEP_SQUARE_SIZE) * STEP_SQUARE_SIZE;
				var deltaHPerSq = Math.floor(deltaH / 8 / STEP_SQUARE_SIZE) * STEP_SQUARE_SIZE;
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
			var whiteSquareColor = "#f0dec7"; //TODO: read this from options
			var blackSquareColor = "#b5876b"; //TODO: read this from options
			var squareColor = {light: whiteSquareColor, dark: blackSquareColor};

			// Rows, columns
			var ROWS    = this.options.flip ? ['1','2','3','4','5','6','7','8'] : ['8','7','6','5','4','3','2','1'];
			var COLUMNS = this.options.flip ? ['h','g','f','e','d','c','b','a'] : ['a','b','c','d','e','f','g','h'];

			// Clear the target node and create the table object.
			this.element.empty();
			var table = $('<div class="ChessWidget-table"></div>').appendTo(this.element);

			// For each row...
			for(var r=0; r<8; ++r) {
				var tr = $('<div class="ChessWidget-row"></div>').appendTo(table);

				// If visible, the row coordinates are shown in the left-most column.
				if(this.options.showCoordinates) {
					$('<div class="ChessWidget-row-header">' + ROWS[r] + '</div>').appendTo(tr);
				}

				// Print the squares belonging to the current column.
				for(var c=0; c<8; ++c) {
					var sq = COLUMNS[c] + ROWS[r];
					$(
						'<div class="ChessWidget-cell" style="background-color: ' + squareColor[this._position.square_color(sq)] + ';">' +
							'<img src="' + coloredPieceURL(this._position.get(sq), this.options.squareSize) + '" />' +
						'</div>'
					).appendTo(tr);
				}

				// Add a "fake" cell at the end of the row: this last column will contain the turn flag.
				var fakeCell = $('<div class="ChessWidget-fake-cell"></div>').appendTo(tr);

				// Add the turn flag to the current fake cell if required.
				var turn = this._position.turn();
				if((ROWS[r]=='8' && turn=='b') || (ROWS[r]=='1' && turn=='w')) {
					$('<img src="' + colorURL(turn, this.options.squareSize) + '" />').appendTo(fakeCell);
				}
			}

			// If visible, the column coordinates are shown at the bottom of the table.
			if(this.options.showCoordinates) {
				var tr = $('<div class="ChessWidget-row"></div>').appendTo(table);

				// Empty cell
				$('<div class="ChessWidget-corner-header"></div>').appendTo(tr);

				// Column headers
				for(var c=0; c<8; ++c) {
					$('<div class="ChessWidget-column-header">' + COLUMNS[c] + '</div>').appendTo(tr);
				}

				// Empty cell below the "fake" cell columns
				$('<div class="ChessWidget-fake-header"></div>').appendTo(tr);
			}
		}
	}); /* End of $.widget('chess.chessboard', ... ) */


	/**
	 * Public constants.
	 */
	$.chessboard =
	{
		MINIMUM_SQUARE_SIZE: MINIMUM_SQUARE_SIZE,
		MAXIMUM_SQUARE_SIZE: MAXIMUM_SQUARE_SIZE,
		STEP_SQUARE_SIZE   : STEP_SQUARE_SIZE
	};

})(Chess, jQuery);
