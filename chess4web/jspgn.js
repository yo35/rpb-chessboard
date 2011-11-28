/******************************************************************************
 *                                                                            *
 *    This file is part of chess4web, a javascript library for displaying     *
 *    chessboards or full chess game in a web page.                           *
 *                                                                            *
 *    Copyright (C) 2011  Yoann Le Montagner <yo35(at)melix(dot)net>          *
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
 * Result of a game
 * @{
 */
const WHITE_WINS = 0;
const BLACK_WINS = 1;
const DRAW       = 2;
const UNFINISHED = 3;
///@}

/**
 * Node in a PGN tree (correspond to 1 move)
 * \remark Do not use the constructor directly
 */
function PGNNode(parent, move)
{
	this.address  = parent.address + "+";
	this.parent   = parent;
	this.move     = move;
	this.position = parent.position.clone();
	play(this.position, move);
	this.commentary = null;
	this.nags       = Array();
	this.next       = null;
	this.variations = Array();
}
PGNNode.prototype =
{
	/**
	 * Play the next move of the current variation
	 * \pre The move must be legal according to this.position
	 */
	play: function(move)
	{
		this.next = new PGNNode(this, move);
		return this.next;
	},

	/**
	 * Start a new variation
	 */
	startVariation: function()
	{
		var newVariation      = new PGNVariation();
		newVariation.address  = this.address + this.variations.length;
		newVariation.parent   = this;
		newVariation.position = this.parent.position;
		this.variations.push(newVariation);
		return newVariation;
	}
}

/**
 * Object corresponding to a variation
 * \remark Do not use the constructor directly
 */
function PGNVariation()
{
	this.address    = "";
	this.parent     = null;
	this.position   = null;
	this.commentary = null;
	this.nags       = Array();
	this.next       = null;
}
PGNVariation.prototype =
{
	/**
	 * Play the first move of the variation
	 * \pre The move must be legal according to this.position
	 */
	play: function(move)
	{
		this.next = new PGNNode(this, move);
		return this.next;
	}
}

/**
 * Object corresponding to 1 game in a *.pgn file
 */
function PGNItem()
{
	this.mainVariation = null;
	this.result        = UNFINISHED;
}
PGNItem.prototype =
{
	/**
	 * Start the game
	 * \param startingPosition Starting position (optional, default: new initial position)
	 * \pre The starting position must be legal
	 */
	startMainVariation: function(startingPosition)
	{
		if(startingPosition===undefined) {
			startingPosition = makeInitialPosition();
		}
		this.mainVariation = new PGNVariation();
		this.mainVariation.position = startingPosition;
		return this.mainVariation;
	},

	/**
	 * Return the node (or variation) with the given address, or null if such a node
	 * does not exist
	 */
	addressLookup: function(address)
	{
		var cursor = this.mainVariation;
		var pos    = 0;
		while(pos<address.length) {
			if(cursor==null) {
				return null;
			}
			if(address.charAt(pos)=="+") {
				cursor = cursor.next;
				++pos;
			}
			else {
				var variationIndex = 0;
				while(pos<address.length) {
					var charCode = address.charCodeAt(pos);
					if(charCode==43) {
						break;
					}
					else if(!(charCode>=48 && charCode<=57)) {
						return null;
					}
					variationIndex = variationIndex*10 + (charCode-48);
					++pos;
				}
				cursor = variationIndex>=cursor.variations.length ? null : cursor.variations[variationIndex];
			}
		}
		return cursor;
	}
}

/**
 * Exception thrown when an error is encountered in the PGN stream
 */
function PGNException(pgnString, position, message)
{
	this.position  = position ;
	this.pgnString = pgnString;
	this.message   = "PGN stream error at " + position + ": " + message;
}

/**
 * Parse a *.pgn file
 * \throw PGNException
 */
function parsePGN(pgnString)
{
	// Token types
	const TOKEN_OPENING_SQUARE_BRACKETS =  1; // [
	const TOKEN_CLOSING_SQUARE_BRACKETS =  2; // ]
	const TOKEN_OPENING_PARENTHESIS     =  3; // (
	const TOKEN_CLOSING_PARENTHESIS     =  4; // )
	const TOKEN_NAG                     =  5; // $[1-9][0-9]* or !! ! !? ?! ? ??
	const TOKEN_DOT_1                   =  6; // .
	const TOKEN_DOT_3                   =  7; // ...
	const TOKEN_END_OF_GAME             =  8; // 1-0, 0-1, 1/2-1/2 or *
	const TOKEN_TAG_ID                  =  9; // [a-zA-Z_]+
	const TOKEN_TAG_VALUE               = 10; // "[a-zA-Z_]*"
	const TOKEN_MOVE_NUMBER             = 11; // [0-9]+
	const TOKEN_MOVE                    = 12; // [a-h1-8#+-=BKNOQRx]+
	const TOKEN_COMMENTARY              = 13; // {[^}]*}

	// State variables
	var pos    = 0;
	var token  = 0;
	var tokenValue;
	var inside_tag = false;
	var retVal = Array();
	var item = null;

	// Skip the blank and newline characters
	function skipBlank()
	{
		while(pos<pgnString.length) {
			switch(pgnString.charAt(pos)) {
				case " ":
				case "\n":
				case "\r":
				case "\t":
				case "\v":
					++pos;
					break;
				default:
					return;
			}
		}
	}

	// Return true if xmin <= x <= xmax
	function inRange(x, xmin, xmax) {
		return (x>=xmin) && (x<=xmax);
	}

	// Convert an incorrectly formated NAG into its correspond numerical code
	function convertIncorrectNag(nagString) {
		switch(nagString) {
			case "!!": tokenValue=3; return;
			case "!" : tokenValue=1; return;
			case "!?": tokenValue=5; return;
			case "?!": tokenValue=6; return;
			case "?" : tokenValue=2; return;
			case "??": tokenValue=4; return;
			default:
				break;
		}
	}

	// Read an integer from the stream
	function consumeInteger()
	{
		tokenValue = 0;
		var oneCharacterConsumed = false;
		while(pos<pgnString.length) {
			var charCode = pgnString.charCodeAt(pos);
			if(!inRange(charCode, 48, 57)) {
				return oneCharacterConsumed;
			}
			oneCharacterConsumed = true;
			tokenValue = tokenValue*10 + (charCode-48);
			++pos;
		}
		if(!oneCharacterConsumed) {
			throw new PGNException(pgnString, pos, "integer expected");
		}
		return true;
	}

	// Read a string
	function consumeString(stopChar)
	{
		var pos0 = pos;
		while(pos<pgnString.length) {
			if(pgnString.charAt(pos)==stopChar) {
				tokenValue = pgnString.substr(pos0, pos-pos0);
				++pos;
				return true;
			}
			++pos;
		}
		return false;
	}

	// Read a tag ID
	function consumeTagId()
	{
		var pos0 = pos;
		while(pos<pgnString.length) {
			var charCode = pgnString.charCodeAt(pos);
			if(!(inRange(charCode, 65, 90) || inRange(charCode, 97, 122) || charCode==95)) {
				if(pos==pos0) {
					throw new PGNException(pgnString, pos, "tag ID expected");
				}
				tokenValue = pgnString.substr(pos0, pos-pos0);
				return true;
			}
			++pos;
		}
		return false;
	}

	// Read a move
	function consumeMove()
	{
		var pos0 = pos;
		while(pos<pgnString.length) {
			var charCode = pgnString.charCodeAt(pos);
			if(!(
				inRange(charCode, 97, 104) || inRange(charCode, 49, 56) ||
				charCode==35 || charCode==43 || charCode==45 || charCode==61 || charCode==66 || charCode==75 ||
				charCode==78 || charCode==79 || charCode==81 || charCode==82 || charCode==120
			)) {
				if(pos==pos0) {
					throw new PGNException(pgnString, pos, "move expected");
				}
				tokenValue = pgnString.substr(pos0, pos-pos0);
				return true;
			}
			++pos;
		}
		return false;
	}

	// Read a token from the stream
	// Return false if no token have been read (meaning that EOF have been reached)
	function consumeToken()
	{
		skipBlank();
		if(pos>=pgnString.length) {
			return false;
		}
		switch(pgnString.charAt(pos)) {

			// One-character tokens
			case "[": token=TOKEN_OPENING_SQUARE_BRACKETS; ++pos; inside_tag=true ; return true;
			case "]": token=TOKEN_CLOSING_SQUARE_BRACKETS; ++pos; inside_tag=false; return true;
			case "(": token=TOKEN_OPENING_PARENTHESIS    ; ++pos; return true;
			case ")": token=TOKEN_CLOSING_PARENTHESIS    ; ++pos; return true;
			case "*": token=TOKEN_END_OF_GAME            ; ++pos; tokenValue=UNFINISHED; return true;

			// Discriminate between . and ...
			case ".":
				if(pos+2<pgnString.length && pgnString.substr(pos+1, 2)=="..") {
					token = TOKEN_DOT_3;
					pos += 3;
					return true;
				}
				else {
					token = TOKEN_DOT_1;
					++pos;
					return true;
				}
			case "\u2026":
				token = TOKEN_DOT_3;
				++pos;
				return true;

			// Nags correctly formated
			case "$":
				token = TOKEN_NAG;
				++pos;
				skipBlank();
				return consumeInteger();

			// Nags incorrectly formated
			case "!":
			case "?":
				token = TOKEN_NAG;
				tokenValue = pgnString.charAt(pos);
				++pos;
				if(pos<pgnString.length && (pgnString.charAt(pos)=="!" || pgnString.charAt(pos)=="?")) {
					tokenValue += pgnString.charAt(pos);
					++pos;
				}
				convertIncorrectNag(tokenValue);
				return true;

			// Integers starting either by 1 or 0
			case "0":
				if(pos+2<pgnString.length && pgnString.substr(pos+1, 2)=="-1") {
					token      = TOKEN_END_OF_GAME;
					tokenValue = BLACK_WINS;
					pos += 3;
					return true;
				}
				else {
					token = TOKEN_MOVE_NUMBER;
					return consumeInteger();
				}
			case "1":
				if(pos+2<pgnString.length && pgnString.substr(pos+1, 2)=="-0") {
					token      = TOKEN_END_OF_GAME;
					tokenValue = WHITE_WINS;
					pos += 3;
					return true;
				}
				else if(pos+6<pgnString.length && pgnString.substr(pos+1, 6)=="/2-1/2") {
					token      = TOKEN_END_OF_GAME;
					tokenValue = DRAW;
					pos += 7;
					return true;
				}
				else {
					token = TOKEN_MOVE_NUMBER;
					return consumeInteger();
				}

			// Commentary
			case "{":
				token = TOKEN_COMMENTARY;
				++pos;
				return consumeString("}");

			// Tag value
			case "\"":
				token = TOKEN_TAG_VALUE;
				++pos;
				return consumeString("\"");

			// Default case
			default:

				// [2-9] -> move number
				if(inRange(pgnString.charCodeAt(pos), 50, 57)) {
					token = TOKEN_MOVE_NUMBER;
					return consumeInteger();
				}

				// [a-zA-Z] -> move or tag ID
				else if(inRange(pgnString.charCodeAt(pos), 65, 90) || inRange(pgnString.charCodeAt(pos), 97, 122)) {
					if(inside_tag) {
						token = TOKEN_TAG_ID;
						return consumeTagId();
					}
					else {
						token = TOKEN_MOVE;
						return consumeMove();
					}
				}

				// Unexpected character
				else {
					throw new PGNException(pgnString, pos, "unexpected character (code="+pgnString.charCodeAt(pos)+")");
				}
		}
	}

	// Token loop
	var tagsAllowed = true;
	var currentNode = null; // Might be either a node or a variation
	var nodeStack   = Array();
	while(consumeToken()) {

		// Create a new item if necessary
		if(item==null) {
			item        = new PGNItem();
			currentNode = item.startMainVariation();
		}

		// Tag processing
		if(token==TOKEN_OPENING_SQUARE_BRACKETS) {
			if(!tagsAllowed) {
				throw new PGNException(pgnString, pos, "unexpected tag");
			}
			if(!(consumeToken() && token==TOKEN_TAG_ID)) {
				throw new PGNException(pgnString, pos, "tag ID expected");
			}
			var tagID = tokenValue;
			if(!(consumeToken() && token==TOKEN_TAG_VALUE)) {
				throw new PGNException(pgnString, pos, "tag value expected");
			}
			var tagValue = tokenValue;
			if(!(consumeToken() && token==TOKEN_CLOSING_SQUARE_BRACKETS)) {
				throw new PGNException(pgnString, pos, "end of tag token expected");
			}
			// TODO: filter tagID
			item[tagID] = tagValue;
		}

		// Unexpected tag component
		else if(token==TOKEN_TAG_ID                 ) { throw new PGNException(pgnString, pos, "unexpected tag ID"   ); }
		else if(token==TOKEN_TAG_VALUE              ) { throw new PGNException(pgnString, pos, "unexpected tag value"); }
		else if(token==TOKEN_CLOSING_SQUARE_BRACKETS) { throw new PGNException(pgnString, pos, "unexpected end of tag token"); }

		// Commentary
		else if(token==TOKEN_COMMENTARY) {
			tagsAllowed = false;
			currentNode.commentary = tokenValue;
		}

		// Nag
		else if(token==TOKEN_NAG) {
			tagsAllowed = false;
			currentNode.nags.push(tokenValue);
		}

		// Move
		else if(token==TOKEN_MOVE) {
			tagsAllowed = false;
			try {
				var move = parseNotation(currentNode.position, tokenValue, false);
				currentNode = currentNode.play(move);
			}
			catch(err) {
				if(err instanceof ParsingException) {
					throw new PGNException(pgnString, pos, "illegal or badly formated move");
				}
			}
		}

		// Start of variation
		else if(token==TOKEN_OPENING_PARENTHESIS) {
			tagsAllowed = false;
			if(!(currentNode instanceof PGNNode)) {
				throw new PGNException(pgnString, pos, "unexpected start of variation");
			}
			nodeStack.push(currentNode);
			currentNode = currentNode.startVariation();
		}

		// End of variation
		else if(token==TOKEN_CLOSING_PARENTHESIS) {
			tagsAllowed = false;
			if(nodeStack.length==0) {
				throw new PGNException(pgnString, pos, "unexpected end of variation");
			}
			currentNode = nodeStack[nodeStack.length-1];
			nodeStack.pop();
		}

		// Do not care about TOKEN_DOT_1, TOKEN_DOT_3 and TOKEN_MOVE_NUMBER

		// End-of-game
		else if(token==TOKEN_END_OF_GAME) {
			if(nodeStack.length>0) {
				throw new PGNException(pgnString, pos, "unexpected end of game (there are pending variations)");
			}
			item.result = tokenValue;
			retVal.push(item);
			item        = null;
			tagsAllowed = true;
			currentNode = null;
		}
	}
	return retVal;
}
