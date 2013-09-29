
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
		ParsingException: ParsingException,
		parse           : parse
	};

})(Chess);
