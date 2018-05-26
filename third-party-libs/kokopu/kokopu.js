(function(f){if(typeof exports==="object"&&typeof module!=="undefined"){module.exports=f()}else if(typeof define==="function"&&define.amd){define([],f)}else{var g;if(typeof window!=="undefined"){g=window}else if(typeof global!=="undefined"){g=global}else if(typeof self!=="undefined"){g=self}else{g=this}g.kokopu = f()}})(function(){var define,module,exports;return (function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


exports.i18n = require('./src/i18n');
exports.exception = require('./src/exception');

var util = require('./src/util');
exports.forEachSquare = util.forEachSquare;
exports.squareColor = util.squareColor;
exports.squareToCoordinates = util.squareToCoordinates;
exports.coordinatesToSquare = util.coordinatesToSquare;

exports.isMoveDescriptor = require('./src/movedescriptor').isInstanceOf;

exports.Position = require('./src/position').Position;
exports.Game = require('./src/game').Game;

var pgn = require('./src/pgn');
exports.pgnRead = pgn.pgnRead;

},{"./src/exception":3,"./src/game":4,"./src/i18n":5,"./src/movedescriptor":6,"./src/pgn":7,"./src/position":8,"./src/util":15}],2:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


// Colors
exports.WHITE = 0;
exports.BLACK = 1;

// Pieces
exports.KING   = 0;
exports.QUEEN  = 1;
exports.ROOK   = 2;
exports.BISHOP = 3;
exports.KNIGHT = 4;
exports.PAWN   = 5;

// Colored pieces
exports.WK =  0; exports.BK =  1;
exports.WQ =  2; exports.BQ =  3;
exports.WR =  4; exports.BR =  5;
exports.WB =  6; exports.BB =  7;
exports.WN =  8; exports.BN =  9;
exports.WP = 10; exports.BP = 11;

// Special square values
exports.EMPTY = -1;
exports.INVALID = -2;

// Game result
exports.WHITE_WINS = 0;
exports.BLACK_WINS = 1;
exports.DRAW = 2;
exports.LINE = 3;


// -----------------------------------------------------------------------------
// Conversion API constants (strings) <-> internal constants (integers)
// -----------------------------------------------------------------------------

var COLOR_SYMBOL = 'wb'      ;
var PIECE_SYMBOL = 'kqrbnp'  ;
var RANK_SYMBOL  = '12345678';
var FILE_SYMBOL  = 'abcdefgh';
var RESULT_SYMBOL = ['1-0', '0-1', '1/2-1/2', '*'];

exports.colorToString  = function(color ) { return COLOR_SYMBOL [color ]; };
exports.pieceToString  = function(piece ) { return PIECE_SYMBOL [piece ]; };
exports.rankToString   = function(rank  ) { return RANK_SYMBOL  [rank  ]; };
exports.fileToString   = function(file  ) { return FILE_SYMBOL  [file  ]; };
exports.resultToString = function(result) { return RESULT_SYMBOL[result]; };

exports.colorFromString  = function(color ) { return COLOR_SYMBOL .indexOf(color ); };
exports.pieceFromString  = function(piece ) { return PIECE_SYMBOL .indexOf(piece ); };
exports.rankFromString   = function(rank  ) { return RANK_SYMBOL  .indexOf(rank  ); };
exports.fileFromString   = function(file  ) { return FILE_SYMBOL  .indexOf(file  ); };
exports.resultFromString = function(result) { return RESULT_SYMBOL.indexOf(result); };

exports.squareToString = function(square) {
	return FILE_SYMBOL[square % 16] + RANK_SYMBOL[Math.floor(square / 16)];
};

exports.squareFromString = function(square) {
	if(!/^[a-h][1-8]$/.test(square)) {
		return -1;
	}
	var file = FILE_SYMBOL.indexOf(square[0]);
	var rank = RANK_SYMBOL.indexOf(square[1]);
	return rank*16 + file;
};

exports.coloredPieceToString = function(cp) {
	return COLOR_SYMBOL[cp % 2] + PIECE_SYMBOL[Math.floor(cp / 2)];
};

exports.coloredPieceFromString = function(cp) {
	if(!/^[wb][kqrbnp]$/.test(cp)) {
		return -1;
	}
	var color = COLOR_SYMBOL.indexOf(cp[0]);
	var piece = PIECE_SYMBOL.indexOf(cp[1]);
	return piece*2 + color;
};

},{}],3:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


/**
 * Exception thrown when an invalid argument is passed to a function.
 *
 * @param {string} fun
 */
exports.IllegalArgument = function(fun) {
	this.fun = fun;
};


/**
 * Exception thrown by the FEN parsing function.
 *
 * @param {string} fen String whose parsing leads to an error.
 * @param {string} message Human-readable error message.
 * @param ...
 */
exports.InvalidFEN = function(fen, message) {
	this.fen = fen;
	this.message = message;
	for(var i=2; i<arguments.length; ++i) {
		var re = new RegExp('%' + (i-1) + '\\$s');
		this.message = this.message.replace(re, arguments[i]);
	}
};


/**
 * Exception thrown by the move notation parsing function.
 *
 * @param {string} fen FEN-representation of the position used to try to parse the move notation.
 * @param {string} notation String whose parsing leads to an error.
 * @param {string} message Human-readable error message.
 * @param ...
 */
exports.InvalidNotation = function(fen, notation, message) {
	this.fen = fen;
	this.notation = notation;
	this.message = message;
	for(var i=3; i<arguments.length; ++i) {
		var re = new RegExp('%' + (i-2) + '\\$s');
		this.message = this.message.replace(re, arguments[i]);
	}
};


/**
 * Exception thrown by the PGN parsing functions.
 *
 * @param {string} pgn String whose parsing leads to an error.
 * @param {number} index Character index in the string where the parsing fails (`-1` if no particular character is targeted).
 * @param {string} message Human-readable error message.
 * @param ...
 */
exports.InvalidPGN = function(pgn, index, message) {
	this.pgn = pgn;
	this.index = index;
	this.message = message;
	for(var i=3; i<arguments.length; ++i) {
		var re = new RegExp('%' + (i-2) + '\\$s');
		this.message = this.message.replace(re, arguments[i]);
	}
};

},{}],4:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('./basetypes');
var exception = require('./exception');
var i18n = require('./i18n');

var Position = require('./position').Position;



// -----------------------------------------------------------------------------
// Game
// -----------------------------------------------------------------------------

/**
 * Chess game, with some headers, a main variation, and a result.
 */
var Game = exports.Game = function() {
	this._playerName  = [undefined, undefined];
	this._playerElo   = [undefined, undefined];
	this._playerTitle = [undefined, undefined];
	this._event     = undefined;
	this._round     = undefined;
	this._date      = undefined;
	this._site      = undefined;
	this._annotator = undefined;
	this._result    = bt.LINE;

	this._initialPosition = new Position();
	this._fullMoveNumber = 1;
	this._mainVariation = new Variation(this, true);
};


/**
 * Get/set the player name.
 */
Game.prototype.playerName = function(color, value) {
	color = bt.colorFromString(color);
	if(color < 0) { throw new exception.IllegalArgument('Game#playerName()'); }
	if(arguments.length === 1) { return this._playerName[color]; }
	else { this._playerName[color] = value; }
};


/**
 * Get/set the player elo.
 */
Game.prototype.playerElo = function(color, value) {
	color = bt.colorFromString(color);
	if(color < 0) { throw new exception.IllegalArgument('Game#playerElo()'); }
	if(arguments.length === 1) { return this._playerElo[color]; }
	else { this._playerElo[color] = value; }
};


/**
 * Get/set the player title.
 */
Game.prototype.playerTitle = function(color, value) {
	color = bt.colorFromString(color);
	if(color < 0) { throw new exception.IllegalArgument('Game#playerTitle()'); }
	if(arguments.length === 1) { return this._playerTitle[color]; }
	else { this._playerTitle[color] = value; }
};


/**
 * Get/set the event.
 */
Game.prototype.event = function(value) {
	if(arguments.length === 0) { return this._event; }
	else { this._event = value; }
};


/**
 * Get/set the round.
 */
Game.prototype.round = function(value) {
	if(arguments.length === 0) { return this._round; }
	else { this._round = value; }
};


/**
 * Get/set the date of the game.
 */
Game.prototype.date = function(value) {
	if(arguments.length === 0) {
		return this._date;
	}
	else if(value === undefined || value === null) {
		this._date = undefined;
	}
	else if(value instanceof Date) {
		this._date = value;
	}
	else if(typeof value === 'object' && typeof value.year === 'number' && typeof value.month === 'number') {
		this._date = { year: value.year, month: value.month };
	}
	else if(typeof value === 'object' && typeof value.year === 'number' && (value.month === undefined || value.month === null)) {
		this._date = { year: value.year };
	}
	else {
		throw new exception.IllegalArgument('Game#date()');
	}
};


/**
 * Get/set where the game takes place.
 */
Game.prototype.site = function(value) {
	if(arguments.length === 0) { return this._site; }
	else { this._site = value; }
};


/**
 * Get/set the annotator.
 */
Game.prototype.annotator = function(value) {
	if(arguments.length === 0) { return this._annotator; }
	else { this._annotator = value; }
};


/**
 * Get/set the result of the game.
 */
Game.prototype.result = function(value) {
	if(arguments.length === 0) {
		return bt.resultToString(this._result);
	}
	else {
		var result = bt.resultFromString(value);
		if(result < 0) {
			throw new exception.IllegalArgument('Game#result()');
		}
		this._result = result;
	}
};


/**
 * Get/set the initial position of the game. WARNING: the setter resets the main variation.
 *
 * @param {Position} initialPosition (SETTER only)
 * @param {number?} fullMoveNumber (SETTER only)
 * @returns {Position} (GETTER only)
 */
Game.prototype.initialPosition = function(initialPosition, fullMoveNumber) {
	if(arguments.length === 0) {
		return this._initialPosition;
	}
	else {
		if(!(initialPosition instanceof Position)) {
			throw new exception.IllegalArgument('Game#initialPosition()');
		}
		if(arguments.length === 1) {
			fullMoveNumber = 1;
		}
		else if(typeof fullMoveNumber !== 'number') {
			throw new exception.IllegalArgument('Game#initialPosition()');
		}
		this._initialPosition = initialPosition;
		this._fullMoveNumber = fullMoveNumber;
		this._mainVariation = new Variation(this, true);
	}
};


/**
 * Main variation.
 *
 * @returns {Variation}
 */
Game.prototype.mainVariation = function() {
	return this._mainVariation;
};



// -----------------------------------------------------------------------------
// Node
// -----------------------------------------------------------------------------

/**
 * Represent one move in the tree structure formed by a chess game with multiple variations.
 *
 * @param {Variation} parentVariation
 * @param {Node?} previous
 * @param {string} move SAN notation (or `'--'` for a null-move).
 * @throws {InvalidNotation} If the move notation cannot be parsed.
 */
function Node(parentVariation, previous, move) {

	this._parentVariation = parentVariation;  // Variation to which the current node belongs (always a `Variation` object).
	this._previous = previous; // Previous node (always a `Node` object if defined).
	this._next = undefined; // Next node (always a `Node` object if defined).
	this._position = new Position(previous === undefined ? parentVariation.initialPosition() : previous.position());

	// Null move.
	if(move === '--') {
		this._notation = '--';
		if(!this._position.playNullMove()) {
			throw new exception.InvalidNotation(this._position, '--', i18n.ILLEGAL_NULL_MOVE);
		}
	}

	// Regular move.
	else {
		var moveDescriptor = this._position.notation(move);
		this._notation = this._position.notation(moveDescriptor);
		this._position.play(moveDescriptor);
	}

	// Full-move number
	if(previous === undefined) {
		this._fullMoveNumber = parentVariation.initialFullMoveNumber();
	}
	else {
		this._fullMoveNumber = previous._fullMoveNumber + (previous.position().turn() === 'w' ? 1 : 0);
	}

	// Variations that could be played instead of the current move.
	this._variations = [];

	// Annotations and comments associated to the current move.
	this._nags = {};
	this._tags = {};
	this._comment = undefined;
	this._isLongComment = false;
}


/**
 * SAN representation of the move associated to the current node.
 *
 * @returns {string}
 */
Node.prototype.notation = function() {
	return this._notation;
};


/**
 * Chess position before the current move.
 *
 * @returns {Position}
 */
Node.prototype.positionBefore = function() {
	return this._previous === undefined ? this._parentVariation.initialPosition() : this._previous.position();
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
	return this.positionBefore().turn();
};


/**
 * Next move within the same variation.
 *
 * @returns {Node?} `undefined` if the current move is the last move of the variation.
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
	return this._variations.slice(0);
};


/**
 * Return the NAGs associated to the current move.
 *
 * @returns {number[]}
 */
Node.prototype.nags = function() {
	var res = [];
	for(var key in this._nags) {
		if(this._nags[key]) {
			res.push(key);
		}
	}
	return res;
};


/**
 * Check whether the current has the given NAG or not.
 *
 * @param {number} nag
 * @returns {boolean}
 */
Node.prototype.hasNag = function(nag) {
	return !!this._nags[nag];
};


/**
 * Add the given NAG to the current move.
 *
 * @param {number} nag
 */
Node.prototype.addNag = function(nag) {
	this._nags[nag] = true;
};


/**
 * Remove the given NAG from the current move.
 *
 * @param {number} nag
 */
Node.prototype.removeNag = function(nag) {
	delete this._nags[nag];
};


/**
 * Return the keys of the tags associated to the current move.
 *
 * @returns {string[]}
 */
Node.prototype.tags = function() {
	var res = [];
	for(var key in this._tags) {
		if(this._tags[key] !== undefined) {
			res.push(key);
		}
	}
	return res;
};


/**
 * Get/set the value that is defined for the tag corresponding to the given key on the current move.
 *
 * @param {string} key
 * @param {string} value (SETTER only)
 * @returns {string?} `undefined` if no value is defined for this tag on the current move. (GETTER only)
 */
Node.prototype.tag = function(key, value) {
	if(arguments.length === 1) {
		return this._tags[key];
	}
	else {
		this._tags[key] = value;
	}
};


/**
 * Get/set the text comment associated to the current move.
 *
 * @param {string} value (SETTER only)
 * @param {boolean?} isLongComment (SETTER only)
 * @returns {string?} `undefined` if no comment is defined for the move. (GETTER only)
 */
Node.prototype.comment = function(value, isLongComment) {
	if(arguments.length === 0) {
		return this._comment;
	}
	else {
		this._comment = value;
		this._isLongComment = isLongComment;
	}
};


/**
 * Whether the text comment associated to the current move is long or short.
 *
 * @returns {boolean}
 */
Node.prototype.isLongComment = function() {
	return this._isLongComment && this._parentVariation.isLongVariation();
};


/**
 * Play the given move after the current one.
 *
 * @param {string} move SAN notation (or `'--'` for a null-move).
 * @returns {Node} A new node object, to represents the new move.
 * @throws {InvalidNotation} If the move notation cannot be parsed.
 */
Node.prototype.play = function(move) {
	this._next = new Node(this._parentVariation, this, move);
	return this._next;
};


/**
 * Create a new variation that can be played instead of the current move.
 */
Node.prototype.addVariation = function(isLongVariation) {
	this._variations.push(new Variation(this, isLongVariation));
	return this._variations[this._variations.length - 1];
};



// -----------------------------------------------------------------------------
// Variation
// -----------------------------------------------------------------------------

/**
 * Represent one variation in the tree structure formed by a chess game, meaning
 * a starting chess position and list of played consecutively from this position.
 *
 * @param {Node|Game} parent Parent node in the tree structure.
 * @param {boolean} isLongVariation Whether the variation is long or short.
 */
function Variation(parent, isLongVariation) {

	this._parent = parent;   // Either a `Node` or a `Game` object.
	this._first = undefined; // First node of the variation (always a `Node` object if defined).

	// Whether the variation is or not to a "long-variation".
	this._isLongVariation = isLongVariation;

	// Annotations and comments associated to the current variation.
	this._nags = {};
	this._tags = {};
	this._comment = undefined;
	this._isLongComment = false;
}


/**
 * Whether the current variation is considered as a "long" variation, i.e. a variation that
 * should be displayed in an isolated block.
 *
 * @returns {boolean}
 */
Variation.prototype.isLongVariation = function() {
	return this._isLongVariation && (this._parent instanceof Game || this._parent._parentVariation.isLongVariation());
};


/**
 * Chess position at the beginning of the variation.
 *
 * @returns {Position}
 */
Variation.prototype.initialPosition = function() {
	return (this._parent instanceof Game) ? this._parent.initialPosition() : this._parent.positionBefore();
};


/**
 * Full-move number at the beginning of the variation.
 *
 * @returns {number}
 */
Variation.prototype.initialFullMoveNumber = function() {
	return this._parent._fullMoveNumber; // REMARK: `this._parent` can be `Game` or `Node`.
};


/**
 * First move within the variation.
 *
 * @returns {Node?} `undefined` if the variation is empty.
 */
Variation.prototype.first = function() {
	return this._first;
};


// Methods inherited from `Node`.
Variation.prototype.nags          = Node.prototype.nags         ;
Variation.prototype.hasNag        = Node.prototype.hasNag       ;
Variation.prototype.addNag        = Node.prototype.addNag       ;
Variation.prototype.removeNag     = Node.prototype.removeNag    ;
Variation.prototype.tags          = Node.prototype.tags         ;
Variation.prototype.tag           = Node.prototype.tag          ;
Variation.prototype.comment       = Node.prototype.comment      ;
Variation.prototype.isLongComment = function() {
	return this._isLongComment && this.isLongVariation();
};


/**
 * Play the given move as the first move of the variation.
 *
 * @param {string} move SAN notation (or `'--'` for a null-move).
 * @returns {Node} A new node object, to represents the new move.
 * @throws {InvalidNotation} If the move notation cannot be parsed.
 */
Variation.prototype.play = function(move) {
	this._first = new Node(this, undefined, move);
	return this._first;
};

},{"./basetypes":2,"./exception":3,"./i18n":5,"./position":8}],5:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


// Ordinal integers (from 1 to 8).
exports.ORDINALS = ['1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th'];

// FEN parsing error messages
exports.WRONG_NUMBER_OF_FEN_FIELDS                = 'A FEN string must contain exactly 6 space-separated fields.';
exports.WRONG_NUMBER_OF_SUBFIELDS_IN_BOARD_FIELD  = 'The 1st field of a FEN string must contain exactly 8 `/`-separated subfields.';
exports.UNEXPECTED_CHARACTER_IN_BOARD_FIELD       = 'Unexpected character in the 1st field of the FEN string: `%1$s`.';
exports.UNEXPECTED_END_OF_SUBFIELD_IN_BOARD_FIELD = 'The %1$s subfield of the FEN string 1st field is unexpectedly short.';
exports.INVALID_TURN_FIELD                        = 'The 2nd field of a FEN string must be either `w` or `b`.';
exports.INVALID_CASTLING_FIELD                    = 'The 3rd field of a FEN string must be either `-` or a list of characters among `K`, `Q`, `k` and `q` (in this order).';
exports.INVALID_EN_PASSANT_FIELD                  = 'The 4th field of a FEN string must be either `-` or a square from the 3rd or 6th rank where en-passant is allowed.';
exports.WRONG_RANK_IN_EN_PASSANT_FIELD            = 'The rank number indicated in the FEN string 4th field is inconsistent with respect to the 2nd field.';
exports.INVALID_MOVE_COUNTING_FIELD               = 'The %1$s field of a FEN string must be a number.';

// Notation parsing error messages
exports.INVALID_MOVE_NOTATION_SYNTAX        = 'The syntax of the move notation is invalid.';
exports.ILLEGAL_POSITION                    = 'The position is not legal.';
exports.ILLEGAL_QUEEN_SIDE_CASTLING         = 'Queen-side castling is not legal in the considered position.';
exports.ILLEGAL_KING_SIDE_CASTLING          = 'King-side castling is not legal in the considered position.';
exports.NO_PIECE_CAN_MOVE_TO                = 'No %1$s can move to %2$s.';
exports.NO_PIECE_CAN_MOVE_TO_DISAMBIGUATION = 'No %1$s on the specified rank/file can move to %2$s.';
exports.REQUIRE_DISAMBIGUATION              = 'Cannot determine uniquely which %1$s is supposed to move to %2$s.';
exports.WRONG_DISAMBIGUATION_SYMBOL         = 'Wrong disambiguation symbol (expected: `%1$s`, observed: `%2$s`).';
exports.TRYING_TO_CAPTURE_YOUR_OWN_PIECES   = 'Capturing its own pieces is not legal.';
exports.INVALID_CAPTURING_PAWN_MOVE         = 'Invalid capturing pawn move.';
exports.INVALID_NON_CAPTURING_PAWN_MOVE     = 'Invalid non-capturing pawn move.';
exports.NOT_SAFE_FOR_WHITE_KING             = 'This move would put let the white king in check.';
exports.NOT_SAFE_FOR_BLACK_KING             = 'This move would put let the black king in check.';
exports.MISSING_PROMOTION                   = 'A promoted piece must be specified for this move.';
exports.MISSING_PROMOTION_SYMBOL            = 'Character `=` is required to specify a promoted piece.';
exports.INVALID_PROMOTED_PIECE              = '%1$s cannot be specified as a promoted piece.';
exports.ILLEGAL_PROMOTION                   = 'Specifying a promoted piece is illegal for this move.';
exports.ILLEGAL_NULL_MOVE                   = 'Cannot play a null-move in this position.';
exports.MISSING_CAPTURE_SYMBOL              = 'Capture symbol `x` is missing.';
exports.INVALID_CAPTURE_SYMBOL              = 'This move is not a capture move.';
exports.WRONG_CHECK_CHECKMATE_SYMBOL        = 'Wrong check/checkmate symbol (expected: `%1$s`, observed: `%2$s`).';

// PGN parsing error messages
exports.INVALID_PGN_TOKEN               = 'Unrecognized character or group of characters.';
exports.INVALID_MOVE_IN_PGN_TEXT        = 'Invalid move (%1$s). %2$s';
exports.INVALID_FEN_IN_PGN_TEXT         = 'Invalid FEN string in the initial position header. %1$s';
exports.UNEXPECTED_PGN_HEADER           = 'Unexpected PGN game header.';
exports.UNEXPECTED_BEGIN_OF_VARIATION   = 'Unexpected begin of variation.';
exports.UNEXPECTED_END_OF_VARIATION     = 'Unexpected end of variation.';
exports.UNEXPECTED_END_OF_GAME          = 'Unexpected end of game: there are pending variations.';
exports.UNEXPECTED_END_OF_TEXT          = 'Unexpected end of text: there is a pending game.';
exports.INVALID_GAME_INDEX              = 'Game index %1$s is invalid (only %2$s game(s) found in the PGN data).';

},{}],6:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('./basetypes');
var exception = require('./exception');


var CASTLING_FLAG   = 0x01;
var EN_PASSANT_FLAG = 0x02;
var CAPTURE_FLAG    = 0x04;
var PROMOTION_FLAG  = 0x08;


exports.make = function(from, to, color, movingPiece, capturedPiece) {
	var flags = capturedPiece >= 0 ? CAPTURE_FLAG : 0x00;
	var movingColoredPiece = movingPiece*2 + color;
	return new MoveDescriptor(flags, from, to, movingColoredPiece, movingColoredPiece, capturedPiece, -1, -1);
};


exports.makeCastling = function(from, to, rookFrom, rookTo, color) {
	var movingKing = bt.KING*2 + color;
	var movingRook = bt.ROOK*2 + color;
	return new MoveDescriptor(CASTLING_FLAG, from, to, movingKing, movingKing, movingRook, rookFrom, rookTo);
};


exports.makeEnPassant = function(from, to, enPassantSquare, color) {
	var flags = EN_PASSANT_FLAG /* jshint bitwise:false */ | CAPTURE_FLAG /* jshint bitwise:true */;
	var movingPawn = bt.PAWN*2 + color;
	var capturedPawn = bt.PAWN*2 + 1 - color;
	return new MoveDescriptor(flags, from, to, movingPawn, movingPawn, capturedPawn, enPassantSquare, -1);
};


exports.makePromotion = function(from, to, color, promotion, capturedPiece) {
	var flags = PROMOTION_FLAG /* jshint bitwise:false */ | (capturedPiece >= 0 ? CAPTURE_FLAG : 0x00) /* jshint bitwise:true */;
	var movingPawn = bt.PAWN*2 + color;
	var finalPiece = promotion*2 + color;
	return new MoveDescriptor(flags, from, to, movingPawn, finalPiece, capturedPiece, -1, -1);
};


/**
 * @classdesc
 * Hold the raw information that is required to play a move in a given position.
 */
function MoveDescriptor(flags, from, to, movingPiece, finalPiece, optionalPiece, optionalSquare1, optionalSquare2) {
	this._type            = flags          ;
	this._from            = from           ;
	this._to              = to             ;
	this._movingPiece     = movingPiece    ;
	this._finalPiece      = finalPiece     ;
	this._optionalPiece   = optionalPiece  ; // Captured piece in case of capture, moving rook in case of castling.
	this._optionalSquare1 = optionalSquare1; // Rook-from or en-passant square.
	this._optionalSquare2 = optionalSquare2; // Rook-to.
}


/**
 * Whether the given object is a move descriptor or not.
 */
exports.isInstanceOf = function(obj) {
	return obj instanceof MoveDescriptor;
};


MoveDescriptor.prototype.isCastling = function() {
	return (this._type /* jshint bitwise:false */ & CASTLING_FLAG /* jshint bitwise:true */) !== 0;
};


MoveDescriptor.prototype.isEnPassant = function() {
	return (this._type /* jshint bitwise:false */ & EN_PASSANT_FLAG /* jshint bitwise:true */) !== 0;
};


MoveDescriptor.prototype.isCapture = function() {
	return (this._type /* jshint bitwise:false */ & CAPTURE_FLAG /* jshint bitwise:true */) !== 0;
};


MoveDescriptor.prototype.isPromotion = function() {
	return (this._type /* jshint bitwise:false */ & PROMOTION_FLAG /* jshint bitwise:true */) !== 0;
};


MoveDescriptor.prototype.from = function() {
	return bt.squareToString(this._from);
};


MoveDescriptor.prototype.to = function() {
	return bt.squareToString(this._to);
};


MoveDescriptor.prototype.color = function() {
	return bt.colorToString(this._movingPiece % 2);
};


MoveDescriptor.prototype.movingPiece = function() {
	return bt.pieceToString(Math.floor(this._movingPiece / 2));
};


MoveDescriptor.prototype.movingColoredPiece = function() {
	return bt.coloredPieceToString(this._movingPiece);
};


MoveDescriptor.prototype.capturedPiece = function() {
	if(!this.isCapture()) { throw new exception.IllegalArgument('MoveDescriptor#capturedPiece()'); }
	return bt.pieceToString(Math.floor(this._optionalPiece / 2));
};


MoveDescriptor.prototype.capturedColoredPiece = function() {
	if(!this.isCapture()) { throw new exception.IllegalArgument('MoveDescriptor#capturedColoredPiece()'); }
	return bt.coloredPieceToString(this._optionalPiece);
};


MoveDescriptor.prototype.rookFrom = function() {
	if(!this.isCastling()) { throw new exception.IllegalArgument('MoveDescriptor#rookFrom()'); }
	return bt.squareToString(this._optionalSquare1);
};


MoveDescriptor.prototype.rookTo = function() {
	if(!this.isCastling()) { throw new exception.IllegalArgument('MoveDescriptor#rookTo()'); }
	return bt.squareToString(this._optionalSquare2);
};


MoveDescriptor.prototype.enPassantSquare = function() {
	if(!this.isEnPassant()) { throw new exception.IllegalArgument('MoveDescriptor#enPassantSquare()'); }
	return bt.squareToString(this._optionalSquare1);
};


MoveDescriptor.prototype.promotion = function() {
	if(!this.isPromotion()) { throw new exception.IllegalArgument('MoveDescriptor#promotion()'); }
	return bt.pieceToString(Math.floor(this._finalPiece / 2));
};


MoveDescriptor.prototype.coloredPromotion = function() {
	if(!this.isPromotion()) { throw new exception.IllegalArgument('MoveDescriptor#coloredPromotion()'); }
	return bt.coloredPieceToString(this._finalPiece);
};


MoveDescriptor.prototype.toString = function() {
	var result = bt.squareToString(this._from) + bt.squareToString(this._to);
	if(this.isPromotion()) {
		result += this.promotion().toUpperCase();
	}
	return result;
};

},{"./basetypes":2,"./exception":3}],7:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var exception = require('./exception');
var i18n = require('./i18n');

var Position = require('./position').Position;
var Game = require('./game').Game;


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


/**
 * Parse a header value, unescaping special characters.
 * @param {string} rawHeaderValue
 * @returns {string}
 */
function parseHeaderValue(rawHeaderValue) {
	return rawHeaderValue.replace(/\\([\\"\[\]])/g, '$1');
}


/**
 * Parse a comment, unescaping special characters, and looking for the `[%key value]` tags.
 *
 * @param {string} rawComment String to parse.
 * @returns {{comment:string, tags:object}}
 */
function parseCommentValue(rawComment) {
	rawComment = rawComment.replace(/\\([\{\}\\])/g, '$1');

	var tags = {};

	// Find and remove the tags from the raw comment.
	var comment = rawComment.replace(/\[%([a-zA-Z]+) ([^\[\]]+)\]/g, function(match, p1, p2) {
		tags[p1] = p2;
		return ' ';
	});

	// Trim the comment and collapse sequences of space characters into 1 character only.
	comment = comment.replace(/^\s+|\s+$/g, '').replace(/\s+/g, ' ');
	if(comment === '') {
		comment = undefined;
	}

	// Return the result
	return { comment:comment, tags:tags };
}


// PGN token types
var /* const */ TOKEN_HEADER          = 1; // Ex: [White "Kasparov, G."]
var /* const */ TOKEN_MOVE            = 2; // SAN notation or -- (with an optional move number)
var /* const */ TOKEN_NAG             = 3; // $[1-9][0-9]* or a key from table SPECIAL_NAGS_LOOKUP (!!, +-, etc..)
var /* const */ TOKEN_COMMENT         = 4; // {some text}
var /* const */ TOKEN_BEGIN_VARIATION = 5; // (
var /* const */ TOKEN_END_VARIATION   = 6; // )
var /* const */ TOKEN_END_OF_GAME     = 7; // 1-0, 0-1, 1/2-1/2 or *


/**
 * Stream of tokens.
 */
function TokenStream(pgnString) {
	this.text           = pgnString; // what is being parsed
	this._pos           = 0;         // current position in the string
	this.emptyLineFound = false;     // whether an empty line has been encountered by skipBlank()
	this.token          = 0;         // current token
	this.tokenValue     = null;      // current token value (if any)
	this.tokenPos       = 0;         // position of the current token in the string
}


/**
 * Advance until the first non-blank character.
 */
TokenStream.prototype._skipBlanks = function() {
	var newLineCount = 0;
	while(this._pos < this.text.length) {
		var s = this.text.substr(this._pos);
		if(/^([ \f\t\v])+/.test(s)) { // match spaces
			this._pos += RegExp.$1.length;
		}
		else if(/^(\r?\n|\r)/.test(s)) { // match line-breaks
			this._pos += RegExp.$1.length;
			++newLineCount;
		}
		else {
			break;
		}
	}

	// An empty line was encountered if and only if at least to line breaks were found.
	this.emptyLineFound = newLineCount >= 2;
};


/**
 * Try to consume 1 token.
 *
 * @return {boolean} `true` if a token could have been read, `false` if the end of the text has been reached.
 * @throws {InvalidPGN} If the text cannot be interpreted as a valid token.
 */
TokenStream.prototype.consumeToken = function() {

	// Consume blank (i.e. meaning-less) characters
	this._skipBlanks();
	if(this._pos >= this.text.length) {
		return false; // -> `false` means that the end of the string have been reached
	}

	// Remaining part of the string
	var s = this.text.substr(this._pos);
	this.tokenPos = this._pos;

	// Match a game header (ex: [White "Kasparov, G."])
	if(/^(\[\s*(\w+)\s+\"((?:[^\\"\[\]]|\\[\\"\[\]])*)\"\s*\])/.test(s)) {
		this._pos      += RegExp.$1.length;
		this.token      = TOKEN_HEADER;
		this.tokenValue = {key: RegExp.$2, value: parseHeaderValue(RegExp.$3)};
	}

	// Match a move or a null-move
	else if(/^((?:[1-9][0-9]*\s*\.(?:\.\.)?\s*)?((?:O-O-O|O-O|[KQRBN][a-h]?[1-8]?x?[a-h][1-8]|(?:[a-h]x?)?[a-h][1-8](?:=?[KQRBNP])?)[\+#]?|--))/.test(s)) {
		this._pos      += RegExp.$1.length;
		this.token      = TOKEN_MOVE;
		this.tokenValue = RegExp.$2;
	}

	// Match a NAG
	else if(/^(([!\?][!\?]?|\+\/?[\-=]|[\-=]\/?\+|=|inf|~)|\$([1-9][0-9]*))/.test(s)) {
		this._pos      += RegExp.$1.length;
		this.token      = TOKEN_NAG;
		this.tokenValue = RegExp.$3.length === 0 ? SPECIAL_NAGS_LOOKUP[RegExp.$2] : parseInt(RegExp.$3, 10);
	}

	// Match a comment
	else if(/^(\{((?:[^\{\}\\]|\\[\{\}\\])*)\})/.test(s)) {
		this._pos      += RegExp.$1.length;
		this.token      = TOKEN_COMMENT;
		this.tokenValue = parseCommentValue(RegExp.$2);
	}

	// Match the beginning of a variation
	else if(/^(\()/.test(s)) {
		this._pos      += RegExp.$1.length;
		this.token      = TOKEN_BEGIN_VARIATION;
		this.tokenValue = null;
	}

	// Match the end of a variation
	else if(/^(\))/.test(s)) {
		this._pos      += RegExp.$1.length;
		this.token      = TOKEN_END_VARIATION;
		this.tokenValue = null;
	}

	// Match a end-of-game marker
	else if(/^(1\-0|0\-1|1\/2\-1\/2|\*)/.test(s)) {
		this._pos      += RegExp.$1.length;
		this.token      = TOKEN_END_OF_GAME;
		this.tokenValue = RegExp.$1;
	}

	// Otherwise, the string is badly formatted with respect to the PGN syntax
	else {
		throw new exception.InvalidPGN(this.text, this._pos, i18n.INVALID_PGN_TOKEN);
	}

	return true;
};


function parseNullableHeader(value) {
	return value === '?' ? undefined : value;
}


function parseDateHeader(value) {
	if(/^([0-9]{4})\.([0-9]{2})\.([0-9]{2})$/.test(value)) {
		var year = RegExp.$1;
		var month = RegExp.$2;
		var day = RegExp.$3;
		year = parseInt(year, 10);
		month = parseInt(month, 10);
		day = parseInt(day, 10);
		if(month >= 1 && month <= 12 && day >= 1 && day <= 31) {
			return new Date(year, month - 1, day);
		}
	}
	else if(/^([0-9]{4})\.([0-9]{2})\.\?\?$/.test(value)) {
		var year = RegExp.$1;
		var month = parseInt(RegExp.$2, 10);
		if(month >= 1 && month <= 12) {
			return { year: parseInt(year, 10), month: month };
		}
	}
	else if(/^([0-9]{4})(?:\.\?\?\.\?\?)?$/.test(value)) {
		return { year: parseInt(RegExp.$1, 10) };
	}
	return undefined;
}


/**
 * Process a TOKEN_HEADER.
 */
function processHeader(stream, game, key, value) {
	value = value.trim();
	switch(key) {
		case 'White': game.playerName('w', parseNullableHeader(value)); break;
		case 'Black': game.playerName('b', parseNullableHeader(value)); break;
		case 'WhiteElo': game.playerElo('w', value); break;
		case 'BlackElo': game.playerElo('b', value); break;
		case 'WhiteTitle': game.playerTitle('w', value); break;
		case 'BlackTitle': game.playerTitle('b', value); break;
		case 'Event': game.event(parseNullableHeader(value)); break;
		case 'Round': game.round(parseNullableHeader(value)); break;
		case 'Date': game.date(parseDateHeader(value)); break;
		case 'Site': game.site(parseNullableHeader(value)); break;
		case 'Annotator': game.annotator(value); break;

		// The header 'FEN' has a special meaning, in that it is used to define a custom
		// initial position, that may be different from the usual one.
		case 'FEN':
			try {
				var position = new Position();
				var moveCounters = position.fen(value);
				game.initialPosition(position, moveCounters.fullMoveNumber);
			}
			catch(error) {
				if(error instanceof exception.InvalidFEN) {
					throw new exception.InvalidPGN(stream.text, stream.tokenPos, i18n.INVALID_FEN_IN_PGN_TEXT, error.message);
				}
				else {
					throw error;
				}
			}
			break;
	}
}


/**
 * Try to parse 1 game from the given stream.
 *
 * @param {TokenStream}
 * @returns {Game?} `null` if the end of the stream has been reached.
 * @throws {InvalidPGN}
 */
function doParseGame(stream) {

	// State variable for syntaxic analysis.
	var game            = null;  // the result
	var node            = null;  // current node (or variation) to which the next move should be appended
	var nodeIsVariation = false; // whether the current node is a variation or not
	var nodeStack       = [];    // when starting a variation, its parent node (btw., always a "true" node, not a variation) is stacked here

	// Token loop
	while(stream.consumeToken()) {

		// Create a new game if necessary
		if(game === null) {
			game = new Game();
		}

		// Matching anything else different from a header means that the move section
		// is going to be parse => set-up the root node.
		if(stream.token !== TOKEN_HEADER && node === null) {
			node = game.mainVariation();
			nodeIsVariation = true;
		}

		// Token type switch
		switch(stream.token) {

			// Header
			case TOKEN_HEADER:
				if(node !== null) {
					throw new exception.InvalidPGN(stream.text, stream.tokenPos, i18n.UNEXPECTED_PGN_HEADER);
				}
				processHeader(stream, game, stream.tokenValue.key, stream.tokenValue.value);
				break;

			// Move or null-move
			case TOKEN_MOVE:
				try {
					node = node.play(stream.tokenValue);
					nodeIsVariation = false;
				}
				catch(error) {
					if(error instanceof exception.InvalidNotation) {
						throw new exception.InvalidPGN(stream.text, stream.tokenPos, i18n.INVALID_MOVE_IN_PGN_TEXT, error.notation, error.message);
					}
					else {
						throw error;
					}
				}
				break;

			// NAG
			case TOKEN_NAG:
				node.addNag(stream.tokenValue);
				break;

			// Comment
			case TOKEN_COMMENT:
				for(var key in stream.tokenValue.tags) {
					if(stream.tokenValue.tags[key] !== undefined) {
						node.tag(key, stream.tokenValue.tags[key]);
					}
				}
				node.comment(stream.tokenValue.comment, stream.emptyLineFound);
				break;

			// Begin of variation
			case TOKEN_BEGIN_VARIATION:
				if(nodeIsVariation) {
					throw new exception.InvalidPGN(stream.text, stream.tokenPos, i18n.UNEXPECTED_BEGIN_OF_VARIATION);
				}
				nodeStack.push(node);
				node = node.addVariation(stream.emptyLineFound);
				nodeIsVariation = true;
				break;

			// End of variation
			case TOKEN_END_VARIATION:
				if(nodeStack.length === 0) {
					throw new exception.InvalidPGN(stream.text, stream.tokenPos, i18n.UNEXPECTED_END_OF_VARIATION);
				}
				node = nodeStack.pop();
				nodeIsVariation = false;
				break;

			// End-of-game
			case TOKEN_END_OF_GAME:
				if(nodeStack.length > 0) {
					throw new exception.InvalidPGN(stream.text, stream.tokenPos, i18n.UNEXPECTED_END_OF_GAME);
				}
				game.result(stream.tokenValue);
				return game;

		} // switch(token)

	} // while(consume(token()))

	if(game !== null) {
		throw new exception.InvalidPGN(stream.text, stream.text.length, i18n.UNEXPECTED_END_OF_TEXT);
	}
	return null;
}


/**
 * Skip 1 game in the given stream.
 *
 * @param {TokenStream}
 * @returns {boolean} `true` if a game has been skipped, false if the end of the stream has been reached.
 * @throws {InvalidPGN}
 */
function doSkipGame(stream) {
	while(stream.consumeToken()) {
		switch(stream.token) {
			case TOKEN_END_OF_GAME: return true;
		}
	}
	return false;
}


/**
 * PGN parsing function.
 *
 * @param {string} pgnString String to parse.
 * @param {number} [gameIndex] If provided, parse only the game corresponding to this index.
 * @returns {Game[]|Game} Return an array if no game index is provided, or a single `Game` object otherwise.
 * @throws {InvalidPGN}
 */
exports.pgnRead = function(pgnString, gameIndex) {
	var stream = new TokenStream(pgnString);

	// Parse all games...
	if(arguments.length === 1) {
		var result = [];
		while(true) {
			var currentGame = doParseGame(stream);
			if(currentGame === null) {
				return result;
			}
			result.push(currentGame);
		}
	}

	// Parse one game...
	else {
		var gameCounter = 0;
		while(gameCounter < gameIndex) {
			if(doSkipGame(stream)) {
				++gameCounter;
			}
			else {
				throw new exception.InvalidPGN(pgnString, -1, i18n.INVALID_GAME_INDEX, gameIndex, gameCounter);
			}
		}

		var result = doParseGame(stream);
		if(result === null) {
			throw new exception.InvalidPGN(pgnString, -1, i18n.INVALID_GAME_INDEX, gameIndex, gameCounter);
		}
		return result;
	}
};

},{"./exception":3,"./game":4,"./i18n":5,"./position":8}],8:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('./basetypes');
var moveDescriptor = require('./movedescriptor');
var exception = require('./exception');

var impl = require('./private_position/impl');
var fen = require('./private_position/fen');
var attacks = require('./private_position/attacks');
var legality = require('./private_position/legality');
var moveGeneration = require('./private_position/movegeneration');
var notation = require('./private_position/notation');



// -----------------------------------------------------------------------------
// Constructor & reset/clear
// -----------------------------------------------------------------------------

/**
 * Represent a chess position, i.e. the state of a 64-square chessboard with a few additional
 * information (who is about to play, castling rights, en-passant rights).
 *
 * @param {string|Position} [fen = 'start'] Either `'start'`, `'empty'`, an existing position, or a FEN string representing chess position.
 * @throws InvalidFEN If the input parameter is neither a correctly formatted FEN string nor `'start'` or `'empty'`.
 */
var Position = exports.Position = function() {
	if(arguments.length === 0 || arguments[0] === 'start') {
		this._impl = impl.makeInitial();
	}
	else if(arguments[0] === 'empty') {
		this._impl = impl.makeEmpty();
	}
	else if(arguments[0] instanceof Position) {
		this._impl = impl.makeCopy(arguments[0]._impl);
	}
	else if(typeof arguments[0] === 'string') {
		this._impl = fen.parseFEN(arguments[0], false).position;
	}
	else {
		throw new exception.IllegalArgument('Position()');
	}
};


/**
 * Set the position to the empty state.
 */
Position.prototype.clear = function() {
	this._impl = impl.makeEmpty();
};


/**
 * Set the position to the starting state.
 */
Position.prototype.reset = function() {
	this._impl = impl.makeInitial();
};



// -----------------------------------------------------------------------------
// FEN & ASCII conversion
// -----------------------------------------------------------------------------


/**
 * Return a human-readable string representing the position. This string is multi-line,
 * and is intended to be displayed in a fixed-width font (similarly to an ASCII-art picture).
 *
 * @returns {string} Human-readable representation of the position.
 */
Position.prototype.ascii = function() {
	return fen.ascii(this._impl);
};


/**
 * `fen()` or `fen({fiftyMoveClock:number, fullMoveNumber:number})`: return the FEN representation of the position (getter behavior).
 *
 * `fen(string [, boolean])`: parse the given FEN string and set the position accordingly (setter behavior).
 */
Position.prototype.fen = function() {
	if(arguments.length === 0) {
		return fen.getFEN(this._impl, 0, 1);
	}
	else if(arguments.length === 1 && typeof arguments[0] === 'object') {
		var fiftyMoveClock = (typeof arguments[0].fiftyMoveClock === 'number') ? arguments[0].fiftyMoveClock : 0;
		var fullMoveNumber = (typeof arguments[0].fullMoveNumber === 'number') ? arguments[0].fullMoveNumber : 1;
		return fen.getFEN(this._impl, fiftyMoveClock, fullMoveNumber);
	}
	else if(arguments.length === 1 && typeof arguments[0] === 'string') {
		var result = fen.parseFEN(arguments[0], false);
		this._impl = result.position;
		return { fiftyMoveClock: result.fiftyMoveClock, fullMoveNumber: result.fullMoveNumber };
	}
	else if(arguments.length >= 2 && typeof arguments[0] === 'string' && typeof arguments[1] === 'boolean') {
		var result = fen.parseFEN(arguments[0], arguments[1]);
		this._impl = result.position;
		return { fiftyMoveClock: result.fiftyMoveClock, fullMoveNumber: result.fullMoveNumber };
	}
	else {
		throw new exception.IllegalArgument('Position#fen()');
	}
};



// -----------------------------------------------------------------------------
// Accessors
// -----------------------------------------------------------------------------


/**
 * Get/set the content of a square.
 *
 * @param {string} square `'e4'` for instance
 * @param {string} [value]
 */
Position.prototype.square = function(square, value) {
	square = bt.squareFromString(square);
	if(square < 0) {
		throw new exception.IllegalArgument('Position#square()');
	}

	if(arguments.length === 1) {
		var cp = this._impl.board[square];
		return cp < 0 ? '-' : bt.coloredPieceToString(cp);
	}
	else if(value === '-') {
		this._impl.board[square] = bt.EMPTY;
		this._impl.legal = null;
	}
	else {
		var cp = bt.coloredPieceFromString(value);
		if(cp < 0) {
			throw new exception.IllegalArgument('Position#square()');
		}
		this._impl.board[square] = cp;
		this._impl.legal = null;
	}
};


/**
 * Get/set the turn flag.
 *
 * @param {string} [value]
 */
Position.prototype.turn = function(value) {
	if(arguments.length === 0) {
		return bt.colorToString(this._impl.turn);
	}
	else {
		var turn = bt.colorFromString(value);
		if(turn < 0) {
			throw new exception.IllegalArgument('Position#turn()');
		}
		this._impl.turn = turn;
		this._impl.legal = null;
	}
};


/**
 * Get/set the castling rights. TODO: make it chess-960 compatible.
 *
 * @param {string} color
 * @param {string} side
 * @param {boolean} [value]
 */
Position.prototype.castling = function(castle, value) {
	if(!/^[wb][qk]$/.test(castle)) {
		throw new exception.IllegalArgument('Position#castling()');
	}
	var color = bt.colorFromString(castle[0]);
	var file = castle[1]==='k' ? 7 : 0;

	if(arguments.length === 1) {
		return (this._impl.castling[color] /* jshint bitwise:false */ & (1 << file) /* jshint bitwise:true */) !== 0;
	}
	else if(value) {
		this._impl.castling[color] /* jshint bitwise:false */ |= 1 << file; /* jshint bitwise:true */
		this._impl.legal = null;
	}
	else {
		this._impl.castling[color] /* jshint bitwise:false */ &= ~(1 << file); /* jshint bitwise:true */
		this._impl.legal = null;
	}
};


/**
 * Get/set the en-passant flag.
 *
 * @param {string} [value]
 */
Position.prototype.enPassant = function(value) {
	if(arguments.length === 0) {
		return this._impl.enPassant < 0 ? '-' : bt.fileToString(this._impl.enPassant);
	}
	else if(value === '-') {
		this._impl.enPassant = -1;
		this._impl.legal = null;
	}
	else {
		var enPassant = bt.fileFromString(value);
		if(enPassant < 0) {
			throw new exception.IllegalArgument('Position#enPassant()');
		}
		this._impl.enPassant = enPassant;
		this._impl.legal = null;
	}
};



// -----------------------------------------------------------------------------
// Attacks
// -----------------------------------------------------------------------------


/**
 * Check if any piece of the given color attacks a given square.
 *
 * @param {string} square
 * @param {string} byWho Either `'w'` or `'b'`
 * @returns {boolean}
 */
Position.prototype.isAttacked = function(square, byWho) {
	square = bt.squareFromString(square);
	byWho = bt.colorFromString(byWho);
	if(square < 0 || byWho < 0) {
		throw new exception.IllegalArgument('Position#isAttacked()');
	}
	return attacks.isAttacked(this._impl, square, byWho);
};


/**
 * Return the squares from which a piece of the given color attacks a given square.
 *
 * @param {string} square
 * @param {string} byWho Either `'w'` or `'b'`
 * @returns {boolean}
 */
Position.prototype.getAttacks = function(square, byWho) {
	square = bt.squareFromString(square);
	byWho = bt.colorFromString(byWho);
	if(square < 0 || byWho < 0) {
		throw new exception.IllegalArgument('Position#getAttacks()');
	}
	return attacks.getAttacks(this._impl, square, byWho).map(bt.squareToString);
};



// -----------------------------------------------------------------------------
// Legality
// -----------------------------------------------------------------------------


/**
 * Check whether the current position is legal or not.
 *
 * A position is considered to be legal if all the following conditions are met:
 *
 *  1. There is exactly one white king and one black king on the board.
 *  2. The player that is not about to play is not check.
 *  3. There are no pawn on rows 1 and 8.
 *  4. For each colored castle flag set, there is a rook and a king on the
 *     corresponding initial squares.
 *  5. The pawn situation is consistent with the en-passant flag if it is set.
 *     For instance, if it is set to the 'e' column and black is about to play,
 *     the squares e2 and e3 must be empty, and there must be a white pawn on e4.
 *
 * @returns {boolean}
 */
Position.prototype.isLegal = function() {
	return legality.isLegal(this._impl);
};


/**
 * Return the square on which is located the king of the given color.
 *
 * @param {string} color
 * @returns {string} Square where is located the searched king. `'-'` is returned
 *          if there is no king of the given color or if the are 2 such kings or more.
 */
Position.prototype.kingSquare = function(color) {
	color = bt.colorFromString(color);
	if(color < 0) {
		throw new exception.IllegalArgument('Position#kingSquare()');
	}
	legality.refreshLegalFlagAndKingSquares(this._impl);
	var square = this._impl.king[color];
	return square < 0 ? '-' : bt.squareToString(square);
};



// -----------------------------------------------------------------------------
// Move generation
// -----------------------------------------------------------------------------


/**
 * Return true if the player that is about to play is in check. If the position is not legal, the returned value is always false.
 *
 * @returns {boolean}
 */
Position.prototype.isCheck = function() {
	return moveGeneration.isCheck(this._impl);
};


/**
 * Return true if the player that is about to play is checkmated. If the position is not legal, the returned value is always false.
 *
 * @returns {boolean}
 */
Position.prototype.isCheckmate = function() {
	return moveGeneration.isCheckmate(this._impl);
};


/**
 * Return true if the player that is about to play is stalemated. If the position is not legal, the returned value is always false.
 *
 * @returns {boolean}
 */
Position.prototype.isStalemate = function() {
	return moveGeneration.isStalemate(this._impl);
};


/**
 * Detect if there exist any legal move in the current position. If the position is not legal, the returned value is always false.
 *
 * @returns {boolean}
 */
Position.prototype.hasMove = function() {
	return moveGeneration.hasMove(this._impl);
};


/**
 * Return the list of all legal moves in the current position. An empty list is returned if the position itself is not legal.
 *
 * @returns {MoveDescriptor[]}
 */
Position.prototype.moves = function() {
	return moveGeneration.moves(this._impl);
};


/**
 * Whether a move is legal or not.
 *
 * @returns {false|MoveDescriptor|function}
 */
Position.prototype.isMoveLegal = function(from, to) {
	from = bt.squareFromString(from);
	to = bt.squareFromString(to);
	if(from < 0 || to < 0) {
		throw new exception.IllegalArgument('Position#isMoveLegal()');
	}
	var result = moveGeneration.isMoveLegal(this._impl, from, to);

	// A promoted piece needs to be chosen to build a valid move descriptor.
	if(typeof result === 'function') {
		var builder = function(promotion) {
			promotion = bt.pieceFromString(promotion);
			if(promotion >= 0) {
				var builtMoveDescriptor = result(promotion);
				if(builtMoveDescriptor) {
					return builtMoveDescriptor;
				}
			}
			throw new exception.IllegalArgument('Position#isMoveLegal()');
		};
		builder.needPromotion = true;
		return builder;
	}

	// The result is either false or is a valid move descriptor.
	else {
		return result;
	}
};


/**
 * Play the given move if it is legal.
 *
 * @param {string|MoveDescriptor} move
 * @returns {boolean} `true` if the move has been played and if it is legal, `false` otherwise.
 */
Position.prototype.play = function(move) {
	if(typeof move === 'string') {
		try {
			moveGeneration.play(this._impl, notation.parseNotation(this._impl, move, false));
			return true;
		}
		catch(err) {
			if(err instanceof exception.InvalidNotation) {
				return false;
			}
			else {
				throw err;
			}
		}
	}
	else if(moveDescriptor.isInstanceOf(move)) {
		moveGeneration.play(this._impl, move);
		return true;
	}
	else {
		throw new exception.IllegalArgument('Position#play()');
	}
};


/**
 * Determine if a null-move (i.e. switching the player about to play) can be play in the current position.
 * A null-move is possible if the position is legal and if the current player about to play is not in check.
 *
 * @returns {boolean}
 */
Position.prototype.isNullMoveLegal = function() {
	return moveGeneration.isNullMoveLegal(this._impl);
};


/**
 * Play a null-move on the current position if it is legal.
 *
 * @returns {boolean} `true` if the move has actually been played, `false` otherwise.
 */
Position.prototype.playNullMove = function() {
	return moveGeneration.playNullMove(this._impl);
};



// -----------------------------------------------------------------------------
// Algebraic notation
// -----------------------------------------------------------------------------


/**
 * `notation(moveDescriptor)`: return the standard algebraic notation corresponding to the given move descriptor.
 *
 * `notation(string [, boolean])`: parse the given string as standard algebraic notation and return the corresponding move descriptor.
 *
 * @throws {InvalidNotation} If the move parsing fails or if the parsed move would correspond to an illegal move.
 */
Position.prototype.notation = function() {
	if(arguments.length === 1 && moveDescriptor.isInstanceOf(arguments[0])) {
		return notation.getNotation(this._impl, arguments[0]);
	}
	else if(arguments.length === 1 && typeof arguments[0] === 'string') {
		return notation.parseNotation(this._impl, arguments[0], false);
	}
	else if(arguments.length >= 2 && typeof arguments[0] === 'string' && typeof arguments[1] === 'boolean') {
		return notation.parseNotation(this._impl, arguments[0], arguments[1]);
	}
	else {
		throw new exception.IllegalArgument('Position#notation()');
	}
};

},{"./basetypes":2,"./exception":3,"./movedescriptor":6,"./private_position/attacks":9,"./private_position/fen":10,"./private_position/impl":11,"./private_position/legality":12,"./private_position/movegeneration":13,"./private_position/notation":14}],9:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('../basetypes');


// Attack directions per colored piece.
var ATTACK_DIRECTIONS = exports.ATTACK_DIRECTIONS = [
	[-17, -16, -15, -1, 1, 15, 16, 17], // king/queen
	[-17, -16, -15, -1, 1, 15, 16, 17], // king/queen
	[-17, -16, -15, -1, 1, 15, 16, 17], // king/queen
	[-17, -16, -15, -1, 1, 15, 16, 17], // king/queen
	[-16, -1, 1, 16], // rook
	[-16, -1, 1, 16], // rook
	[-17, -15, 15, 17], // bishop
	[-17, -15, 15, 17], // bishop
	[-33, -31, -18, -14, 14, 18, 31, 33], // knight
	[-33, -31, -18, -14, 14, 18, 31, 33], // knight
	[15, 17], // white pawn
	[-17, -15] // black pawn
];



// -----------------------------------------------------------------------------
// isAttacked
// -----------------------------------------------------------------------------

/**
 * Check if any piece of the given color attacks a given square.
 */
exports.isAttacked = function(position, square, attackerColor) {
	return isAttackedByNonSliding(position, square, bt.KING*2 + attackerColor) ||
		isAttackedByNonSliding(position, square, bt.KNIGHT*2 + attackerColor) ||
		isAttackedByNonSliding(position, square, bt.PAWN*2 + attackerColor) ||
		isAttackedBySliding(position, square, bt.ROOK*2 + attackerColor, bt.QUEEN*2 + attackerColor) ||
		isAttackedBySliding(position, square, bt.BISHOP*2 + attackerColor, bt.QUEEN*2 + attackerColor);
};


function isAttackedByNonSliding(position, square, nonSlidingAttacker) {
	var directions = ATTACK_DIRECTIONS[nonSlidingAttacker];
	for(var i=0; i<directions.length; ++i) {
		var sq = square - directions[i];
		if((sq /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0 && position.board[sq]===nonSlidingAttacker) {
			return true;
		}
	}
	return false;
}


function isAttackedBySliding(position, square, slidingAttacker, queenAttacker) {
	var directions = ATTACK_DIRECTIONS[slidingAttacker];
	for(var i=0; i<directions.length; ++i) {
		var sq = square;
		while(true) {
			sq -= directions[i];
			if((sq /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0) {
				var cp = position.board[sq];
				if(cp === bt.EMPTY) { continue; }
				else if(cp === slidingAttacker || cp===queenAttacker) { return true; }
			}
			break;
		}
	}
	return false;
}



// -----------------------------------------------------------------------------
// getAttacks
// -----------------------------------------------------------------------------

/**
 * Return the squares from which a piece of the given color attacks a given square.
 */
exports.getAttacks = function(position, square, attackerColor) {
	var result = [];
	findNonSlidingAttacks(position, square, result, bt.KING*2 + attackerColor);
	findNonSlidingAttacks(position, square, result, bt.KNIGHT*2 + attackerColor);
	findNonSlidingAttacks(position, square, result, bt.PAWN*2 + attackerColor);
	findSlidingAttacks(position, square, result, bt.ROOK*2 + attackerColor, bt.QUEEN*2 + attackerColor);
	findSlidingAttacks(position, square, result, bt.BISHOP*2 + attackerColor, bt.QUEEN*2 + attackerColor);
	return result;
};


function findNonSlidingAttacks(position, square, result, nonSlidingAttacker) {
	var directions = ATTACK_DIRECTIONS[nonSlidingAttacker];
	for(var i=0; i<directions.length; ++i) {
		var sq = square - directions[i];
		if((sq /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0 && position.board[sq]===nonSlidingAttacker) {
			result.push(sq);
		}
	}
}


function findSlidingAttacks(position, square, result, slidingAttacker, queenAttacker) {
	var directions = ATTACK_DIRECTIONS[slidingAttacker];
	for(var i=0; i<directions.length; ++i) {
		var sq = square;
		while(true) {
			sq -= directions[i];
			if((sq /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0) {
				var cp = position.board[sq];
				if(cp === bt.EMPTY) { continue; }
				else if(cp === slidingAttacker || cp===queenAttacker) { result.push(sq); }
			}
			break;
		}
	}
}

},{"../basetypes":2}],10:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('../basetypes');
var exception = require('../exception');
var i18n = require('../i18n');

var impl = require('./impl');

var FEN_PIECE_SYMBOL = 'KkQqRrBbNnPp';


/**
 * Return a human-readable string representing the position. This string is multi-line,
 * and is intended to be displayed in a fixed-width font (similarly to an ASCII-art picture).
 */
exports.ascii = function(position) {

	// Board scanning
	var result = '+---+---+---+---+---+---+---+---+\n';
	for(var r=7; r>=0; --r) {
		for(var f=0; f<8; ++f) {
			var cp = position.board[r*16 + f];
			result += '| ' + (cp < 0 ? ' ' : FEN_PIECE_SYMBOL[cp]) + ' ';
		}
		result += '|\n';
		result += '+---+---+---+---+---+---+---+---+\n';
	}

	// Flags
	result += bt.colorToString(position.turn) + ' ' + castlingToString(position) + ' ' + enPassantToString(position);

	return result;
};


exports.getFEN = function(position, fiftyMoveClock, fullMoveNumber) {
	var result = '';

	// Board scanning
	for(var r=7; r>=0; --r) {
		var emptyCount = 0;
		for(var f=0; f<8; ++f) {
			var cp = position.board[r*16 + f];
			if(cp < 0) {
				++emptyCount;
			}
			else {
				if(emptyCount > 0) {
					result += emptyCount;
					emptyCount = 0;
				}
				result += FEN_PIECE_SYMBOL[cp];
			}
		}
		if(emptyCount > 0) {
			result += emptyCount;
		}
		if(r > 0) {
			result += '/';
		}
	}

	// Flags + additional move counters
	result += ' ' + bt.colorToString(position.turn) + ' ' + castlingToString(position) + ' ' + enPassantToString(position);
	result += ' ' + fiftyMoveClock + ' ' + fullMoveNumber;

	return result;
};


function castlingToString(position) {
	var result = '';
	if(position.castling[bt.WHITE] /* jshint bitwise:false */ & 1<<7 /* jshint bitwise:true */) { result += 'K'; }
	if(position.castling[bt.WHITE] /* jshint bitwise:false */ & 1<<0 /* jshint bitwise:true */) { result += 'Q'; }
	if(position.castling[bt.BLACK] /* jshint bitwise:false */ & 1<<7 /* jshint bitwise:true */) { result += 'k'; }
	if(position.castling[bt.BLACK] /* jshint bitwise:false */ & 1<<0 /* jshint bitwise:true */) { result += 'q'; }
	return result === '' ? '-' : result;
}


function enPassantToString(position) {
	return position.enPassant < 0 ? '-' : bt.fileToString(position.enPassant) + (position.turn===bt.WHITE ? '6' : '3');
}


exports.parseFEN = function(fen, strict) {

	// Trim the input string and split it into 6 fields.
	fen = fen.replace(/^\s+|\s+$/g, '');
	var fields = fen.split(/\s+/);
	if(fields.length !== 6) {
		throw new exception.InvalidFEN(fen, i18n.WRONG_NUMBER_OF_FEN_FIELDS);
	}

	// The first field (that represents the board) is split in 8 sub-fields.
	var rankFields = fields[0].split('/');
	if(rankFields.length !== 8) {
		throw new exception.InvalidFEN(fen, i18n.WRONG_NUMBER_OF_SUBFIELDS_IN_BOARD_FIELD);
	}

	// Initialize the position
	var position = impl.makeEmpty();
	position.legal = null;

	// Board parsing
	for(var r=7; r>=0; --r) {
		var rankField = rankFields[7-r];
		var i = 0;
		var f = 0;
		while(i<rankField.length && f<8) {
			var s = rankField[i];
			var cp = FEN_PIECE_SYMBOL.indexOf(s);

			// The current character is in the range [1-8] -> skip the corresponding number of squares.
			if(/^[1-8]$/.test(s)) {
				f += parseInt(s, 10);
			}

			// The current character corresponds to a colored piece symbol -> set the current square accordingly.
			else if(cp >= 0) {
				position.board[r*16 + f] = cp;
				++f;
			}

			// Otherwise -> parsing error.
			else {
				throw new exception.InvalidFEN(fen, i18n.UNEXPECTED_CHARACTER_IN_BOARD_FIELD, s);
			}

			// Increment the character counter.
			++i;
		}

		// Ensure that the current sub-field deals with all the squares of the current rank.
		if(i !== rankField.length || f !== 8) {
			throw new exception.InvalidFEN(fen, i18n.UNEXPECTED_END_OF_SUBFIELD_IN_BOARD_FIELD, i18n.ORDINALS[7-r]);
		}
	}

	// Turn parsing
	position.turn = bt.colorFromString(fields[1]);
	if(position.turn < 0) {
		throw new exception.InvalidFEN(fen, i18n.INVALID_TURN_FIELD);
	}

	// Castling rights parsing
	position.castling = castlingFromString(fields[2], strict);
	if(position.castling === null) {
		throw new exception.InvalidFEN(fen, i18n.INVALID_CASTLING_FIELD);
	}

	// En-passant rights parsing
	var enPassantField = fields[3];
	if(enPassantField !== '-') {
		if(!/^[a-h][36]$/.test(enPassantField)) {
			throw new exception.InvalidFEN(fen, i18n.INVALID_EN_PASSANT_FIELD);
		}
		if(strict && ((enPassantField[1]==='3' && position.turn===bt.WHITE) || (enPassantField[1]==='6' && position.turn===bt.BLACK))) {
			throw new exception.InvalidFEN(fen, i18n.WRONG_RANK_IN_EN_PASSANT_FIELD);
		}
		position.enPassant = bt.fileFromString(enPassantField[0]);
	}

	// Move counting flags parsing
	var moveCountingRegExp = strict ? /^(?:0|[1-9][0-9]*)$/ : /^[0-9]+$/;
	if(!moveCountingRegExp.test(fields[4])) {
		throw new exception.InvalidFEN(fen, i18n.INVALID_MOVE_COUNTING_FIELD, i18n.ORDINALS[4]);
	}
	if(!moveCountingRegExp.test(fields[5])) {
		throw new exception.InvalidFEN(fen, i18n.INVALID_MOVE_COUNTING_FIELD, i18n.ORDINALS[5]);
	}
	return { position: position, fiftyMoveClock: parseInt(fields[4], 10), fullMoveNumber: parseInt(fields[5], 10) };
};


function castlingFromString(castling, strict) {
	var res = [0, 0];
	if(castling === '-') {
		return res;
	}
	if(!(strict ? /^K?Q?k?q?$/ : /^[KQkq]*$/).test(castling)) {
		return null;
	}
	if(castling.indexOf('K') >= 0) { res[bt.WHITE] /* jshint bitwise:false */ |= 1<<7; /* jshint bitwise:true */ }
	if(castling.indexOf('Q') >= 0) { res[bt.WHITE] /* jshint bitwise:false */ |= 1<<0; /* jshint bitwise:true */ }
	if(castling.indexOf('k') >= 0) { res[bt.BLACK] /* jshint bitwise:false */ |= 1<<7; /* jshint bitwise:true */ }
	if(castling.indexOf('q') >= 0) { res[bt.BLACK] /* jshint bitwise:false */ |= 1<<0; /* jshint bitwise:true */ }
	return res;
}

},{"../basetypes":2,"../exception":3,"../i18n":5,"./impl":11}],11:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('../basetypes');
var EMPTY = bt.EMPTY;
var INVALID = bt.INVALID;


exports.makeEmpty = function() {
	return {

		// Board state
		board: [
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY
		],

		// Flags
		turn: bt.WHITE,
		castling: [0, 0],
		enPassant: -1,

		// Computed attributes
		legal: false,
		king: [-1, -1]
	};
};


exports.makeInitial = function() {
	return {

		// Board state
		board: [
			bt.WR, bt.WN, bt.WB, bt.WQ, bt.WK, bt.WB, bt.WN, bt.WR, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			bt.WP, bt.WP, bt.WP, bt.WP, bt.WP, bt.WP, bt.WP, bt.WP, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, EMPTY, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			bt.BP, bt.BP, bt.BP, bt.BP, bt.BP, bt.BP, bt.BP, bt.BP, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID, INVALID,
			bt.BR, bt.BN, bt.BB, bt.BQ, bt.BK, bt.BB, bt.BN, bt.BR
		],

		// Flags
		turn: bt.WHITE,
		castling: [129 /* (1 << A-file) | (1 << H-file) */, 129],
		enPassant: -1,

		// Computed attributes
		legal: true,
		king: [4 /* e1 */, 116 /* e8 */]
	};
};


exports.makeCopy = function(position) {
	return {
		board    : position.board.slice(),
		turn     : position.turn,
		castling : position.castling.slice(),
		enPassant: position.enPassant,
		legal    : position.legal,
		king     : position.king.slice()
	};
};

},{"../basetypes":2}],12:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('../basetypes');
var attacks = require('./attacks');


/**
 * Check whether the given position is legal or not.
 *
 * A position is considered to be legal if all the following conditions are met:
 *
 *  1. There is exactly one white king and one black king on the board.
 *  2. The player that is not about to play is not check.
 *  3. There are no pawn on rows 1 and 8.
 *  4. For each colored castle flag set, there is a rook and a king on the
 *     corresponding initial squares.
 *  5. The pawn situation is consistent with the en-passant flag if it is set.
 *     For instance, if it is set to the 'e' column and black is about to play,
 *     the squares e2 and e3 must be empty, and there must be a white pawn on e4.
 */
exports.isLegal = function(position) {
	refreshLegalFlagAndKingSquares(position);
	return position.legal;
};


/**
 * Refresh the legal flag of the given position if it is set to null
 * (which means that the legality state of the position is unknown).
 *
 * Together with the legal flag, the reference to the squares where the white and
 * black kings lie is updated by this function.
 *
 * TODO: make it chess-960 compatible.
 */
var refreshLegalFlagAndKingSquares = exports.refreshLegalFlagAndKingSquares = function(position) {
	if(position.legal !== null) {
		return;
	}
	position.legal = false;

	// Condition (1)
	refreshKingSquare(position, bt.WHITE);
	refreshKingSquare(position, bt.BLACK);
	if(position.king[bt.WHITE] < 0 || position.king[bt.BLACK] < 0) {
		return;
	}

	// Condition (2)
	if(attacks.isAttacked(position, position.king[1-position.turn], position.turn)) {
		return;
	}

	// Condition (3)
	for(var c=0; c<8; ++c) {
		var cp1 = position.board[c];
		var cp8 = position.board[112 + c];
		if(cp1 === bt.WP || cp8 === bt.WP || cp1 === bt.BP || cp8 === bt.BP) {
			return;
		}
	}

	// Condition (4)
	for(var color=0; color<2; ++color) {
		var skipOO  = (position.castling[color] /* jshint bitwise:false */ & 0x80 /* jshint bitwise:true */) === 0;
		var skipOOO = (position.castling[color] /* jshint bitwise:false */ & 0x01 /* jshint bitwise:true */) === 0;
		var rookHOK = skipOO              || position.board[7 + 112*color] === bt.ROOK*2 + color;
		var rookAOK = skipOOO             || position.board[0 + 112*color] === bt.ROOK*2 + color;
		var kingOK  = (skipOO && skipOOO) || position.board[4 + 112*color] === bt.KING*2 + color;
		if(!(kingOK && rookAOK && rookHOK)) {
			return;
		}
	}

	// Condition (5)
	if(position.enPassant >= 0) {
		var square2 = (6-position.turn*5)*16 + position.enPassant;
		var square3 = (5-position.turn*3)*16 + position.enPassant;
		var square4 = (4-position.turn  )*16 + position.enPassant;
		if(!(position.board[square2]===bt.EMPTY && position.board[square3]===bt.EMPTY && position.board[square4]===bt.PAWN*2+1-position.turn)) {
			return;
		}
	}

	// At this point, all the conditions (1) to (5) hold, so the position can be flagged as legal.
	position.legal = true;
};


/**
 * Detect the kings of the given color that are present on the chess board.
 */
function refreshKingSquare(position, color) {
	var target = bt.KING*2 + color;
	position.king[color] = -1;
	for(var sq=0; sq<120; sq += (sq /* jshint bitwise:false */ & 0x7 /* jshint bitwise:true */)===7 ? 9 : 1) {
		if(position.board[sq] === target) {

			// If the targeted king is detected on the square sq, two situations may occur:
			// 1) No king was detected on the previously visited squares: then the current
			//    square is saved, and loop over the next board squares goes on.
			if(position.king[color] < 0) {
				position.king[color] = sq;
			}

			// 2) Another king was detected on the previously visited squares: then the buffer position.king[color]
			//    is set to the invalid state (-1), and the loop is interrupted.
			else {
				position.king[color] = -1;
				return;
			}
		}
	}
}

},{"../basetypes":2,"./attacks":9}],13:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('../basetypes');
var moveDescriptor = require('../movedescriptor');
var attacks = require('./attacks');
var legality = require('./legality');


// Displacement lookup per square index difference.
var /* const */ DISPLACEMENT_LOOKUP = [
 204,    0,    0,    0,    0,    0,    0,   60,    0,    0,    0,    0,    0,    0,  204,    0,
	 0,  204,    0,    0,    0,    0,    0,   60,    0,    0,    0,    0,    0,  204,    0,    0,
	 0,    0,  204,    0,    0,    0,    0,   60,    0,    0,    0,    0,  204,    0,    0,    0,
	 0,    0,    0,  204,    0,    0,    0,   60,    0,    0,    0,  204,    0,    0,    0,    0,
	 0,    0,    0,    0,  204,    0,    0,   60,    0,    0,  204,    0,    0,    0,    0,    0,
	 0,    0,    0,    0,    0,  204,  768,   60,  768,  204,    0,    0,    0,    0,    0,    0,
	 0,    0,    0,    0,    0,  768, 2255, 2111, 2255,  768,    0,    0,    0,    0,    0,    0,
	60,   60,   60,   60,   60,   60,   63,    0,   63,   60,   60,   60,   60,   60,   60,    0,
	 0,    0,    0,    0,    0,  768, 1231, 1087, 1231,  768,    0,    0,    0,    0,    0,    0,
	 0,    0,    0,    0,    0,  204,  768,   60,  768,  204,    0,    0,    0,    0,    0,    0,
	 0,    0,    0,    0,  204,    0,    0,   60,    0,    0,  204,    0,    0,    0,    0,    0,
	 0,    0,    0,  204,    0,    0,    0,   60,    0,    0,    0,  204,    0,    0,    0,    0,
	 0,    0,  204,    0,    0,    0,    0,   60,    0,    0,    0,    0,  204,    0,    0,    0,
	 0,  204,    0,    0,    0,    0,    0,   60,    0,    0,    0,    0,    0,  204,    0,    0,
 204,    0,    0,    0,    0,    0,    0,   60,    0,    0,    0,    0,    0,    0,  204,    0
];

// Sliding direction
var /* const */ SLIDING_DIRECTION = [
	-17,   0,   0,   0,   0,   0,   0, -16,   0,   0,   0,   0,   0,   0, -15,   0,
		0, -17,   0,   0,   0,   0,   0, -16,   0,   0,   0,   0,   0, -15,   0,   0,
		0,   0, -17,   0,   0,   0,   0, -16,   0,   0,   0,   0, -15,   0,   0,   0,
		0,   0,   0, -17,   0,   0,   0, -16,   0,   0,   0, -15,   0,   0,   0,   0,
		0,   0,   0,   0, -17,   0,   0, -16,   0,   0, -15,   0,   0,   0,   0,   0,
		0,   0,   0,   0,   0, -17,   0, -16,   0, -15,   0,   0,   0,   0,   0,   0,
		0,   0,   0,   0,   0,   0, -17, -16, -15,   0,   0,   0,   0,   0,   0,   0,
	 -1,  -1,  -1,  -1,  -1,  -1,  -1,   0,   1,   1,   1,   1,   1,   1,   1,   0,
		0,   0,   0,   0,   0,   0,  15,  16,  17,   0,   0,   0,   0,   0,   0,   0,
		0,   0,   0,   0,   0,  15,   0,  16,   0,  17,   0,   0,   0,   0,   0,   0,
		0,   0,   0,   0,  15,   0,   0,  16,   0,   0,  17,   0,   0,   0,   0,   0,
		0,   0,   0,  15,   0,   0,   0,  16,   0,   0,   0,  17,   0,   0,   0,   0,
		0,   0,  15,   0,   0,   0,   0,  16,   0,   0,   0,   0,  17,   0,   0,   0,
		0,  15,   0,   0,   0,   0,   0,  16,   0,   0,   0,   0,   0,  17,   0,   0,
	 15,   0,   0,   0,   0,   0,   0,  16,   0,   0,   0,   0,   0,   0,  17,   0
];


exports.isCheck = function(position) {
	return legality.isLegal(position) && attacks.isAttacked(position, position.king[position.turn], 1-position.turn);
};


exports.isCheckmate = function(position) {
	return legality.isLegal(position) && !hasMove(position) && attacks.isAttacked(position, position.king[position.turn], 1-position.turn);
};


exports.isStalemate = function(position) {
	return legality.isLegal(position) && !hasMove(position) && !attacks.isAttacked(position, position.king[position.turn], 1-position.turn);
};


var hasMove = exports.hasMove = function(position) {
	function MoveFound() {}
	try {
		generateMoves(position, function(descriptor) {
			if(descriptor) { throw new MoveFound(); }
		});
		return false;
	}
	catch(err) {
		if(err instanceof MoveFound) { return true; }
		else { throw err; }
	}
};


exports.moves = function(position) {
	var res = [];
	generateMoves(position, function(descriptor, generatePromotions) {
		if(descriptor) {
			if(generatePromotions) {
				res.push(moveDescriptor.makePromotion(descriptor._from, descriptor._to, position.turn, bt.QUEEN , descriptor._optionalPiece));
				res.push(moveDescriptor.makePromotion(descriptor._from, descriptor._to, position.turn, bt.ROOK  , descriptor._optionalPiece));
				res.push(moveDescriptor.makePromotion(descriptor._from, descriptor._to, position.turn, bt.BISHOP, descriptor._optionalPiece));
				res.push(moveDescriptor.makePromotion(descriptor._from, descriptor._to, position.turn, bt.KNIGHT, descriptor._optionalPiece));
			}
			else {
				res.push(descriptor);
			}
		}
	});
	return res;
};


/**
 * Generate all the legal moves of the given position.
 */
function generateMoves(position, fun) {

	// Ensure that the position is legal.
	if(!legality.isLegal(position)) { return; }

	// For all potential 'from' square...
	for(var from=0; from<120; from += (from /* jshint bitwise:false */ & 0x7 /* jshint bitwise:true */)===7 ? 9 : 1) {

		// Nothing to do if the current square does not contain a piece of the right color.
		var fromContent = position.board[from];
		var movingPiece = Math.floor(fromContent / 2);
		if(fromContent < 0 || fromContent%2 !== position.turn) {
			continue;
		}

		// Generate moves for pawns
		if(movingPiece === bt.PAWN) {

			// Capturing moves
			var attackDirections = attacks.ATTACK_DIRECTIONS[fromContent];
			for(var i=0; i<attackDirections.length; ++i) {
				var to = from + attackDirections[i];
				if((to /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0) {
					var toContent = position.board[to];
					if(toContent >= 0 && toContent%2 !== position.turn) { // regular capturing move
						fun(isKingSafeAfterMove(position, from, to, -1), to<8 || to>=112);
					}
					else if(toContent < 0 && to === (5-position.turn*3)*16 + position.enPassant) { // en-passant move
						fun(isKingSafeAfterMove(position, from, to, (4-position.turn)*16 + position.enPassant), false);
					}
				}
			}

			// Non-capturing moves
			var moveDirection = 16 - position.turn*32;
			var to = from + moveDirection;
			if(position.board[to] < 0) {
				fun(isKingSafeAfterMove(position, from, to, -1), to<8 || to>=112);

				// 2-square pawn move
				var firstSquareOfRow = (1 + position.turn*5) * 16;
				if(from>=firstSquareOfRow && from<firstSquareOfRow+8) {
					to += moveDirection;
					if(position.board[to] < 0) {
						fun(isKingSafeAfterMove(position, from, to, -1), false);
					}
				}
			}
		}

		// Generate moves for non-sliding non-pawn pieces
		else if(movingPiece===bt.KNIGHT || movingPiece===bt.KING) {
			var directions = attacks.ATTACK_DIRECTIONS[fromContent];
			for(var i=0; i<directions.length; ++i) {
				var to = from + directions[i];
				if((to /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0) {
					var toContent = position.board[to];
					if(toContent < 0 || toContent%2 !== position.turn) {
						fun(isKingSafeAfterMove(position, from, to, -1), false);
					}
				}
			}
		}

		// Generate moves for sliding pieces
		else {
			var directions = attacks.ATTACK_DIRECTIONS[fromContent];
			for(var i=0; i<directions.length; ++i) {
				for(var to=from+directions[i]; (to /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0; to+=directions[i]) {
					var toContent = position.board[to];
					if(toContent < 0 || toContent%2 !== position.turn) {
						fun(isKingSafeAfterMove(position, from, to, -1), false);
					}
					if(toContent >= 0) { break; }
				}
			}
		}

		// Generate castling moves
		if(movingPiece === bt.KING && position.castling[position.turn] !== 0) {
			var to = [from-2, from+2];
			for(var i=0; i<to.length; ++i) {
				fun(isCastlingLegal(position, from, to[i]), false);
			}
		}
	}
}


/**
 * Check whether the current player king is in check after moving from `from` to `to`.
 *
 * This function implements the verification steps (7) to (9) as defined in {@link #isMoveLegal}
 *
 * @param {number} enPassantSquare Index of the square where the "en-passant" taken pawn lies if any, `-1` otherwise.
 * @returns {boolean|MoveDescriptor} The move descriptor if the move is legal, `false` otherwise.
 */
var isKingSafeAfterMove = exports.isKingSafeAfterMove = function(position, from, to, enPassantSquare) {
	var fromContent = position.board[from];
	var toContent   = position.board[to  ];
	var movingPiece = Math.floor(fromContent / 2);

	// Step (7) -> Execute the displacement (castling moves are processed separately).
	position.board[to  ] = fromContent;
	position.board[from] = bt.EMPTY;
	if(enPassantSquare >= 0) {
		position.board[enPassantSquare] = bt.EMPTY;
	}

	// Step (8) -> Is the king safe after the displacement?
	var kingSquare    = movingPiece===bt.KING ? to : position.king[position.turn];
	var kingIsInCheck = attacks.isAttacked(position, kingSquare, 1-position.turn);

	// Step (9) -> Reverse the displacement.
	position.board[from] = fromContent;
	position.board[to  ] = toContent;
	if(enPassantSquare >= 0) {
		position.board[enPassantSquare] = bt.PAWN*2 + 1-position.turn;
	}

	// Final result
	if(kingIsInCheck) {
		return false;
	}
	else {
		if(enPassantSquare >= 0) {
			return moveDescriptor.makeEnPassant(from, to, enPassantSquare, position.turn);
		}
		else {
			return moveDescriptor.make(from, to, position.turn, movingPiece, toContent);
		}
	}
};


/**
 * Delegated method for checking whether a castling move is legal or not.
 *
 * TODO: make it chess-960 compatible.
 */
var isCastlingLegal = exports.isCastlingLegal = function(position, from, to) {

	// Ensure that the given underlying castling is allowed.
	var column = from < to ? 7 : 0;
	if((position.castling[position.turn] /* jshint bitwise:false */ & 1<<column /* jshint bitwise:true */) === 0) {
		return false;
	}

	// Origin and destination squares of the rook involved in the move.
	var rookFrom = column + position.turn*112;
	var rookTo   = (from + to) / 2;

	// Ensure that each square between the king and the rook is empty.
	var offset = from < rookFrom ? 1 : -1;
	for(var sq=from+offset; sq!==rookFrom; sq+=offset) {
		if(position.board[sq] !== bt.EMPTY) { return false; }
	}

	// The origin and destination squares of the king, and the square between them must not be attacked.
	var byWho = 1-position.turn;
	if(attacks.isAttacked(position, from, byWho) || attacks.isAttacked(position, to, byWho) || attacks.isAttacked(position, rookTo, byWho)) {
		return false;
	}

	// The move is legal -> generate the move descriptor.
	return moveDescriptor.makeCastling(from, to, rookFrom, rookTo, position.turn);
};


/**
 * Core algorithm to determine whether a move is legal or not. The verification flow is the following:
 *
 *  1. Ensure that the position itself is legal.
 *  2. Ensure that the origin square contains a piece (denoted as the moving-piece)
 *     whose color is the same than the color of the player about to play.
 *  4. Ensure that the displacement is geometrically correct, with respect to the moving piece.
 *  5. Check the content of the destination square.
 *  6. For the sliding pieces (and in case of a 2-square pawn move), ensure that there is no piece
 *     on the trajectory.
 *
 * The move is almost ensured to be legal at this point. The last condition to check
 * is whether the king of the current player will be in check after the move or not.
 *
 *  7. Execute the displacement from the origin to the destination square, in such a way that
 *     it can be reversed. Only the state of the board is updated at this point.
 *  8. Look for king attacks.
 *  9. Reverse the displacement.
 *
 * Castling moves fail at step (4). They are taken out of this flow and processed
 * by the dedicated method `isLegalCastling()`.
 */
exports.isMoveLegal = function(position, from, to) {

	// Step (1)
	if(!legality.isLegal(position)) { return false; }

	// Step (2)
	var fromContent = position.board[from];
	var toContent   = position.board[to  ];
	var movingPiece = Math.floor(fromContent / 2);
	if(fromContent < 0 || fromContent%2 !== position.turn) { return false; }

	// Miscellaneous variables
	var displacement = to - from + 119;
	var enPassantSquare = -1; // square where a pawn is taken if the move is "en-passant"
	var isTwoSquarePawnMove = false;
	var isPromotion = movingPiece===bt.PAWN && (to<8 || to>=112);

	// Step (4)
	if((DISPLACEMENT_LOOKUP[displacement] /* jshint bitwise:false */ & 1<<fromContent /* jshint bitwise:true */) === 0) {
		if(movingPiece === bt.PAWN && displacement === 151-position.turn*64) {
			var firstSquareOfRow = (1 + position.turn*5) * 16;
			if(from < firstSquareOfRow || from >= firstSquareOfRow+8) { return false; }
			isTwoSquarePawnMove = true;
		}
		else if(movingPiece === bt.KING && (displacement === 117 || displacement === 121)) {
			return isCastlingLegal(position, from, to);
		}
		else {
			return false;
		}
	}

	// Step (5) -> check the content of the destination square
	if(movingPiece === bt.PAWN) {
		if(displacement === 135-position.turn*32 || isTwoSquarePawnMove) { // non-capturing pawn move
			if(toContent !== bt.EMPTY) { return false; }
		}
		else if(toContent === bt.EMPTY) { // en-passant pawn move
			if(position.enPassant < 0 || to !== (5-position.turn*3)*16 + position.enPassant) { return false; }
			enPassantSquare = (4-position.turn)*16 + position.enPassant;
		}
		else { // regular capturing pawn move
			if(toContent%2 === position.turn) { return false; }
		}
	}
	else { // piece move
		if(toContent >= 0 && toContent%2 === position.turn) { return false; }
	}

	// Step (6) -> For sliding pieces, ensure that there is nothing between the origin and the destination squares.
	if(movingPiece === bt.BISHOP || movingPiece === bt.ROOK || movingPiece === bt.QUEEN) {
		var direction = SLIDING_DIRECTION[displacement];
		for(var sq=from + direction; sq !== to; sq += direction) {
			if(position.board[sq] !== bt.EMPTY) { return false; }
		}
	}
	else if(isTwoSquarePawnMove) { // two-square pawn moves also require this test.
		if(position.board[(from + to) / 2] !== bt.EMPTY) { return false; }
	}

	// Steps (7) to (9) are delegated to `isKingSafeAfterMove`.
	var descriptor = isKingSafeAfterMove(position, from, to, enPassantSquare);
	return descriptor && isPromotion ? function(promotion) {
		if(promotion !== bt.PAWN && promotion !== bt.KING) {
			return moveDescriptor.makePromotion(descriptor._from, descriptor._to, descriptor._movingPiece % 2, promotion, descriptor._optionalPiece);
		}
		return false;
	} : descriptor;
};


/**
 * Play the move corresponding to the given descriptor.
 */
exports.play = function(position, descriptor) {

	// Update the board.
	if(descriptor.isEnPassant()) {
		position.board[descriptor._optionalSquare1] = bt.EMPTY;
	}
	else if(descriptor.isCastling()) {
		position.board[descriptor._optionalSquare1] = bt.EMPTY;
		position.board[descriptor._optionalSquare2] = descriptor._optionalPiece;
	}
	position.board[descriptor._to] = descriptor._finalPiece;
	position.board[descriptor._from] = bt.EMPTY;

	var movingPiece = Math.floor(descriptor._movingPiece / 2);

	// Update the castling flags.
	if(movingPiece === bt.KING) {
		position.castling[position.turn] = 0;
	}
	if(descriptor._from <    8) { position.castling[bt.WHITE] /* jshint bitwise:false */ &= ~(1 <<  descriptor._from    ); /* jshint bitwise:true */ }
	if(descriptor._to   <    8) { position.castling[bt.WHITE] /* jshint bitwise:false */ &= ~(1 <<  descriptor._to      ); /* jshint bitwise:true */ }
	if(descriptor._from >= 112) { position.castling[bt.BLACK] /* jshint bitwise:false */ &= ~(1 << (descriptor._from%16)); /* jshint bitwise:true */ }
	if(descriptor._to   >= 112) { position.castling[bt.BLACK] /* jshint bitwise:false */ &= ~(1 << (descriptor._to  %16)); /* jshint bitwise:true */ }

	// Update the en-passant flag.
	position.enPassant = -1;
	if(movingPiece === bt.PAWN && Math.abs(descriptor._from - descriptor._to)===32) {
		var otherPawn = descriptor._movingPiece /* jshint bitwise:false */ ^ 0x01 /* jshint bitwise:true */;
		var squareBefore = descriptor._to - 1;
		var squareAfter = descriptor._to + 1;
		if(((squareBefore /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0 && position.board[squareBefore]===otherPawn) ||
			((squareAfter /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)===0 && position.board[squareAfter]===otherPawn)) {
			position.enPassant = descriptor._to % 16;
		}
	}

	// Update the computed flags.
	if(movingPiece === bt.KING) {
		position.king[position.turn] = descriptor._to;
	}

	// Toggle the turn flag.
	position.turn = 1 - position.turn;
};


/**
 * Determine if a null-move (i.e. switching the player about to play) can be play in the current position.
 * A null-move is possible if the position is legal and if the current player about to play is not in check.
 */
var isNullMoveLegal = exports.isNullMoveLegal = function(position) {
	return legality.isLegal(position) && !attacks.isAttacked(position, position.king[position.turn], 1-position.turn);
};


/**
 * Play a null-move on the current position if it is legal.
 */
exports.playNullMove = function(position) {
	if(isNullMoveLegal(position)) {
		position.turn = 1 - position.turn;
		position.enPassant = -1;
		return true;
	}
	else {
		return false;
	}
};

},{"../basetypes":2,"../movedescriptor":6,"./attacks":9,"./legality":12}],14:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('../basetypes');
var moveDescriptor = require('../movedescriptor');
var exception = require('../exception');
var i18n = require('../i18n');

var impl = require('./impl');
var fen = require('./fen');
var attacks = require('./attacks');
var legality = require('./legality');
var moveGeneration = require('./movegeneration');


/**
 * Convert the given move descriptor to standard algebraic notation.
 */
exports.getNotation = function(position, descriptor) {
	var res = '';

	// Castling move
	if(descriptor.isCastling()) {
		res = descriptor._from < descriptor._to ? 'O-O' : 'O-O-O';
	}

	// Pawn move
	else if(Math.floor(descriptor._movingPiece / 2) === bt.PAWN) {
		if(descriptor.isCapture()) {
			res += bt.fileToString(descriptor._from % 16) + 'x';
		}
		res += bt.squareToString(descriptor._to);
		if(descriptor.isPromotion()) {
			res += '=' + bt.pieceToString(Math.floor(descriptor._finalPiece / 2)).toUpperCase();
		}
	}

	// Non-pawn move
	else {
		res += bt.pieceToString(Math.floor(descriptor._movingPiece / 2)).toUpperCase();
		res += getDisambiguationSymbol(position, descriptor._from, descriptor._to);
		if(descriptor.isCapture()) {
			res += 'x';
		}
		res += bt.squareToString(descriptor._to);
	}

	// Check/checkmate detection and final result.
	res += getCheckCheckmateSymbol(position, descriptor);
	return res;
};


/**
 * Return the check/checkmate symbol to use for a move.
 */
function getCheckCheckmateSymbol(position, descriptor) {
	var nextPosition = impl.makeCopy(position);
	moveGeneration.play(nextPosition, descriptor);
	return moveGeneration.isCheck(nextPosition) ? (moveGeneration.hasMove(nextPosition) ? '+' : '#') : '';
}


/**
 * Return the disambiguation symbol to use for a move from `from` to `to`.
 */
function getDisambiguationSymbol(position, from, to) {
	var attackers = attacks.getAttacks(position, to, position.turn).filter(function(sq) { return position.board[sq]===position.board[from]; });

	// Disambiguation is not necessary if there less than 2 attackers.
	if(attackers.length < 2) {
		return '';
	}

	var foundNotPined = false;
	var foundOnSameRank = false;
	var foundOnSameFile = false;
	var rankFrom = Math.floor(from / 16);
	var fileFrom = from % 16;
	for(var i=0; i<attackers.length; ++i) {
		var sq = attackers[i];
		if(sq === from || isPinned(position, sq)) { continue; }

		foundNotPined = true;
		if(rankFrom === Math.floor(sq / 16)) { foundOnSameRank = true; }
		if(fileFrom === sq % 16) { foundOnSameFile = true; }
	}

	if(foundOnSameFile) {
		return foundOnSameRank ? bt.squareToString(from) : bt.rankToString(rankFrom);
	}
	else {
		return foundNotPined ? bt.fileToString(fileFrom) : '';
	}
}


/**
 * Whether the piece on the given square is pinned or not.
 */
function isPinned(position, sq) {
	var content = position.board[sq];
	position.board[sq] = bt.EMPTY;
	var result = attacks.isAttacked(position, position.king[position.turn], 1-position.turn);
	position.board[sq] = content;
	return result;
}


/**
 * Parse a move notation for the given position.
 *
 * @returns {MoveDescriptor}
 * @throws InvalidNotation
 */
exports.parseNotation = function(position, notation, strict) {

	// General syntax
	var m = /^(?:(O-O-O)|(O-O)|([KQRBN])([a-h])?([1-8])?(x)?([a-h][1-8])|(?:([a-h])(x)?)?([a-h][1-8])(?:(=)?([KQRBNP]))?)([\+#])?$/.exec(notation);
	if(m === null) {
		throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_MOVE_NOTATION_SYNTAX);
	}

	// Ensure that the position is legal.
	if(!legality.isLegal(position)) {
		throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.ILLEGAL_POSITION);
	}

	// CASTLING
	// m[1] -> O-O-O
	// m[2] -> O-O

	// NON-PAWN MOVE
	// m[3] -> moving piece
	// m[4] -> file disambiguation
	// m[5] -> rank disambiguation
	// m[6] -> x (capture symbol)
	// m[7] -> to

	// PAWN MOVE
	// m[ 8] -> from column (only for captures)
	// m[ 9] -> x (capture symbol)
	// m[10] -> to
	// m[11] -> = (promotion symbol)
	// m[12] -> promoted piece

	// OTHER
	// m[13] -> +/# (check/checkmate symbol)

	var descriptor = null;

	// Parse castling moves
	if(m[1] || m[2]) {
		var from = position.king[position.turn];
		var to = from + (m[2] ? 2 : -2);
		descriptor = moveGeneration.isCastlingLegal(position, from, to);
		if(!descriptor) {
			var message = m[2] ? i18n.ILLEGAL_KING_SIDE_CASTLING : i18n.ILLEGAL_QUEEN_SIDE_CASTLING;
			throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, message);
		}
	}

	// Non-pawn move
	else if(m[3]) {
		var movingPiece = bt.pieceFromString(m[3].toLowerCase());
		var to = bt.squareFromString(m[7]);
		var toContent = position.board[to];

		// Cannot take your own pieces!
		if(toContent >= 0 && toContent % 2 === position.turn) {
			throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.TRYING_TO_CAPTURE_YOUR_OWN_PIECES);
		}

		// Find the "from"-square candidates
		var attackers = attacks.getAttacks(position, to, position.turn).filter(function(sq) { return position.board[sq] === movingPiece*2 + position.turn; });

		// Apply disambiguation
		if(m[4]) {
			var fileFrom = bt.fileFromString(m[4]);
			attackers = attackers.filter(function(sq) { return sq%16 === fileFrom; });
		}
		if(m[5]) {
			var rankFrom = bt.rankFromString(m[5]);
			attackers = attackers.filter(function(sq) { return Math.floor(sq/16) === rankFrom; });
		}
		if(attackers.length===0) {
			var message = (m[4] || m[5]) ? i18n.NO_PIECE_CAN_MOVE_TO_DISAMBIGUATION : i18n.NO_PIECE_CAN_MOVE_TO;
			throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, message, m[3], m[7]);
		}

		// Compute the move descriptor for each remaining "from"-square candidate
		for(var i=0; i<attackers.length; ++i) {
			var currentDescriptor = moveGeneration.isKingSafeAfterMove(position, attackers[i], to, -1);
			if(currentDescriptor) {
				if(descriptor !== null) {
					throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.REQUIRE_DISAMBIGUATION, m[3], m[7]);
				}
				descriptor = currentDescriptor;
			}
		}
		if(descriptor === null) {
			var message = position.turn===bt.WHITE ? i18n.NOT_SAFE_FOR_WHITE_KING : i18n.NOT_SAFE_FOR_BLACK_KING;
			throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, message);
		}

		// STRICT-MODE -> check the disambiguation symbol.
		if(strict) {
			var expectedDS = getDisambiguationSymbol(position, descriptor._from, to);
			var observedDS = (m[4] ? m[4] : '') + (m[5] ? m[5] : '');
			if(expectedDS !== observedDS) {
				throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.WRONG_DISAMBIGUATION_SYMBOL, expectedDS, observedDS);
			}
		}
	}

	// Pawn move
	else if(m[10]) {
		var to = bt.squareFromString(m[10]);
		if(m[8]) {
			descriptor = getPawnCaptureDescriptor(position, notation, bt.fileFromString(m[8]), to);
		}
		else {
			descriptor = getPawnAdvanceDescriptor(position, notation, to);
		}

		// Ensure that the pawn move do not let a king in check.
		if(!descriptor) {
			var message = position.turn===bt.WHITE ? i18n.NOT_SAFE_FOR_WHITE_KING : i18n.NOT_SAFE_FOR_BLACK_KING;
			throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, message);
		}

		// Detect promotions
		if(to<8 || to>=112) {
			if(!m[12]) {
				throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.MISSING_PROMOTION);
			}
			var promotion = bt.pieceFromString(m[12].toLowerCase());
			if(promotion === bt.PAWN || promotion === bt.KING) {
				throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_PROMOTED_PIECE, m[12]);
			}
			descriptor = moveDescriptor.makePromotion(descriptor._from, descriptor._to, descriptor._movingPiece % 2, promotion, descriptor._optionalPiece);

			// STRICT MODE -> do not forget the `=` character!
			if(strict && !m[11]) {
				throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.MISSING_PROMOTION_SYMBOL);
			}
		}

		// Detect illegal promotion attempts!
		else if(m[12]) {
			throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.ILLEGAL_PROMOTION);
		}
	}

	// STRICT MODE
	if(strict) {
		if(descriptor.isCapture() !== (m[6] || m[9])) {
			var message = descriptor.isCapture() ? i18n.MISSING_CAPTURE_SYMBOL : i18n.INVALID_CAPTURE_SYMBOL;
			throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, message);
		}
		var expectedCCS = getCheckCheckmateSymbol(position, descriptor);
		var observedCCS = m[13] ? m[13] : '';
		if(expectedCCS !== observedCCS) {
			throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.WRONG_CHECK_CHECKMATE_SYMBOL, expectedCCS, observedCCS);
		}
	}

	// Final result
	return descriptor;
};


/**
 * Delegate function for capture pawn move parsing.
 *
 * @returns {boolean|MoveDescriptor}
 */
function getPawnCaptureDescriptor(position, notation, columnFrom, to) {

	// Ensure that `to` is not on the 1st row.
	var from = to - 16 + position.turn*32;
	if((from /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)!==0) {
		throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_CAPTURING_PAWN_MOVE);
	}

	// Compute the "from"-square.
	var columnTo = to % 16;
	if(columnTo - columnFrom === 1) { from -= 1; }
	else if(columnTo - columnFrom === -1) { from += 1; }
	else {
		throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_CAPTURING_PAWN_MOVE);
	}

	// Check the content of the "from"-square
	if(position.board[from] !== bt.PAWN*2+position.turn) {
		throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_CAPTURING_PAWN_MOVE);
	}

	// Check the content of the "to"-square
	var toContent = position.board[to];
	if(toContent < 0) {
		if(to === (5-position.turn*3)*16 + position.enPassant) { // detecting "en-passant" captures
			return moveGeneration.isKingSafeAfterMove(position, from, to, (4-position.turn)*16 + position.enPassant);
		}
	}
	else if(toContent % 2 !== position.turn) { // detecting regular captures
		return moveGeneration.isKingSafeAfterMove(position, from, to, -1);
	}

	throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_CAPTURING_PAWN_MOVE);
}


/**
 * Delegate function for non-capturing pawn move parsing.
 *
 * @returns {boolean|MoveDescriptor}
 */
function getPawnAdvanceDescriptor(position, notation, to) {

	// Ensure that `to` is not on the 1st row.
	var offset = 16 - position.turn*32;
	var from = to - offset;
	if((from /* jshint bitwise:false */ & 0x88 /* jshint bitwise:true */)!==0) {
		throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_NON_CAPTURING_PAWN_MOVE);
	}

	// Check the content of the "to"-square
	if(position.board[to] >= 0) {
		throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_NON_CAPTURING_PAWN_MOVE);
	}

	// Check the content of the "from"-square
	var expectedFromContent = bt.PAWN*2+position.turn;
	if(position.board[from] === expectedFromContent) {
		return moveGeneration.isKingSafeAfterMove(position, from, to, -1);
	}

	// Look for two-square pawn moves
	else if(position.board[from] < 0) {
		from -= offset;
		var firstSquareOfRow = (1 + position.turn*5) * 16;
		if(from >= firstSquareOfRow && from < firstSquareOfRow+8 && position.board[from] === expectedFromContent) {
			return moveGeneration.isKingSafeAfterMove(position, from, to, -1);
		}
	}

	throw new exception.InvalidNotation(fen.getFEN(position, 0, 1), notation, i18n.INVALID_NON_CAPTURING_PAWN_MOVE);
}

},{"../basetypes":2,"../exception":3,"../i18n":5,"../movedescriptor":6,"./attacks":9,"./fen":10,"./impl":11,"./legality":12,"./movegeneration":13}],15:[function(require,module,exports){
/******************************************************************************
 *                                                                            *
 *    This file is part of Kokopu, a JavaScript chess library.                *
 *    Copyright (C) 2018  Yoann Le Montagner <yo35 -at- melix.net>            *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or           *
 *    modify it under the terms of the GNU Lesser General Public License      *
 *    as published by the Free Software Foundation, either version 3 of       *
 *    the License, or (at your option) any later version.                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            *
 *    GNU Lesser General Public License for more details.                     *
 *                                                                            *
 *    You should have received a copy of the GNU Lesser General               *
 *    Public License along with this program. If not, see                     *
 *    <http://www.gnu.org/licenses/>.                                         *
 *                                                                            *
 ******************************************************************************/


'use strict';


var bt = require('./basetypes');
var exception = require('./exception');


/**
 * Iterate on each of the 64 squares.
 *
 * @param {function} fun
 */
exports.forEachSquare = function(fun) {
	for(var rank=0; rank<8; ++rank) {
		for(var file=0; file<8; ++file) {
			fun(bt.squareToString(rank * 16 + file));
		}
	}
};


/**
 * Return the color of a square.
 *
 * @param {string} square
 * @returns {string} Either `'w'` or `'b'`.
 */
exports.squareColor = function(square) {
	square = bt.squareFromString(square);
	if(square < 0) {
		throw new exception.IllegalArgument('squareColor()');
	}
	return Math.floor(square/16) % 2 === square % 2 ? 'b' : 'w';
};


/**
 * Return the coordinates of a square.
 *
 * @param {string} square
 * @returns {{rank:number, file:number}}
 */
exports.squareToCoordinates = function(square) {
	square = bt.squareFromString(square);
	if(square < 0) {
		throw new exception.IllegalArgument('squareToCoordinates()');
	}
	return { rank:Math.floor(square/16), file:square%16 };
};


/**
 * Return the square corresponding to the given coordinates.
 *
 * @param {number} file
 * @param {number} rank
 * @returns {string}
 */
exports.coordinatesToSquare = function(file, rank) {
	if(file<0 || file>=8 || rank<0 || rank>= 8) {
		throw new exception.IllegalArgument('coordinatesToSquare()');
	}
	return bt.fileToString(file) + bt.rankToString(rank);
};

},{"./basetypes":2,"./exception":3}]},{},[1])(1)
});
