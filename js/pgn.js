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
 * Tools to parse PGN text data.
 *
 * Compared to the built-in `Chess#load_png` method provided by the chess.js
 * library, the parsing functions defined below support comments
 * and variations in PGN games.
 *
 * @author Yoann Le Montagner
 * @namespace Pgn
 *
 * @requires chess.js {@link https://github.com/jhlywa/chess.js}
 */
var Pgn = (function(Chess) /* exported Pgn */
{
	'use strict';



	// === BaseNode ==============================================================

	/**
	 * @constructor
	 * @alias BaseNode
	 * @memberof Pgn
	 *
	 * @classdesc
	 * Base class inherited by `Node` and `Variation`.
	 */
	function BaseNode() {}


	/**
	 * Getter/setter for the NAGs associated to the current move/variation.
	 *
	 * If `value` is undefined, the current list of NAGs is returned.
	 * If a new list of NAGs is provided as `value`, it replaces the old one,
	 * which is returned.
	 *
	 * @param {number[]} [value=undefined]
	 * @returns {number[]}
	 */
	BaseNode.prototype.nags = function(value)
	{
		var retVal = this._nags;
		if(value !== undefined) {
			this._nags = value;
		}
		return retVal;
	};


	/**
	 * Getter/setter for the text comment associated to the current move/variation.
	 *
	 * If no argument is provided, the current comment (or `null` if no comment is defined)
	 * is returned (getter behavior).
	 *
	 * If `value` is provided, it replaces the previous comment, which is returned (setter behavior).
	 * Optionnaly, `isLongComment` can be used to set whether the comment is long or short.
	 * However, if the node does not belong itself to a long variation, this flag is ignored,
	 * and the comment is taken as a short comment.
	 *
	 * @param {null|string} [value=undefined]
	 * @param {boolean} [isLongComment]
	 * @returns {null|string}
	 */
	BaseNode.prototype.comment = function(value, isLongComment)
	{
		var retVal = this._comment;
		if(value !== undefined) {
			this._comment = value;
			if(isLongComment !== undefined) {
				this._isLongComment = this._withinLongVariation && isLongComment;  // `this._withinLongVariation` must be defined in derived classes.
			}
		}
		return retVal;
	};


	/**
	 * Whether the text comment associated to the current move/variation is long or short.
	 *
	 * @returns {boolean}
	 */
	BaseNode.prototype.isLongComment = function()
	{
		return this._isLongComment;
	};



	// === Node ==================================================================

	/**
	 * @constructor
	 * @alias Node
	 * @memberof Pgn
	 *
	 * @classdesc
	 * Represent one move in the tree structure formed by a chess game with multiple variations.
	 *
	 * @param {(Pgn.Node|Pgn.Variation)} parent
	 * @param {string} move
	 */
	function Node(parent, move)
	{
		this._parent = parent; // Either a `Node` or a `Variation` object.
		this._move   = move  ; // SAN description of the move.
		this._next   = null  ; // Next node (always a `Node` object if defined).

		// Whether the node belongs or not to a "long-variation".
		this._withinLongVariation = parent._withinLongVariation;

		// Variations that could be played instead of the current move.
		this._variations = [];

		// Chess position obtained after the current move (encoded as a FEN string).
		var position = new Chess(parent.position());
		this._position = position.move(move) === null ? '' : position.fen();

		// Move counter (not to be confused with the full-move number).
		this._moveCounter = (parent instanceof Variation) ? parent.moveCounter() : parent.moveCounter()+1;

		// List of NAGs associated to the current move/variation.
		this._nags = [];

		// Text comment associated to the current move/variation if any, or null otherwise.
		this._comment = null;
		this._isLongComment = false;
	}
	Node.prototype = new BaseNode();
	Node.prototype.constructor = Node;


	/**
	 * Whether the current node have been created from a valid move.
	 *
	 * @returns {boolean}
	 */
	Node.prototype.valid = function()
	{
		return this._position.length !== 0;
	};


	/**
	 * SAN representation of the move associated to the current node.
	 *
	 * @returns {string}
	 */
	Node.prototype.move = function()
	{
		return this._move;
	};


	/**
	 * Chess position before the current move (encoded as a FEN string).
	 *
	 * @returns {string}
	 */
	Node.prototype.positionBefore = function()
	{
		return this._parent.position();
	};


	/**
	 * Chess position obtained after the current move (encoded as a FEN string).
	 *
	 * @returns {string}
	 */
	Node.prototype.position = function()
	{
		return this._position;
	};


	/**
	 * Move counter. This counter is incremented each time a move is played, either
	 * by white or by black. Even values of the move counter denote a move played by
	 * white, odd values a move played by black.
	 *
	 * The move counter must not be confused with the full-move number, which is
	 * incremented only after a black move.
	 *
	 * @returns {number}
	 */
	Node.prototype.moveCounter = function()
	{
		return this._moveCounter;
	};


	/**
	 * Full-move number. It starts at 1, and is incremented after every black moves.
	 *
	 * @returns {number}
	 */
	Node.prototype.fullMoveNumber = function()
	{
		return Math.floor(this._moveCounter / 2) + 1;
	};


	/**
	 * Color the side corresponding to the current move.
	 *
	 * @returns {string} Either 'w' or 'b'.
	 */
	Node.prototype.moveColor = function()
	{
		return (this._moveCounter%2 === 0) ? 'w' : 'b';
	};


	/**
	 * Next move within the same variation.
	 *
	 * @returns {null|Pgn.Node} Null if the current move is the last move of the variation.
	 */
	Node.prototype.next = function()
	{
		return this._next;
	};


	/**
	 * Number of variations that can be followed instead of the current move.
	 *
	 * @returns {number}
	 */
	Node.prototype.variations = function()
	{
		return this._variations.length;
	};


	/**
	 * Return the k^th variation that can be followed instead of the current move.
	 *
	 * @param {number} k Variation index.
	 * @returns {Pgn.Variation}
	 */
	Node.prototype.variation = function(k)
	{
		return this._variations[k];
	};


	/**
	 * Define the move that immediatly follows the one represented by the current node.
	 *
	 * @param {string} move The new move to be played.
	 * @returns {boolean} True if the move has actually been played, false if the move was badly formatted or illegal.
	 */
	Node.prototype.play = function(move)
	{
		this._next = new Node(this, move);
		return this._next.valid();
	};


	/**
	 * Add a new variation to the current move.
	 *
	 * @param {boolean} Whether the is long or short. This flag is ignored if the current move
	 *        does not belong itself to a long variation.
	 * @returns {Pgn.Variation} The newly created variation, with no node.
	 */
	Node.prototype.addVariation = function(isLongVariation)
	{
		var newVariation = new Variation(this, this._withinLongVariation && isLongVariation);
		this._variations.push(newVariation);
		return newVariation;
	};



	// === Variation =============================================================

	/**
	 * @constructor
	 * @alias Variation
	 * @memberof Pgn
	 *
	 * @classdesc
	 * Represent one variation in the tree structure formed by a chess game, meaning
	 * a starting chess position and list of played consecutively from this position.
	 *
	 * @param {(Pgn.Node|Pgn.Item)} parent Parent node in the tree structure.
	 * @param {boolean} isLongVariation Whether the variation is long or short.
	 */
	function Variation(parent, isLongVariation)
	{
		this._parent = parent; // Either a `Node` or a `Item` object.
		this._first  = null  ; // First node of the variation (always as `Node` object if defined).

		// Whether the variation is or not to a "long-variation".
		this._withinLongVariation = isLongVariation;

		// List of NAGs associated to the current move/variation.
		this._nags = [];

		// Text comment associated to the current move/variation if any, or null otherwise.
		this._comment = null;
		this._isLongComment = false;
	}
	Variation.prototype = new BaseNode();
	Variation.prototype.constructor = Variation;


	/**
	 * Whether the current variation is considered as a "long" variation, i.e. a variation that
	 * should be displayed in an isolated block.
	 *
	 * @returns {boolean}
	 */
	Variation.prototype.isLongVariation = function()
	{
		return this._withinLongVariation;
	};


	/**
	 * Chess position at the beginning of the variation (encoded as a FEN string).
	 *
	 * @returns {string}
	 */
	Variation.prototype.position = function()
	{
		return (this._parent instanceof Node) ? this._parent.positionBefore() : this._parent.initialPosition();
	};


	/**
	 * Move counter to use for the first move of the variation.
	 *
	 * @returns {number}
	 */
	Variation.prototype.moveCounter = function()
	{
		return (this._parent instanceof Node) ? this._parent.moveCounter() : this._parent.initialMoveCounter();
	};


	/**
	 * First move of the current variation.
	 *
	 * @returns {Pgn.Node} May be null if the variation is empty.
	 */
	Variation.prototype.first = function()
	{
		return this._first;
	};


	/**
	 * Define the first move of the variation.
	 *
	 * @param {string} move The new to be played.
	 * @returns {boolean} True if the move has actually been played, false if the move was badly formatted or illegal.
	 */
	Variation.prototype.play = function(move)
	{
		this._first = new Node(this, move);
		return this._first.valid();
	};



	// === Item ==================================================================

	/**
	 * @constructor
	 * @alias Item
	 * @memberof Pgn
	 *
	 * @classdesc
	 * Represent a chess game in a PGN file, i.e. the headers (meta-information such
	 * as the name of the players, the date, the event, etc.), the tree structure
	 * representing the played moves together with the possible variations,
	 * and finally the result of the game.
	 */
	function Item()
	{
		this._headers            = {};
		this._initialPosition    = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';
		this._initialMoveCounter = 0;
		this._mainVariation      = new Variation(this, true);
		this._result             = '*'; // '1-0', '0-1', '1/2-1/2' or '*'
	}


	/**
	 * List all the headers defined for the game.
	 *
	 * @returns {string[]}
	 */
	Item.prototype.headers = function()
	{
		var retVal = [];
		for(var h in this._headers) {
			if(this._headers.hasOwnProperty(h)) {
				retVal.push(h);
			}
		}
		return retVal;
	};


	/**
	 * Getter/setter for the headers of the game.
	 *
	 * If `value` is undefined, the method reads the meta-information identified by `key`,
	 * and return it (getter behavior). Otherwise, this meta-information is set,
	 * and the old value is returned (setter behavior).
	 *
	 * @param {string} key Header to access to.
	 * @param {string} [value=undefined]
	 * @returns {string}
	 */
	Item.prototype.header = function(key, value)
	{
		var retVal = (key in this._headers) ? this._headers[key] : null;
		if(value !== undefined) {
			this._headers[key] = value;
		}
		return retVal;
	};


	/**
	 * Initial position of the game.
	 *
	 * @returns {string}
	 */
	Item.prototype.initialPosition = function()
	{
		return this._initialPosition;
	};


	/**
	 * Value of the move counter to use for the first move.
	 *
	 * @returns {number}
	 */
	Item.prototype.initialMoveCounter = function()
	{
		return this._initialMoveCounter;
	};


	/**
	 * Define the initial position and move number.
	 *
	 * Calling this method erases all the moves and variations previously defined.
	 *
	 * @params {string} fen FEN-formatted string representing the new initial position.
	 * @returns {boolean} True if the operation succeed (i.e. if `fen` is a valid FEN string).
	 */
	Item.prototype.defineInitialPosition = function(fen)
	{
		// Erase the moves.
		this._mainVariation = new Variation(this, true);

		// Validate the FEN string.
		var p = new Chess(fen);
		if(p.fen() !== fen) {
			return false;
		}

		// Validate the full-move number in the FEN string.
		if(!/^.* ([0-9]+)\s*$/.test(fen)) {
			return false;
		}
		var fullMoveNumber = parseInt(RegExp.$1, 10);

		// Save the data
		this._initialPosition    = fen;
		this._initialMoveCounter = 2*(fullMoveNumber - 1) + (p.turn() === 'w' ? 0 : 1);
		return true;
	};


	/**
	 * Main variation.
	 *
	 * @returns {Pgn.Variation}
	 */
	Item.prototype.mainVariation = function()
	{
		return this._mainVariation;
	};


	/**
	 * Getter/setter for the result of the game.
	 *
	 * If no argument is provided, the current result is returned (getter behavior).
	 * Otherwise, `value` replaces the old game result, which is returned.
	 *
	 * @param {string} [value=undefined] Must be `'1-0'`, `'0-1'`, `'1/2-1/2'` or `'*'`.
	 * @returns {string}
	 */
	Item.prototype.result = function(value)
	{
		var retVal = this._result;
		if(value !== undefined) {
			switch(value) {
				case '1-0':
				case '0-1':
				case '1/2-1/2':
				case '*':
					this._result = value;
					break;
			}
		}
		return retVal;
	};



	// === Error =================================================================

	/**
	 * @constructor
	 * @alias Error
	 * @memberof Pgn
	 *
	 * @classdesc
	 * Exception thrown by the PGN parsing functions.
	 *
	 * @param {string} pgnString String whose parsing leads to an error.
	 * @param {null|number} pos Position in the string where the parsing fails.
	 * @param {string} message Human-readable error message.
	 */
	function Error(pgnString, pos, message)
	{
		this.pgnString = pgnString;
		this.pos       = pos      ;
		this.message   = message  ;
	}



	// === Parsing functions =====================================================

	/**
	 * The most common NAGs, and their correspond numeric code.
	 *
	 * @private
	 * @constant
	 *
	 * @memberof Pgn
	 */
	var SPECIAL_NAGS_LOOKUP = {
		'!!' :  3,             // very good move
		'!'  :  1,             // good move
		'!?' :  5,             // interesting move
		'?!' :  6,             // questionable move
		'?'  :  2,             // bad move
		'??' :  4,             // very bad move
		'+-' : 18,             // White has a decisive advantage
		'+/-': 16,             // White has a moderate advantage
		'+=' : 14, '+/=' : 14, // White has a slight advantage
		'='  : 10,             // equal position
		'inf': 13,             // unclear position
		'=+' : 15, '=/+' : 15, // Black has a slight advantage
		'-/+': 17,             // Black has a moderate advantage
		'-+' : 19              // Black has a decisive advantage
	};



	/**
	 * General PGN parsing function.
	 *
	 * @param {string} pgnString String to parse.
	 * @returns {Pgn.Item[]}
	 * @throws {Error}
	 *
	 * @memberof Pgn
	 */
	function parse(pgnString)
	{
		// Token types
		var /* const */ TOKEN_HEADER          = 1; // Ex: [White "Kasparov, G."]
		var /* const */ TOKEN_MOVE            = 2; // [BKNQRa-h1-8xO\-=\+#]+ (with an optional move number)
		var /* const */ TOKEN_NAG             = 3; // $[1-9][0-9]* or !! ! !? ?! ? ?? +- +/- +/= += = inf =+ =/+ -/+ -+
		var /* const */ TOKEN_COMMENT         = 4; // {some text}
		var /* const */ TOKEN_BEGIN_VARIATION = 5; // (
		var /* const */ TOKEN_END_VARIATION   = 6; // )
		var /* const */ TOKEN_END_OF_GAME     = 7; // 1-0, 0-1, 1/2-1/2 or *

		// State variables for lexical analysis (performed by the function consumeToken()).
		var pos            = 0;     // current position in the string
		var emptyLineFound = false; // whether an empty line has been encountered by skipBlank()
		var token          = 0;     // current token
		var tokenValue     = null;  // current token value (if any)
		var tokenPos       = 0;     // position of the current token in the string

		/**
		 * Skip the blank and newline characters.
		 */
		function skipBlank()
		{
			var newLineCount = 0;
			while(pos<pgnString.length) {
				var s = pgnString.substr(pos);
				if(/^([ \f\t\v])+/.test(s)) { // match spaces
					pos += RegExp.$1.length;
				}
				else if(/^(\r?\n|\r)/.test(s)) { // match line-breaks
					pos += RegExp.$1.length;
					++newLineCount;
				}
				else {
					break;
				}
			}

			// An empty line was encountered if and only if at least to line breaks were found.
			emptyLineFound = newLineCount>=2;
		}

		/**
		 * Read the next token in the input string.
		 *
		 * @returns {boolean} false if no token have been read (meaning that the end of the string have been reached).
		 */
		function consumeToken()
		{
			// Consume blank (i.e. meaning-less) characters
			skipBlank();
			if(pos>=pgnString.length) {
				return false;
			}

			// Remaining part of the string
			var s = pgnString.substr(pos);
			var deltaPos = 0;

			// Match a game header (ex: [White "Kasparov, G."])
			if(/^(\[\s*(\w+)\s+\"([^\"]*)\"\s*\])/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_HEADER;
				tokenValue = {key: RegExp.$2, value: RegExp.$3};
			}

			// Match a move
			else if(/^((?:[1-9][0-9]*\s*\.(?:\.\.)?\s*)?((?:O-O-O|O-O|[KQRBNP]?[a-h]?[1-8]?x?[a-h][1-8](?:=?[KQRBNP])?)[\+#]?))/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_MOVE;
				tokenValue = RegExp.$2;
			}

			// Match a NAG
			else if(/^(([!\?][!\?]?|\+\/?[\-=]|[\-=]\/?\+|=|inf)|\$([1-9][0-9]*))/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_NAG;
				tokenValue = RegExp.$3.length === 0 ? SPECIAL_NAGS_LOOKUP[RegExp.$2] : parseInt(RegExp.$3, 10);
			}

			// Match a comment
			else if(/^(\{((?:\\\\|\\\{|\\\}|[^\{\}])*)\})/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_COMMENT;
				tokenValue = RegExp.$2.replace(/\\(\\|\{|\})/g, '$1').replace(/^\s+|\s+$/g, '');
			}

			// Match the beginning of a variation
			else if(/^(\()/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_BEGIN_VARIATION;
				tokenValue = null;
			}

			// Match the end of a variation
			else if(/^(\))/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_END_VARIATION;
				tokenValue = null;
			}

			// Match a end-of-game marker
			else if(/^(1\-0|0\-1|1\/2\-1\/2|\*)/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_END_OF_GAME;
				tokenValue = RegExp.$1;
			}

			// Otherwise, the string is badly formatted with respect to the PGN syntax
			else {
				throw new Error(pgnString, pos, 'Unrecognized character or group of characters.');
			}

			// Increment the character pointer and return the result
			tokenPos = pos;
			pos += deltaPos;
			return true;
		}

		// State variable for syntaxic analysis.
		var retVal    = [];    // returned object (array of Pgn.Item)
		var item      = null;  // item being parsed (if any)
		var node      = null;  // current node (or variation) to which the next move should be appended
		var nodeStack = [];    // when starting to parse a variation, its parent node is stacked here

		// Token loop
		while(consumeToken())
		{
			// Create a new item if necessary
			if(item === null) {
				item = new Item();
			}

			// Matching anything else different from a header means that the move section
			// is going to be parse => set-up the root node.
			if(token !== TOKEN_HEADER && node === null) {
				node = item.mainVariation();
			}

			// Token type switch
			switch(token)
			{
				// Header
				case TOKEN_HEADER:
					if(node !== null) {
						throw new Error(pgnString, tokenPos, 'Unexpected PGN item header.');
					}
					item.header(tokenValue.key, tokenValue.value);

					// The header 'FEN' has a special meaning, in that it is used to define a custom
					// initial position, that may be different from the usual one.
					if(tokenValue.key === 'FEN') {
						if(!item.defineInitialPosition(tokenValue.value)) {
							throw new Error(pgnString, tokenPos, 'Invalid FEN string.');
						}
					}
					break;

				// Move
				case TOKEN_MOVE:
					if(!node.play(tokenValue)) {
						throw new Error(pgnString, tokenPos, 'Invalid move.');
					}
					node = (node instanceof Variation) ? node.first() : node.next();
					break;

				// NAG
				case TOKEN_NAG:
					node.nags().push(tokenValue);
					break;

				// Comment
				case TOKEN_COMMENT:
					node.comment(tokenValue, emptyLineFound);
					break;

				// Begin of variation
				case TOKEN_BEGIN_VARIATION:
					if(!(node instanceof Node)) {
						throw new Error(pgnString, tokenPos, 'Unexpected begin of variation.');
					}
					nodeStack.push(node);
					node = node.addVariation(emptyLineFound);
					break;

				// End of variation
				case TOKEN_END_VARIATION:
					if(nodeStack.length === 0) {
						throw new Error(pgnString, tokenPos, 'Unexpected end of variation.');
					}
					node = nodeStack.pop();
					break;

					// End-of-game
				case TOKEN_END_OF_GAME:
					if(nodeStack.length>0) {
						throw new Error(pgnString, tokenPos, 'Unexpected end of game: there are pending variations.');
					}
					item.result(tokenValue);
					retVal.push(item);
					item = null;
					node = null;
					break;

			} // switch(token)

		} // while(consume(token()))

		// Return the result
		if(item !== null) {
			throw new Error(pgnString, pgnString.length, 'Unexpected end of text: there is a pending item.');
		}
		return retVal;
	}


	/**
	 * PGN parsing function for exactly one item.
	 *
	 * @param {string} pgnString String to parse.
	 * @returns {Pgn.Item}
	 * @throws {Error}
	 *
	 * @memberof Pgn
	 */
	function parseOne(pgnString)
	{
		var items = Pgn.parse(pgnString);
		switch(items.length) {

			// No item found -> throw an exception.
			case 0:
				throw new Error(pgnString, null, 'Unexpected empty PGN data.');

			// 1 item found -> return it.
			case 1:
				return items[0];

			// More than 1 item found -> throw an exception.
			default:
				throw new Error(pgnString, null, 'The PGN data is expected to contain only one game.');
		}
	}


	// Returned the module object
	return {
		Node     : Node     ,
		Variation: Variation,
		Item     : Item     ,
		Error    : Error    ,
		parse    : parse    ,
		parseOne : parseOne
	};

})( /* global Chess */ Chess );
