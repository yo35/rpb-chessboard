
/**
 * Tools to create chessboard widgets in HTML pages.
 *
 * @author Yoann Le Montagner
 * @namespace ChessWidget
 *
 * @requires chess.js {@link https://github.com/jhlywa/chess.js}
 * @requires jQuery
 */
ChessWidget = (function(Chess, $)
{
	/**
	 * @typedef {{squareSize: number, showCoordinates: boolean}} Attributes
	 * @desc Compact definition of a set of options applicable to a chess widget.
	 */

	/**
	 * @constructor
	 * @alias Options
	 * @memberof ChessWidget
	 *
	 * @classdesc
	 * Container for options that might affect the appearance of a chess diagram
	 * in a HTML page.
	 *
	 * This structure allows to resolve option values in a hierarchical manner:
	 * each ChessWidget.Options object holds a reference to a parent
	 * ChessWidget.Options object; if one option field is undefined in the
	 * current ChessWidget.Options object, then its value will be determined
	 * based on the value of the parent ChessWidget.Options object.
	 *
	 * @desc Create a new set of options.
	 *
	 * @param {ChessWidget.Options} [parent=null]
	 * @param {ChessWidget.Attributes} [val=null] Pre-defined values for the options.
	 */
	function Options(parent, val)
	{
		/**
		 * @member {ChessWidget.Options} _parent
		 * @memberof ChessWidget.Options
		 * @instance
		 * @desc Parent of the current set of options.
		 * @private
		 */
		this._parent = parent;

		/**
		 * @member {number} _squareSize
		 * @memberof ChessWidget.Options
		 * @instance
		 * @desc Size of the squares of the diagram (in pixel).
		 * @private
		 */
		this._squareSize = null;

		/**
		 * @member {boolean} _showCoordinates
		 * @memberof ChessWidget.Options
		 * @instance
		 * @desc Whether the row and column coordinates should be shown in the diagram.
		 * @private
		 */
		this._showCoordinates = null;

		// Default values
		if(val!=null) {
			if(val.squareSize     !=null) this.setSquareSize     (val.squareSize     );
			if(val.showCoordinates!=null) this.setShowCoordinates(val.showCoordinates);
		}
	}

	/**
	 * Duplicate a ChessWidget.Options object.
	 *
	 * @returns {ChessWidget.Options}
	 */
	Options.prototype.clone = function()
	{
		var retVal = new Options(this._parent);
		retVal._squareSize      = this._squareSize     ;
		retVal._showCoordinates = this._showCoordinates;
		return retVal;
	};

	/**
	 * Return the square-size option value.
	 *
	 * @returns {number}
	 */
	Options.prototype.getSquareSize = function()
	{
		if(this._squareSize==null) {
			return this._parent==null ? 32 : this._parent.getSquareSize();
		}
		else {
			return this._squareSize;
		}
	};

	/**
	 * Return the show-coordinates option value.
	 *
	 * @returns {boolean}
	 */
	Options.prototype.getShowCoordinates = function()
	{
		if(this._showCoordinates==null) {
			return this._parent==null ? true : this._parent.getShowCoordinates();
		}
		else {
			return this._showCoordinates;
		}
	};

	/**
	 * Set the square-size option value.
	 *
	 * @param {(number|string)} [value=null]
	 * New value (null means that the value corresponding getter will read
	 * determine the value from the ChessWidget.Options parent object.
	 */
	Options.prototype.setSquareSize = function(value)
	{
		if(value==null) {
			this._squareSize = null;
		}
		else {
			// Acceptable values are integers multiple of 4 and between 24 and 64.
			value = Math.min(Math.max(value, 24), 64);
			value = 4 * Math.round(value / 4);
			this._squareSize = isNaN(value) ? null : value;
		}
	};

	/**
	 * Set the show-coordinates option value.
	 *
	 * @param {(boolean|string)} [value=null]
	 * New value (null means that the value corresponding getter will read
	 * determine the value from the ChessWidget.Options parent object.
	 */
	Options.prototype.setShowCoordinates = function(value)
	{
		if(value==null) {
			this._squareSize = null;
		}
		else {
			if(typeof(value)=="string") {
				value = value.toLowerCase();
				if     (value=="true" ) this._showCoordinates = true ;
				else if(value=="false") this._showCoordinates = false;
				else                    this._showCoordinates = null ;
			}
			else {
				this._showCoordinates = value ? true : false;
			}
		}
	};



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
	 * Create a new DOM node representing a chess diagram.
	 *
	 * @param {(Chess|string)} position Chess position to represent.
	 *
	 * If the argument is a string, the function will try to parse it as a
	 * FEN-formatted string. If the parsing fails, an empty chess position will
	 * be represented.
	 *
	 * @params {ChessWidget.Options} [options=null] Diagram options, or null to use the default ones.
	 * @returns {jQuery}
	 *
	 * @memberof ChessWidget
	 */
	function make(position, options)
	{
		// Default options
		if(options==null) {
			options = new Options();
		}

		// If the `position` argument is a string, try to parse it as a FEN-formatted string.
		if(typeof(position)=='string') {
			position = position.replace(/^\s+|\s+$/g, '');
			position = new Chess(position);
		}

		// Read the options
		var squareSize       = options.getSquareSize     ();
		var showCoordinates  = options.getShowCoordinates();
		var whiteSquareColor = "#f0dec7"; //TODO: read this from options
		var blackSquareColor = "#b5876b"; //TODO: read this from options
		var squareColor = {light: whiteSquareColor, dark: blackSquareColor};

		// Create the returned node
		var retVal = $('<div class="ChessWidget"></div>');
		var table  = $('<div class="ChessWidget-table"></div>');
		retVal.append(table);

		// Rows, columns
		var ROWS    = ['8', '7', '6', '5', '4', '3', '2', '1'];
		var COLUMNS = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'];

		// For each row...
		for(var r=0; r<8; ++r) {
			var tr = $('<div class="ChessWidget-row"></div>');
			table.append(tr);

			// If visible, the row coordinates are shown in the left-most column.
			if(showCoordinates) {
				var th = $('<div class="ChessWidget-row-header">' + ROWS[r] + '</div>');
				tr.append(th);
			}

			// Print the squares belonging to the current column
			for(var c=0; c<8; ++c) {
				var sq = COLUMNS[c] + ROWS[r];
				var td = $(
					'<div class="ChessWidget-cell" style="background-color: ' + squareColor[position.square_color(sq)] + ';">' +
						'<img src="' + coloredPieceURL(position.get(sq), squareSize) + '" />' +
					'</div>'
				);
				tr.append(td);
			}

			// Add a "fake" cell at the end of the row: this last column will contain
			// the turn flag.
			var fakeCell = $('<div class="ChessWidget-fake-cell"></div>');
			tr.append(fakeCell);

			// Add the turn flag to the current fake cell if required.
			var turn = position.turn();
			if((ROWS[r]=='8' && turn=='b') || (ROWS[r]=='1' && turn=='w')) {
				var img = $('<img src="' + colorURL(turn, squareSize) + '" />');
				fakeCell.append(img);
			}
		}

		// If visible, the column coordinates are shown at the bottom of the table.
		if(showCoordinates) {
			var tr = $('<div class="ChessWidget-row"></div>');
			table.append(tr);

			// Empty cell
			var th0 = $('<div class="ChessWidget-corner-header"></div>');
			tr.append(th0);

			// Column headers
			for(var c=0; c<8; ++c) {
				var th = $('<div class="ChessWidget-column-header">' + COLUMNS[c] + '</div>');
				tr.append(th);
			}

			// Empty cell below the "fake" cell columns
			var thFake = $('<div class="ChessWidget-fake-header"></div>');
			tr.append(thFake);
		}

		// Return the result
		return retVal;
	}



	// Returned the module object
	return {
		Options: Options,
		make   : make
	};

})(Chess, jQuery);
