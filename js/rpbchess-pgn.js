/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2017  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * @requires rpbchess.js
 */
(function(RPBChess)
{
	'use strict';


	// ---------------------------------------------------------------------------
	// Internationalization
	// ---------------------------------------------------------------------------

	var i18n = RPBChess.i18n;

	// PGN parsing error messages
	i18n.INVALID_PGN_TOKEN               = 'Unrecognized character or group of characters.';
	i18n.INVALID_MOVE_IN_PGN_TEXT        = 'Invalid move. %1$s';
	i18n.INVALID_NULL_MOVE_IN_PGN_TEXT   = 'Invalid null-move.';
	i18n.INVALID_FEN_IN_PGN_TEXT         = 'Invalid FEN string in the initial position header. %1$s';
	i18n.UNEXPECTED_PGN_HEADER           = 'Unexpected PGN item header.';
	i18n.UNEXPECTED_BEGIN_OF_VARIATION   = 'Unexpected begin of variation.';
	i18n.UNEXPECTED_END_OF_VARIATION     = 'Unexpected end of variation.';
	i18n.UNEXPECTED_END_OF_GAME          = 'Unexpected end of game: there are pending variations.';
	i18n.UNEXPECTED_END_OF_TEXT          = 'Unexpected end of text: there is a pending item.';
	i18n.PGN_TEXT_IS_EMPTY               = 'No game found in the text.';
	i18n.PGN_TEXT_CONTAINS_SEVERAL_GAMES = 'The PGN data contains 2 or more games.';
	i18n.INVALID_GAME_INDEX              = 'Game index %1$s is invalid (%2$s game(s) found in the PGN data).';



	// ---------------------------------------------------------------------------
	// Exceptions
	// ---------------------------------------------------------------------------

	/**
	 * @constructor
	 * @alias InvalidPGN
	 * @memberof RPBChess.exceptions
	 *
	 * @classdesc
	 * Exception thrown by the PGN parsing functions.
	 *
	 * @param {string} pgn String whose parsing leads to an error.
	 * @param {number} index Character index in the string where the parsing fails (`-1` if no particular character is targeted).
	 * @param {string} message Human-readable error message.
	 * @param ...
	 */
	function InvalidPGN(pgn, index, message) {
		this.pgn     = pgn    ;
		this.index   = index  ;
		this.message = message;
		for(var i=3; i<arguments.length; ++i) {
			var re = new RegExp('%' + (i-2) + '\\$s');
			this.message = this.message.replace(re, arguments[i]);
		}
	}



	// ---------------------------------------------------------------------------
	// Node
	// ---------------------------------------------------------------------------

	/**
	 * @constructor
	 * @alias Node
	 * @memberof RPBChess.pgn
	 *
	 * @classdesc
	 * Represent one move in the tree structure formed by a chess game with multiple variations.
	 *
	 * @param {Node|Variation} parent
	 * @param {string} move Move notation.
	 * @throws {InvalidNotation} If the move notation cannot be parsed.
	 */
	function Node(parent, move) {

		this._parent = parent; // Either a `Node` or a `Variation` object.
		this._next   = null  ; // Next node (always a `Node` object if defined).
		this._position = new RPBChess.Position(parent.position());

		// Null move.
		if(move === '--') {
			this._notation = '--';
			if(!this._position.playNullMove()) {
				throw new RPBChess.exceptions.InvalidNotation(this._position, '--', i18n.INVALID_NULL_MOVE_IN_PGN_TEXT);
			}
		}

		// Regular move.
		else {
			var moveDescriptor = this._position.notation(move);
			this._notation = this._position.notation(moveDescriptor);
			this._position.play(moveDescriptor);
		}

		// Moving color
		this._movingColor = parent.position().turn();

		// Full-move number
		if(parent instanceof Variation) {
			this._fullMoveNumber = parent._parent._fullMoveNumber;
		}
		else {
			this._fullMoveNumber = this._movingColor==='w' ? parent._fullMoveNumber+1 : parent._fullMoveNumber;
		}

		// Whether the node belongs or not to a "long-variation".
		this._withinLongVariation = parent._withinLongVariation;

		// Variations that could be played instead of the current move.
		this._variations = [];

		// List of NAGs associated to the current move.
		this._nags = [];

		// Text comment associated to the current move if any, or null otherwise.
		this._tags = {};
		this._comment = null;
		this._isLongComment = false;

		// Connect to parent.
		if(parent instanceof Variation) {
			parent._first = this;
		}
		else {
			parent._next = this;
		}
	}


	/**
	 * SAN representation of the move associated to the current node.
	 *
	 * @returns {string}
	 */
	Node.prototype.move = function() {
		return this._notation;
	};


	/**
	 * Chess position before the current move.
	 *
	 * @returns {Position}
	 */
	Node.prototype.positionBefore = function() {
		return this._parent.position();
	};


	/**
	 * Chess position obtained after the current move.
	 *
	 * @returns {Position}
	 */
	Node.prototype.position = function() {
		return this._position;
	};


	/**
	 * Full-move number. It starts at 1, and is incremented after each black move.
	 *
	 * @returns {number}
	 */
	Node.prototype.fullMoveNumber = function() {
		return this._fullMoveNumber;
	};


	/**
	 * Color the side corresponding to the current move.
	 *
	 * @returns {string} Either `'w'` or `'b'`.
	 */
	Node.prototype.moveColor = function() {
		return this._movingColor;
	};


	/**
	 * Next move within the same variation.
	 *
	 * @returns {Node?} `null` if the current move is the last move of the variation.
	 */
	Node.prototype.next = function() {
		return this._next;
	};


	/**
	 * Return the variations that can be followed instead of the current move.
	 *
	 * @returns {Variation[]}
	 */
	Node.prototype.variations = function() {
		return this._variations;
	};


	/**
	 * Return the NAGs associated to the current move.
	 *
	 * @returns {number[]}
	 */
	Node.prototype.nags = function() {
		return this._nags;
	};


	/**
	 * Return the keys of the tags associated to the current move.
	 *
	 * @returns {string[]}
	 */
	Node.prototype.tags = function() {
		var res = [];
		for(var key in this._tags) {
			if(this._tags.hasOwnProperty(key)) {
				res.push(key);
			}
		}
		return res;
	};


	/**
	 * Return the value that is defined for the tag corresponding to the given key on the current move.
	 *
	 * @param {string} key
	 * @returns {string?} `null` if no value is defined for this tag on the current move.
	 */
	Node.prototype.tag = function(key) {
		var res = this._tags[key];
		return typeof res === 'string' ? res : null;
	};


	/**
	 * Return the text comment associated to the current move.
	 *
	 * @returns {string?} `null` if no comment is defined for the move.
	 */
	Node.prototype.comment = function() {
		return this._comment;
	};


	/**
	 * Whether the text comment associated to the current move is long or short.
	 *
	 * @returns {boolean}
	 */
	Node.prototype.isLongComment = function() {
		return this._isLongComment;
	};



	// ---------------------------------------------------------------------------
	// Variation
	// ---------------------------------------------------------------------------

	/**
	 * @constructor
	 * @alias Variation
	 * @memberof RPBChess.pgn
	 *
	 * @classdesc
	 * Represent one variation in the tree structure formed by a chess game, meaning
	 * a starting chess position and list of played consecutively from this position.
	 *
	 * @param {Node|Item} parent Parent node in the tree structure.
	 * @param {boolean} isLongVariation Whether the variation is long or short.
	 */
	function Variation(parent, isLongVariation)
	{
		this._parent = parent; // Either a `Node` or a `Item` object.
		this._first  = null  ; // First node of the variation (always a `Node` object if defined).

		// Whether the variation is or not to a "long-variation".
		this._withinLongVariation = isLongVariation;

		// List of NAGs associated to the current variation.
		this._nags = [];

		// Text comment associated to the current variation if any, or null otherwise.
		this._tags = {};
		this._comment = null;
		this._isLongComment = false;

		// Connect to parent.
		if(parent instanceof Item) {
			parent._mainVariation = this;
		}
		else {
			parent._variations.push(this);
		}
	}


	/**
	 * Whether the current variation is considered as a "long" variation, i.e. a variation that
	 * should be displayed in an isolated block.
	 *
	 * @returns {boolean}
	 */
	Variation.prototype.isLongVariation = function() {
		return this._withinLongVariation;
	};


	/**
	 * Chess position at the beginning of the variation.
	 *
	 * @returns {Position}
	 */
	Variation.prototype.position = function() {
		return (this._parent instanceof Node) ? this._parent.positionBefore() : this._parent.initialPosition();
	};


	/**
	 * First move of the current variation.
	 *
	 * @returns {Node?} May be null if the variation is empty.
	 */
	Variation.prototype.first = function() {
		return this._first;
	};


	// Methods inherited from `Node`.
	Variation.prototype.nags          = Node.prototype.nags         ;
	Variation.prototype.tags          = Node.prototype.tags         ;
	Variation.prototype.tag           = Node.prototype.tag          ;
	Variation.prototype.comment       = Node.prototype.comment      ;
	Variation.prototype.isLongComment = Node.prototype.isLongComment;



	// ---------------------------------------------------------------------------
	// Item
	// ---------------------------------------------------------------------------

	/**
	 * @constructor
	 * @alias Item
	 * @memberof RPBChess.pgn
	 *
	 * @classdesc
	 * Represent a chess game in a PGN file, i.e. the headers (meta-information such
	 * as the name of the players, the date, the event, etc.), the tree structure
	 * representing the played moves together with the possible variations,
	 * and finally the result of the game.
	 */
	function Item() {
		this._headers         = {};
		this._initialPosition = new RPBChess.Position();
		this._fullMoveNumber  = 1;
		this._result          = '*';

		/* jshint nonew:false */ new Variation(this, true); /* jshint nonew:true */
	}


	/**
	 * List all the headers defined for the game.
	 *
	 * @returns {string[]}
	 */
	Item.prototype.headers = function() {
		var retVal = [];
		for(var h in this._headers) {
			if(this._headers.hasOwnProperty(h)) {
				retVal.push(h);
			}
		}
		return retVal;
	};


	/**
	 * Return the value that is defined for the header corresponding to the given key.
	 *
	 * @param {string} key Header to access to.
	 * @returns {string}
	 */
	Item.prototype.header = function(key) {
		var retVal = this._headers[key];
		return typeof retVal === 'string' ? retVal : null;
	};


	/**
	 * Initial position of the game.
	 *
	 * @returns {Position}
	 */
	Item.prototype.initialPosition = function() {
		return this._initialPosition;
	};


	/**
	 * Main variation.
	 *
	 * @returns {Variation}
	 */
	Item.prototype.mainVariation = function() {
		return this._mainVariation;
	};


	/**
	 * Result of the game.
	 *
	 * @returns {string} `'1-0'`, `'0-1'`, `'1/2-1/2'`, or `'*'`.
	 */
	Item.prototype.result = function() {
		return this._result;
	};



	// ---------------------------------------------------------------------------
	// Parsing functions
	// ---------------------------------------------------------------------------

	// Conversion table NAG -> numeric code
	var SPECIAL_NAGS_LOOKUP = {
		'!!' :  3,             // very good move
		'!'  :  1,             // good move
		'!?' :  5,             // interesting move
		'?!' :  6,             // questionable move
		'?'  :  2,             // bad move
		'??' :  4,             // very bad move
		'+-' : 18,             // White has a decisive advantage
		'+/-': 16,             // White has a moderate advantage
		'+/=': 14, '+=' : 14,  // White has a slight advantage
		'='  : 10,             // equal position
		'~'  : 13, 'inf': 13,  // unclear position
		'=/+': 15, '=+' : 15,  // Black has a slight advantage
		'-/+': 17,             // Black has a moderate advantage
		'-+' : 19              // Black has a decisive advantage
	};

	// PGN token types
	var /* const */ TOKEN_HEADER          = 1; // Ex: [White "Kasparov, G."]
	var /* const */ TOKEN_MOVE            = 2; // SAN notation or -- (with an optional move number)
	var /* const */ TOKEN_NAG             = 3; // $[1-9][0-9]* or !! ! !? ?! ? ?? +- +/- +/= += = inf =+ =/+ -/+ -+
	var /* const */ TOKEN_COMMENT         = 4; // {some text}
	var /* const */ TOKEN_BEGIN_VARIATION = 5; // (
	var /* const */ TOKEN_END_VARIATION   = 6; // )
	var /* const */ TOKEN_END_OF_GAME     = 7; // 1-0, 0-1, 1/2-1/2 or *


	/**
	 * Parse a comment, looking for the `[%key value]` tags.
	 *
	 * @param {string} rawComment String to parse.
	 * @returns {{comment:string, tags:object}}
	 */
	function parseComment(rawComment) {
		var tags = {};

		// Find and remove the tags from the raw comment.
		var comment = rawComment.replace(/\[%([a-zA-Z]+) ([^\[\]]+)\]/g, function(match, p1, p2) {
			tags[p1] = p2;
			return ' ';
		});

		// Trim the comment and collapse sequences of space characters into 1 character only.
		comment = comment.replace(/^\s+|\s+$/g, '').replace(/\s+/g, ' ');
		if(comment==='') {
			comment = null;
		}

		// Return the result
		return { comment:comment, tags:tags };
	}


	/**
	 * General PGN parsing function.
	 *
	 * @param {string} pgnString String to parse.
	 * @returns {Item[]}
	 * @throws {InvalidPGN}
	 */
	function parse(pgnString) {

		// State variables for lexical analysis (performed by the function consumeToken()).
		var pos            = 0;     // current position in the string
		var emptyLineFound = false; // whether an empty line has been encountered by skipBlank()
		var token          = 0;     // current token
		var tokenValue     = null;  // current token value (if any)
		var tokenPos       = 0;     // position of the current token in the string

		// Skip the blank and newline characters.
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

		// Read the next token in the input string.
		function consumeToken()
		{
			// Consume blank (i.e. meaning-less) characters
			skipBlank();
			if(pos>=pgnString.length) {
				return false; // -> `false` means that the end of the string have been reached
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

			// Match a move or a null-move
			else if(/^((?:[1-9][0-9]*\s*\.(?:\.\.)?\s*)?((?:O-O-O|O-O|[KQRBN][a-h]?[1-8]?x?[a-h][1-8]|(?:[a-h]x?)?[a-h][1-8](?:=?[KQRBNP])?)[\+#]?|--))/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_MOVE;
				tokenValue = RegExp.$2;
			}

			// Match a NAG
			else if(/^(([!\?][!\?]?|\+\/?[\-=]|[\-=]\/?\+|=|inf|~)|\$([1-9][0-9]*))/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_NAG;
				tokenValue = RegExp.$3.length === 0 ? SPECIAL_NAGS_LOOKUP[RegExp.$2] : parseInt(RegExp.$3, 10);
			}

			// Match a comment
			else if(/^(\{((?:\\\\|\\\{|\\\}|[^\{\}])*)\})/.test(s)) {
				deltaPos   = RegExp.$1.length;
				token      = TOKEN_COMMENT;
				tokenValue = parseComment(RegExp.$2.replace(/\\(\\|\{|\})/g, '$1'));
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
				throw new InvalidPGN(pgnString, pos, i18n.INVALID_PGN_TOKEN);
			}

			// Increment the character pointer and return the result
			tokenPos = pos;
			pos += deltaPos;
			return true;
		}

		// State variable for syntaxic analysis.
		var retVal    = [];    // returned object
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
						throw new InvalidPGN(pgnString, tokenPos, i18n.UNEXPECTED_PGN_HEADER);
					}
					item._headers[tokenValue.key] = tokenValue.value;

					// The header 'FEN' has a special meaning, in that it is used to define a custom
					// initial position, that may be different from the usual one.
					if(tokenValue.key === 'FEN') {
						try {
							var moveCounters = item._initialPosition.fen(tokenValue.value);
							item._fullMoveNumber = moveCounters.fullMoveNumber;
						}
						catch(error) {
							if(error instanceof RPBChess.exceptions.InvalidFEN) {
								throw new InvalidPGN(pgnString, tokenPos, i18n.INVALID_FEN_IN_PGN_TEXT, error.message);
							}
							else {
								throw error;
							}
						}
					}
					break;

				// Move or null-move
				case TOKEN_MOVE:
					try {
						node = new Node(node, tokenValue);
					}
					catch(error) {
						if(error instanceof RPBChess.exceptions.InvalidNotation) {
							throw new InvalidPGN(pgnString, tokenPos, i18n.INVALID_MOVE_IN_PGN_TEXT, error.message);
						}
						else {
							throw error;
						}
					}
					break;

				// NAG
				case TOKEN_NAG:
					node._nags.push(tokenValue);
					break;

				// Comment
				case TOKEN_COMMENT:
					node._tags = tokenValue.tags;
					node._comment = tokenValue.comment;
					node._isLongComment = node._withinLongVariation && emptyLineFound;
					break;

				// Begin of variation
				case TOKEN_BEGIN_VARIATION:
					if(!(node instanceof Node)) {
						throw new InvalidPGN(pgnString, tokenPos, i18n.UNEXPECTED_BEGIN_OF_VARIATION);
					}
					nodeStack.push(node);
					node = new Variation(node, node._withinLongVariation && emptyLineFound);
					break;

				// End of variation
				case TOKEN_END_VARIATION:
					if(nodeStack.length === 0) {
						throw new InvalidPGN(pgnString, tokenPos, i18n.UNEXPECTED_END_OF_VARIATION);
					}
					node = nodeStack.pop();
					break;

					// End-of-game
				case TOKEN_END_OF_GAME:
					if(nodeStack.length>0) {
						throw new InvalidPGN(pgnString, tokenPos, i18n.UNEXPECTED_END_OF_GAME);
					}
					item._result = tokenValue;
					retVal.push(item);
					item = null;
					node = null;
					break;

			} // switch(token)

		} // while(consume(token()))

		// Return the result
		if(item !== null) {
			throw new InvalidPGN(pgnString, pgnString.length, i18n.UNEXPECTED_END_OF_TEXT);
		}
		return retVal;
	}


	/**
	 * PGN parsing function for exactly one item.
	 *
	 * @param {string} pgnString String to parse.
	 * @param {number} [gameIndex] Index of the game to parse (first game is at index 0).
	 * @returns {Item}
	 * @throws {InvalidPGN}
	 */
	function parseOne(pgnString, gameIndex)
	{
		var items = parse(pgnString);

		// No item found -> throw an exception.
		if(items.length === 0) {
			throw new InvalidPGN(pgnString, -1, i18n.PGN_TEXT_IS_EMPTY);
		}

		// No explicit game index -> throw an exception if there is more than 1 item found.
		if(typeof gameIndex === 'undefined' || gameIndex < 0) {
			if(items.length === 1) {
				return items[0];
			}
			throw new InvalidPGN(pgnString, -1, i18n.PGN_TEXT_CONTAINS_SEVERAL_GAMES);
		}

		// Explicit game index -> throw an exception if the game index is not valid.
		else {
			if(gameIndex < items.length) {
				return items[gameIndex];
			}
			throw new InvalidPGN(pgnString, -1, i18n.INVALID_GAME_INDEX, gameIndex, items.length);
		}
	}



	// ---------------------------------------------------------------------------
	// Public objects
	// ---------------------------------------------------------------------------

	RPBChess.exceptions.InvalidPGN = InvalidPGN;
	RPBChess.pgn = {
		Node      : Node      ,
		Variation : Variation ,
		Item      : Item      ,
		parse     : parse     ,
		parseOne  : parseOne
	};

})( /* global RPBChess */ RPBChess );
