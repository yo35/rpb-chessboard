
/**
 * Tools to parse PGN text data.
 *
 * Compared to the built-in `Chess#load_png` method provided by the chess.js
 * library, the parsing functions defined below support commentaries
 * and variations in PGN games.
 *
 * @author Yoann Le Montagner
 * @namespace Pgn
 *
 * @requires chess.js {@link https://github.com/jhlywa/chess.js}
 */
Pgn = (function(Chess)
{
	/**
	 * @constructor
	 * @alias Node
	 * @memberof Pgn
	 *
	 * @classdesc
	 * Represent one move in the tree structure formed by a chess game with multiple variations.
	 *
	 * @desc Create a new node in the tree structure representing a chess game.
	 *
	 * @param {(Pgn.Node|Pgn.Variation)} parent
	 * @param {string} move
	 */
	function Node(parent, move)
	{
		/**
		 * @member {(Pgn.Node|Pgn.Variation)} _parent
		 * @memberof Pgn.Node
		 * @instance
		 * @desc Parent of the current node.
		 * @private
		 */
		this._parent = parent;

		/**
		 * @member {string} _move
		 * @memberof Pgn.Node
		 * @instance
		 * @desc SAN description of the move.
		 * @private
		 */
		this._move = move;

		/**
		 * @member {string} _position
		 * @memberof Pgn.Node
		 * @instance
		 * @desc Chess position obtained after the current move (encoded as a FEN string).
		 * @private
		 */
		var position = new Chess(parent.position());
		if(position.move(move)==null) {
			this._position = '';
		}
		else {
			this._position = position.fen();
		}

		/**
		 * @member {number} _moveCounter
		 * @memberof Pgn.Node
		 * @instance
		 * @desc Move counter (not to be confused with the full-move number).
		 * @private
		 */
		this._moveCounter = (parent instanceof Variation) ? parent.moveCounter() : parent.moveCounter()+1;

		/**
		 * @member {number[]} nags
		 * @memberof Pgn.Node
		 * @instance
		 * @desc List of NAGs associated to the current move.
		 */
		this.nags = [];

		/**
		 * @member {string} commentary
		 * @memberof Pgn.Node
		 * @instance
		 * @desc Commentary associated to the current move (an empty string means no-commentary).
		 */
		this.commentary = '';

		/**
		 * @member {Pgn.Variation[]} _variations
		 * @memberof Pgn.Node
		 * @instance
		 * @desc Variations that could be played instead of the current move.
		 * @private
		 */
		this._variations = [];

		/**
		 * @member {Pgn.Node} _next
		 * @memberof Pgn.Node
		 * @instance
		 * @desc Next move (may be null if the current move is the last move of the variation).
		 * @private
		 */
		this._next = null;
	}

	/**
	 * Whether the current node have been created from a valid move.
	 *
	 * @returns {boolean}
	 */
	Node.prototype.valid = function()
	{
		return this._position.length!=0;
	};

	/**
	 * Move associated to the current node.
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
		return (this._moveCounter%2==0) ? 'w' : 'b';
	};

	/**
	 * Next move within the same variation.
	 *
	 * @returns {Pgn.Node} May be null if the current move is the last move of the variation.
	 */
	Node.prototype.next = function()
	{
		return this._next;
	};

	/**
	 * Number of variations that can be followed instead of the current move.
	 *
	 * @return {number}
	 */
	Node.prototype.nbVariations = function()
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
	 * @returns {Pgn.Node} The newly created node.
	 */
	Node.prototype.play = function(move)
	{
		this._next = new Node(this, move);
		return this._next;
	};

	/**
	 * Add a new variation to the current move.
	 *
	 * @returns {Pgn.Variation} The newly created variation, with no node.
	 */
	Node.prototype.addVariation = function()
	{
		var newVariation = new Variation(this);
		this._variations.push(newVariation);
		return newVariation;
	};



	/**
	 * @constructor
	 * @alias Variation
	 * @memberof Pgn
	 *
	 * @classdesc
	 * Represent one variation in the tree structure formed by a chess game, meaning
	 * a starting chess position and list of played consecutively from this position.
	 *
	 * @desc Initiate a new variation.
	 *
	 * @param {(Pgn.Node|Pgn.Item)} parent Parent node in the tree structure.
	 */
	function Variation(parent)
	{
		/**
		 * @member {(Pgn.Node|Pgn.Item)} _parent
		 * @memberof Pgn.Variation
		 * @instance
		 * @desc Parent of the current variation.
		 * @private
		 */
		this._parent = parent;

		/**
		 * @member {number[]} nags
		 * @memberof Pgn.Variation
		 * @instance
		 * @desc List of NAGs associated to the beginning of the variation.
		 */
		this.nags = [];

		/**
		 * @member {string} commentary
		 * @memberof Pgn.Variation
		 * @instance
		 * @desc Commentary associated to the beginning of the variation (an empty string means no-commentary).
		 */
		this.commentary = '';

		/**
		 * @member {Pgn.Node} _first
		 * @memberof Pgn.Variation
		 * @instance
		 * @desc First move of the variation.
		 * @private
		 */
		this._first = null;
	}

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
	 * @returns {Pgn.Node} The newly created node.
	 */
	Variation.prototype.play = function(move)
	{
		this._first = new Node(this, move);
		return this._first;
	};



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
	 *
	 * @desc Create a new chess game.
	 */
	function Item()
	{
		/**
		 * @member {object} _headers
		 * @memberof Pgn.Item
		 * @instance
		 * @desc Associative array holding the meta-information associated to the game.
		 * @private
		 */
		this._headers = {};

		/**
		 * @member {string} _initialPosition
		 * @memberof Pgn.Item
		 * @instance
		 * @desc Initial position (encoded as a FEN string).
		 * @private
		 */
		this._initialPosition = 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1';

		/**
		 * @member {number} _initialMoveCounter
		 * @memberof Pgn.Item
		 * @instance
		 * @desc Initial move counter.
		 * @private
		 */
		this._initialMoveCounter = 0;

		/**
		 * @member {Pgn.Variation} _mainVariation
		 * @memberof Pgn.Item
		 * @instance
		 * @desc Main variation of the game.
		 * @private
		 */
		this._mainVariation = new Variation(this);

		/**
		 * @member {string} result
		 * @memberof Pgn.Item
		 * @instance
		 * @desc Result of the game (may be '1-0', '0-1', '1/2-1/2' or '*').
		 */
		this.result = '*';
	}

	/**
	 * Getter/setter for the headers of the game
	 *
	 * @param {string} key Header to access to.
	 * @param {string} [value=undefined]
	 *
	 * If undefined, the method reads the meta-information identified by `key`,
	 * and return it. Otherwise, this meta-information is set, and the old value
	 * is returned.
	 *
	 * @returns {string}
	 */
	Item.prototype.header = function(key, value)
	{
		var retVal = this._headers[key];
		if(value!==undefined) {
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
	 * Main variation.
	 *
	 * @return {Pgn.Variation}
	 */
	Item.prototype.mainVariation = function()
	{
		return this._mainVariation;
	};

	/**
	 * Define the initial position and move number.
	 *
	 * @params {string} fen FEN-formatted string representing the new initial position.
	 * @returns {boolean} True if the operation succeed (i.e. if `fen` is a valid FEN string).
	 */
	Item.prototype.defineInitialPosition = function(fen)
	{
		// Validate the FEN string.
		var p = new Chess(fen);
		if(p.fen()!=fen) {
			return false;
		}

		// Validate the full-move number in the FEN string.
		if(!/^.* ([0-9]+)\s*$/.test(fen)) {
			return false;
		}
		var fullMoveNumber = parseInt(RegExp.$1);

		// Save the data
		this._initialPosition    = fen;
		this._initialMoveCounter = 2*(fullMoveNumber - 1) + (p.turn()=='w' ? 0 : 1);
		return true;
	};



	/**
	 * @constructor
	 * @alias ParsingException
	 * @memberof Pgn
	 *
	 * @classdesc
	 * Exception thrown by the PGN parsing function.
	 *
	 * @desc Create a new PGN parsing exception.
	 *
	 * @param {string} pgnString String whose parsing leads to an error.
	 * @param {number} pos Position in the string where the parsing fails.
	 * @param {string} message Human-readable error message.
	 */
	function ParsingException(pgnString, pos, message)
	{
		this.pgnString = pgnString;
		this.pos       = pos      ;
		this.message   = message  ;
	}



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
	 * PGN parsing function.
	 *
	 * @param {string} pgnString String to parse.
	 * @throws {ParsingException}
	 *
	 * @memberof Pgn
	 */
	function parse(pgnString)
	{
		// Token types
		const TOKEN_HEADER          = 1; // Ex: [White "Kasparov, G."]
		const TOKEN_MOVE            = 2; // [BKNQRa-h1-8xO\-=\+#]+ (with an optional move number)
		const TOKEN_NAG             = 3; // $[1-9][0-9]* or !! ! !? ?! ? ?? +- +/- += = inf =+ -/+ -+
		const TOKEN_COMMENTARY      = 4; // {some text}
		const TOKEN_BEGIN_VARIATION = 5; // (
		const TOKEN_END_VARIATION   = 6; // )
		const TOKEN_END_OF_GAME     = 7; // 1-0, 0-1, 1/2-1/2 or *

		// State variables
		var pos            = 0;     // current position in the string
		var emptyLineFound = false; // whether an empty line has been encountered by skipBlank()
		var token          = 0;     // current token
		var tokenValue     = null;  // current token value (if any)

		var retVal     = Array();
		var item       = null;

		/**
		 * Skip the blank and newline characters.
		 */
		function skipBlank()
		{
			var newLineCount = 0;
			while(pos<pgnString.length) {
				s = pgnString.substr(pos);
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
			s = pgnString.substr(pos);

			// Match a game header (ex: [White "Kasparov, G."])
			if(/^(\[\s*(\w+)\s+\"([^\"]*)\"\s*\])/.test(s)) {
				token      = TOKEN_HEADER;
				tokenValue = {key: RegExp.$2, value: RegExp.$3};
			}

			// Match a move
			else if(/^((?:[1-9][0-9]*\s*\.(?:\.\.)?\s*)?(O-O-O|O-O|[KQRBNP]?[a-h]?[1-8]?x?[a-h][1-8](?:=?[KQRBNP])?[\+#]?))/.test(s)) {
				token      = TOKEN_MOVE;
				tokenValue = RegExp.$2;
			}

			// Match a NAG
			else if(/^((!!|!\?|!|\?\?|\?!|\?|\+\/?\-|\+\/?=|=\/?\+|\-\/?\+|=|inf)|\$([1-9][0-9]*))/.test(s)) {
				console.log("Nag detected: $2=["+RegExp.$2+"] $3=["+RegExp.$3+"]");
				console.log(RegExp.$2, RegExp.$3);
				token      = TOKEN_NAG;
				tokenValue = RegExp.$3.length==0 ? SPECIAL_NAGS_LOOKUP[RegExp.$2] : parseInt(RegExp.$3);
			}

			// Match a commentary
			else if(/^(\{([^\{\}]*)\})/.test(s)) {
				token      = TOKEN_COMMENTARY;
				tokenValue = RegExp.$2;
			}

			// Match the beginning of a variation
			else if(/^(\()/.test(s)) {
				token      = TOKEN_BEGIN_VARIATION;
				tokenValue = null;
			}

			// Match the end of a variation
			else if(/^(\))/.test(s)) {
				token      = TOKEN_END_VARIATION;
				tokenValue = null;
			}

			// Match a end-of-game marker
			else if(/^(1\-0|0\-1|1\/2\-1\/2|\*)/.test(s)) {
				token      = TOKEN_END_OF_GAME;
				tokenValue = RegExp.$1;
			}

			// Otherwise, the string is badly formatted with respect to the PGN syntax
			else {
				throw new ParsingException(pgnString, pos, 'Unrecognized character or group of characters.');
			}

			// Increment the character pointer and return the result
			pos += RegExp.$1.length;
			return true;
		}

		//TODO: remove this
		while(consumeToken()) {
			if(emptyLineFound) {
				console.log('<empty line>');
			}
			console.log(token, tokenValue);
		}




		/*skipBlank();
		while(pos<pgnString.length) {
			if(emptyLineFound) {
				console.log('<empty line>');
			}
			console.log(pgnString.charAt(pos) + ' at pos. ' + pos);
			++pos;
			skipBlank();
		}*/
	}




	// Returned the module object
	return {
		Node            : Node            ,
		Variation       : Variation       ,
		Item            : Item            ,
		ParsingException: ParsingException,
		parse           : parse
	};

})(Chess);
