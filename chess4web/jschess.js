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


/******************************************************************************/
/*** Elementary types ***/

/**
 * Column (from a to h)
 * @{
 */
const COLUMN_A=0;
const COLUMN_B=1;
const COLUMN_C=2;
const COLUMN_D=3;
const COLUMN_E=4;
const COLUMN_F=5;
const COLUMN_G=6;
const COLUMN_H=7;
///@}

/**
 * Row (from one to eight)
 * @{
 */
const ROW_1=0;
const ROW_2=1;
const ROW_3=2;
const ROW_4=3;
const ROW_5=4;
const ROW_6=5;
const ROW_7=6;
const ROW_8=7;
///@}

/**
 * Square
 * @{
 */
const A8=56; const B8=57; const C8=58; const D8=59; const E8=60; const F8=61; const G8=62; const H8=63;
const A7=48; const B7=49; const C7=50; const D7=51; const E7=52; const F7=53; const G7=54; const H7=55;
const A6=40; const B6=41; const C6=42; const D6=43; const E6=44; const F6=45; const G6=46; const H6=47;
const A5=32; const B5=33; const C5=34; const D5=35; const E5=36; const F5=37; const G5=38; const H5=39;
const A4=24; const B4=25; const C4=26; const D4=27; const E4=28; const F4=29; const G4=30; const H4=31;
const A3=16; const B3=17; const C3=18; const D3=19; const E3=20; const F3=21; const G3=22; const H3=23;
const A2= 8; const B2= 9; const C2=10; const D2=11; const E2=12; const F2=13; const G2=14; const H2=15;
const A1= 0; const B1= 1; const C1= 2; const D1= 3; const E1= 4; const F1= 5; const G1= 6; const H1= 7;
///@}

/**
 * Color (either white or black)
 * @{
 */
const WHITE=0;
const BLACK=1;
///@}

/**
 * Piece
 * @{
 */
const KING  =0;
const QUEEN =1;
const ROOK  =2;
const BISHOP=3;
const KNIGHT=4;
const PAWN  =5;
///@}

/**
 * Colored piece
 * @{
 */
const WHITE_KING  = 0;
const WHITE_QUEEN = 2;
const WHITE_ROOK  = 4;
const WHITE_BISHOP= 6;
const WHITE_KNIGHT= 8;
const WHITE_PAWN  =10;
const BLACK_KING  = 1;
const BLACK_QUEEN = 3;
const BLACK_ROOK  = 5;
const BLACK_BISHOP= 7;
const BLACK_KNIGHT= 9;
const BLACK_PAWN  =11;
///@}

/**
 * Castle
 * @{
 */
const OO =0;
const OOO=1;
///@}

/**
 * Colored castle
 * @{
 */
const WHITE_OO =0;
const WHITE_OOO=2;
const BLACK_OO =1;
const BLACK_OOO=3;
///@}

/**
 * Conversion between elementary types
 * @{
 */
function makeColoredPiece (color, piece ) { return piece *2+color ; }
function makeColoredCastle(color, castle) { return castle*2+color ; }
function makeSquare       (row  , column) { return row   *8+column; }
function coloredPieceToPiece  (coloredPiece ) { return Math.floor(coloredPiece /2); }
function coloredCastleToCastle(coloredCastle) { return Math.floor(coloredCastle/2); }
function squareToRow          (square       ) { return Math.floor(square       /8); }
function coloredPieceToColor (coloredPiece ) { return coloredPiece %2; }
function coloredCastleToColor(coloredCastle) { return coloredCastle%2; }
function squareToColumn      (square       ) { return square       %8; }
///@}



/******************************************************************************/
/*** Move ***/

/**
 * Create a no-promotion move
 */
function makeSimpleMove(from, to)
{
	return 24576 + 64*to + from;
}

/**
 * Create a promotion move
 */
function makePromotionMove(from, to, promotion)
{
	return promotion*4096 + 64*to + from;
}

/**
 * Check whether a move is a promotion move
 */
function isPromotion(move)
{
	return (move < 24576);
}

/**
 * Retrieve the 'from' square of a move
 */
function getFrom(move)
{
	return move % 64;
}

/**
 * Retrieve the 'to' square of a move
 */
function getTo(move)
{
	return Math.floor(move/64) % 64;
}

/**
 * Retrieve the promotion piece
 */
function getPromotion(move)
{
	return Math.floor(move/4096);
}



/******************************************************************************/
/*** Position ***/

/**
 * Create a new position
 * \param board 64-long array containing either colored pieces or null
 * \param turn Indicate which player is about to play
 * \param castleRight 4-long array containing boolean values
 * \param enPassant Indicate a column if a pawn has just made a 2-square displacement
 *        on this column, or null otherwise
 */
function Position(board, turn, castleRight, enPassant)
{
	this.board       = board      ;
	this.turn        = turn       ;
	this.castleRight = castleRight;
	this.enPassant   = enPassant  ;
}
Position.prototype =
{
	clone: function()
	{
		return new Position(this.board.slice(0), this.turn, this.castleRight.slice(0), this.enPassant);
	}
}

/**
 * Create an empty position
 */
function makeEmptyPosition()
{
	var board = Array();
	for(var k=0; k<64; ++k) {
		board[k] = null;
	}
	var castleRight = Array();
	for(var k=0; k<4; ++k) {
		castleRight[k] = false;
	}
	return new Position(board, WHITE, castleRight, null);
}

/**
 * Create a new start-of-game position
 */
function makeInitialPosition()
{
	var board = Array();
	board[A1] = WHITE_ROOK  ; board[A8] = BLACK_ROOK  ;
	board[B1] = WHITE_KNIGHT; board[B8] = BLACK_KNIGHT;
	board[C1] = WHITE_BISHOP; board[C8] = BLACK_BISHOP;
	board[D1] = WHITE_QUEEN ; board[D8] = BLACK_QUEEN ;
	board[E1] = WHITE_KING  ; board[E8] = BLACK_KING  ;
	board[F1] = WHITE_BISHOP; board[F8] = BLACK_BISHOP;
	board[G1] = WHITE_KNIGHT; board[G8] = BLACK_KNIGHT;
	board[H1] = WHITE_ROOK  ; board[H8] = BLACK_ROOK  ;
	for(var k=COLUMN_A; k<=COLUMN_H; ++k) {
		board[makeSquare(ROW_2, k)] = WHITE_PAWN;
		board[makeSquare(ROW_7, k)] = BLACK_PAWN;
	}
	for(var k=A3; k<=H6; ++k) {
		board[k] = null;
	}
	var castleRight = Array();
	for(var k=0; k<4; ++k) {
		castleRight[k] = true;
	}
	return new Position(board, WHITE, castleRight, null);
}

/**
 * Create a new position from a FEN string
 * \param fenString FEN String, with its 6 fields (board, turn, castle rights,
 *        en-passant, half-move clock, full-move number)
 * \param parseMoveFields Set this argument to true to parse the two last fields
 *        of the FEN string (optional, default: false)
 * \throw ParsingException If the FEN string is not consistent
 */
function parseFEN(fenString, parseMoveFields)
{
	if(parseMoveFields===undefined) {
		parseMoveFields = false;
	}

	// Split the FEN string into 6 fields
	var fields = fenString.split(" ");
	if(fields.length!=6) {
		throw new ParsingException(fenString);
	}

	// Board parsing
	var boardField = fields[0];
	var board = Array();
	var rowFields = boardField.split("/");
	if(rowFields.length!=8) {
		throw new ParsingException(boardField);
	}
	for(var r=ROW_8; r>=ROW_1; --r) {
		var rowField = rowFields[7-r];
		var c=COLUMN_A;
		var k=0;
		while(k<rowField.length && c<=COLUMN_H) {
			var charCode = rowField.charCodeAt(k);
			if(charCode>=49 && charCode<=56) {
				var lastColumn = c + (charCode-49);
				if(lastColumn>COLUMN_H) {
					throw new ParsingException(rowField);
				}
				for(; c<=lastColumn; ++c) {
					board[makeSquare(r, c)] = null;
				}
			}
			else {
				board[makeSquare(r, c)] = parseColoredPiece(rowField.charAt(k));
				++c;
			}
			++k;
		}
		if(k!=rowField.length || c!=8) {
			throw new ParsingException(rowField);
		}
	}

	// Turn parsing
	var turnField = fields[1];
	var turn = parseColor(turnField);

	// Castle right parsing
	var castleRightField = fields[2];
	if(castleRightField.length==0 || castleRightField.length>4) {
		throw new ParsingException(castleRightField);
	}
	var castleRight = Array();
	for(var k=0; k<4; ++k) {
		castleRight[k] = false;
	}
	if(castleRightField!="-") {
		for(var k=0; k<castleRightField.length; ++k) {
			var coloredCastle = parseColoredCastle(castleRightField.charAt(k));
			castleRight[coloredCastle] = true;
			// Non-strict FEN verification: allows things like castleRightField="KKq"
		}
	}

	// En-passant parsing
	var enPassantField = fields[3];
	var enPassant = null;
	if(enPassantField!="-") {
		var enPassantSquare = parseSquare(enPassantField);
		enPassant = squareToColumn(enPassantSquare);
		// Non-strict FEN verification: allows things like enPassantSquare=e5
		// (should be either e3 or e6 depending on the 'turn' flag)
	}

	// Move fields
	if(parseMoveFields) {
		var position = new Position(board, turn, castleRight, enPassant);
		var halfMoveClock  = parseInt(fields[4], 10);
		var fullMoveNumber = parseInt(fields[5], 10);
		if(halfMoveClock<0) {
			throw new ParsingException(fields[4]);
		}
		if(fullMoveNumber<0) {
			throw new ParsingException(fields[5]);
		}
		// None-strict FEN verification: allows those field to be decimal, or to
		// start with a 0
		return {
			position      : position,
			halfMoveClock : halfMoveClock,
			fullMoveNumber: fullMoveNumber
		};
	}
	else {
		return new Position(board, turn, castleRight, enPassant);
	}
}

/**
 * Retrieve the FEN representation of a position
 * \param position Position to convert
 * \param halfMoveClock Half-move clock flag (optional, default: 0)
 * \param fullMoveNumber Full-move number flag (optional, default: 1)
 */
function positionToFEN(position, halfMoveClock, fullMoveNumber)
{
	if(halfMoveClock ===undefined) { halfMoveClock  = 0; }
	if(fullMoveNumber===undefined) { fullMoveNumber = 1; }
	var retVal = "";

	// Board scanning
	for(var r=ROW_8; r>=ROW_1; --r) {
		if(r!=ROW_8) {
			retVal += "/";
		}
		var emptySquareCount = 0;
		for(var c=COLUMN_A; c<=COLUMN_H; ++c) {
			var coloredPiece = position.board[makeSquare(r, c)];
			if(coloredPiece==null) {
				++emptySquareCount;
			}
			else {
				if(emptySquareCount>0) {
					retVal += emptySquareCount;
					emptySquareCount = 0;
				}
				retVal += coloredPieceToString(coloredPiece);
			}
		}
		if(emptySquareCount>0) {
			retVal += emptySquareCount;
		}
	}

	// Turn flag
	retVal += " " + colorToString(position.turn) + " ";

	// Castle right flags
	var atLeastOneCastle = false;
	for(var cc=0; cc<4; ++cc) {
		if(position.castleRight[cc]) {
			atLeastOneCastle = true;
			retVal += coloredCastleToString(cc);
		}
	}
	retVal += atLeastOneCastle ? " " : "- ";

	// En-passant flag
	if(position.enPassant==null) {
		retVal += "- ";
	}
	else {
		var enPassantSquare = makeSquare(position.turn==WHITE ? ROW_3 : ROW_6, position.enPassant);
		retVal += squareToString(enPassantSquare) + " ";
	}

	// Move count flags
	retVal += halfMoveClock + " " + fullMoveNumber;
	return retVal;
}

/**
 * Return true if the player 'byWho' attacks the square 'square' on the given position
 */
function isControlled(position, square, byWho)
{
	var r0 = squareToRow   (square);
	var c0 = squareToColumn(square);
	var currentColoredPiece, k;
	var currentColoredQueen = makeColoredPiece(byWho, QUEEN);

	// Auxiliary function
	// Return true if (row,column) corresponds to a square inside the board, and
	// if the corresponding square in 'position' contains 'coloredPiece'
	function expectedAt(coloredPiece, row, column)
	{
		if(row>=0 && row<8 && column>=0 && column<8) {
			return position.board[makeSquare(row, column)]==coloredPiece;
		}
		else {
			return false;
		}
	}

	// Look for pawn control
	currentColoredPiece = makeColoredPiece(byWho, PAWN);
	k = byWho==WHITE ? r0-1 : r0+1;
	if(expectedAt(currentColoredPiece, k, c0-1)) return true;
	if(expectedAt(currentColoredPiece, k, c0+1)) return true;

	// Look for knight control
	currentColoredPiece = makeColoredPiece(byWho, KNIGHT);
	if(expectedAt(currentColoredPiece, r0-2, c0-1)) return true;
	if(expectedAt(currentColoredPiece, r0-2, c0+1)) return true;
	if(expectedAt(currentColoredPiece, r0-1, c0-2)) return true;
	if(expectedAt(currentColoredPiece, r0-1, c0+2)) return true;
	if(expectedAt(currentColoredPiece, r0+1, c0-2)) return true;
	if(expectedAt(currentColoredPiece, r0+1, c0+2)) return true;
	if(expectedAt(currentColoredPiece, r0+2, c0-1)) return true;
	if(expectedAt(currentColoredPiece, r0+2, c0+1)) return true;

	// Look for king control
	currentColoredPiece = makeColoredPiece(byWho, KING);
	if(expectedAt(currentColoredPiece, r0-1, c0-1)) return true;
	if(expectedAt(currentColoredPiece, r0-1, c0  )) return true;
	if(expectedAt(currentColoredPiece, r0-1, c0+1)) return true;
	if(expectedAt(currentColoredPiece, r0  , c0-1)) return true;
	if(expectedAt(currentColoredPiece, r0  , c0+1)) return true;
	if(expectedAt(currentColoredPiece, r0+1, c0-1)) return true;
	if(expectedAt(currentColoredPiece, r0+1, c0  )) return true;
	if(expectedAt(currentColoredPiece, r0+1, c0+1)) return true;

	// Look for rook or queen control
	currentColoredPiece = makeColoredPiece(byWho, ROOK);
	k = 1;
	while(expectedAt(null, r0+k, c0)) ++k;
	if(expectedAt(currentColoredPiece, r0+k, c0)) return true;
	if(expectedAt(currentColoredQueen, r0+k, c0)) return true;
	k = 1;
	while(expectedAt(null, r0-k, c0)) ++k;
	if(expectedAt(currentColoredPiece, r0-k, c0)) return true;
	if(expectedAt(currentColoredQueen, r0-k, c0)) return true;
	k = 1;
	while(expectedAt(null, r0, c0+k)) ++k;
	if(expectedAt(currentColoredPiece, r0, c0+k)) return true;
	if(expectedAt(currentColoredQueen, r0, c0+k)) return true;
	k = 1;
	while(expectedAt(null, r0, c0-k)) ++k;
	if(expectedAt(currentColoredPiece, r0, c0-k)) return true;
	if(expectedAt(currentColoredQueen, r0, c0-k)) return true;

	// Look for bishop or queen control
	currentColoredPiece = makeColoredPiece(byWho, BISHOP);
	k = 1;
	while(expectedAt(null, r0-k, c0-k)) ++k;
	if(expectedAt(currentColoredPiece, r0-k, c0-k)) return true;
	if(expectedAt(currentColoredQueen, r0-k, c0-k)) return true;
	k = 1;
	while(expectedAt(null, r0-k, c0+k)) ++k;
	if(expectedAt(currentColoredPiece, r0-k, c0+k)) return true;
	if(expectedAt(currentColoredQueen, r0-k, c0+k)) return true;
	k = 1;
	while(expectedAt(null, r0+k, c0-k)) ++k;
	if(expectedAt(currentColoredPiece, r0+k, c0-k)) return true;
	if(expectedAt(currentColoredQueen, r0+k, c0-k)) return true;
	k = 1;
	while(expectedAt(null, r0+k, c0+k)) ++k;
	if(expectedAt(currentColoredPiece, r0+k, c0+k)) return true;
	if(expectedAt(currentColoredQueen, r0+k, c0+k)) return true;

	// At this point, we can tell there is no control
	return false;
}

/**
 * Return the square on which the 'color' king is (null is returned if there is
 * no such king)
 */
function searchKing(position, color)
{
	if(color==WHITE) {
		for(var k=A1; k<=H8; ++k) {
			if(position.board[k]==WHITE_KING)
				return k;
		}
	}
	else if(color==BLACK) {
		for(var k=H8; k>=A1; --k) {
			if(position.board[k]==BLACK_KING)
				return k;
		}
	}
	return null;
}

/**
 * Check whether a position is legal
 *
 * A position is considered to be legal if all the following conditions are met:
 *  (1) There is exactly one white king and one black king on the board.
 *  (2) The player that is not about to play is not check.
 *  (3) For each castle flag set, there are a rook and a king on the corresponding
 *      initial squares.
 *  (4) The pawn situation is consistent with the en-passant flag if it is set.
 *      For instance, if it is set to the 'e' column and black is about to play,
 *      squares e2 and e3 must be empty, and there must be a white pawn on e4.
 *  (5) There are no pawn on rows 1 and 8.
 *
 * \param position Position to look at
 * \param relaxFlags If set to true, no error will be reported due to (3) and (4),
 *        but the corresponding flags will be adjust to make the position consistent
 *        (optional, default: false)
 */
function isLegal(position, relaxFlags)
{
	if(relaxFlags===undefined) {
		relaxFlags = false;
	}

	// Condition (1)
	var whiteKingEncountered = false;
	var blackKingEncountered = false;
	for(var k=A1; k<=H8; ++k) {
		var coloredPiece = position.board[k];
		if(coloredPiece==WHITE_KING) {
			if(whiteKingEncountered) {
				return false;
			}
			whiteKingEncountered = true;
		}
		else if(coloredPiece==BLACK_KING) {
			if(blackKingEncountered) {
				return false;
			}
			blackKingEncountered = true;
		}
	}
	if(!(whiteKingEncountered && blackKingEncountered)) {
		return false;
	}

	// Condition (2)
	var defenselessKingSquare = searchKing(position, 1-position.turn);
	if(isControlled(position, defenselessKingSquare, position.turn)) {
		return false;
	}

	// Condition (5)
	for(var k=COLUMN_A; k<=COLUMN_H; ++k) {
		var coloredPiece1 = position.board[makeSquare(ROW_1, k)];
		var coloredPiece8 = position.board[makeSquare(ROW_8, k)];
		if(coloredPiece1!=null && coloredPieceToPiece(coloredPiece1)==PAWN) {
			return false;
		}
		if(coloredPiece8!=null && coloredPieceToPiece(coloredPiece8)==PAWN) {
			return false;
		}
	}

	// Condition (3)
	for(var k=0; k<4; ++k) {
		if(position.castleRight[k]) {
			var color      = coloredCastleToColor(k);
			var row        = (color==WHITE) ? ROW_1 : ROW_8;
			var rookColumn = coloredCastleToCastle(k)==OO ? COLUMN_H : COLUMN_A;
			if(!(
				position.board[makeSquare(row, COLUMN_E  )]==makeColoredPiece(color, KING) &&
				position.board[makeSquare(row, rookColumn)]==makeColoredPiece(color, ROOK)
			)) {
				if(relaxFlags)
					position.castleRight[k] = false;
				else
					return false;
			}
		}
	}

	// Condition (4)
	if(position.enPassant!=null) {
		var square2 = makeSquare(position.turn==BLACK ? ROW_2 : ROW_7, position.enPassant);
		var square3 = makeSquare(position.turn==BLACK ? ROW_3 : ROW_6, position.enPassant);
		var square4 = makeSquare(position.turn==BLACK ? ROW_4 : ROW_5, position.enPassant);
		if(!(
			position.board[square2]==null &&
			position.board[square3]==null &&
			position.board[square4]==makeColoredPiece(1-position.turn, PAWN)
		)) {
			if(relaxFlags)
				position.enPassant = null;
			else
				return false;
		}
	}

	// At this point, the position is OK
	return true;
}

/**
 * Check whether the piece on square 'from' can move to square 'to'
 * \pre The position must be legal
 */
function isLegalDisplacement(position, from, to)
{
	// Check that there is a piece with correct color on the 'from' square
	var movingPiece = position.board[from];
	if(movingPiece==null || coloredPieceToColor(movingPiece)!=position.turn) {
		return false;
	}
	movingPiece = coloredPieceToPiece(movingPiece);
	var enPassantSquare = null;

	// Special case for pawn moves
	if(movingPiece==PAWN) {
		var deltaRow = position.turn==WHITE ? 1 : -1;
		var rowFrom    = squareToRow   (from);
		var rowTo      = squareToRow   (to  );
		var columnFrom = squareToColumn(from);
		var columnTo   = squareToColumn(to  );

		// Take moves
		if(Math.abs(columnFrom-columnTo)==1) {
			if(rowTo-rowFrom!=deltaRow) {
				return false;
			}
			if(position.board[to]==null) {
				var row5 = position.turn==WHITE ? ROW_5 : ROW_4;
				if(!(position.enPassant==columnTo && rowFrom==row5)) {
					return false;
				}
				enPassantSquare = makeSquare(rowFrom, columnTo);
			}
			else if(coloredPieceToColor(position.board[to])==position.turn) {
				return false;
			}
		}

		// No-take moves
		else if(columnFrom==columnTo) {
			if(position.board[to]!=null) {
				return false;
			}
			if(rowTo-rowFrom!=deltaRow) {
				var row2 = position.turn==WHITE ? ROW_2 : ROW_7;
				var row3 = position.turn==WHITE ? ROW_3 : ROW_6;
				var row4 = position.turn==WHITE ? ROW_4 : ROW_5;
				if(!(rowFrom==row2 && rowTo==row4 && position.board[makeSquare(row3, columnFrom)]==null)) {
					return false;
				}
			}
		}

		// Other cases
		else {
			return false;
		}
	}

	// Regular pieces
	else {

		// Check that the 'to' square does not contain a "friend" piece
		var targetSquare = position.board[to];
		if(targetSquare!=null && coloredPieceToColor(targetSquare)==position.turn) {
			return false;
		}

		// Analyze the displacement geometry
		switch(movingPiece) {

			// King
			case KING:
				if(!isLegalKingMove(position, from, to)) {

					// Deal with castle displacement
					var row = position.turn==WHITE ? ROW_1 : ROW_8;
					if(from!=makeSquare(row, COLUMN_E)) {
						return false;
					}
					var coloredCastle;
					var middleSquare ;
					if(to==makeSquare(row, COLUMN_G)) {
						coloredCastle = makeColoredCastle(position.turn, OO);
						middleSquare  = makeSquare(row, COLUMN_F);
					}
					else if(to==makeSquare(row, COLUMN_C)) {
						coloredCastle = makeColoredCastle(position.turn, OOO);
						middleSquare  = makeSquare(row, COLUMN_D);
						if(position.board[makeSquare(row, COLUMN_B)]!=null) {
							return false;
						}
					}
					else {
						return false;
					}
					return position.castleRight[coloredCastle] &&
						position.board[to]==null && position.board[middleSquare]==null && !(
						isControlled(position, from        , 1-position.turn) ||
						isControlled(position, to          , 1-position.turn) ||
						isControlled(position, middleSquare, 1-position.turn));
				}
				break;

			// Regular pieces
			case QUEEN : if(!isLegalQueenMove (position, from, to)) return false; break;
			case ROOK  : if(!isLegalRookMove  (position, from, to)) return false; break;
			case BISHOP: if(!isLegalBishopMove(position, from, to)) return false; break;
			case KNIGHT: if(!isLegalKnightMove(position, from, to)) return false; break;

			// Default case
			default:
				return false;
		}
	}

	// Execute the displacement (remember we know at this point it is not a castle)
	var oldEnPassantContent = 0;
	var oldToContent = position.board[to];
	position.board[to  ] = position.board[from];
	position.board[from] = null;
	if(enPassantSquare!=null) {
		oldEnPassantContent = position.board[enPassantSquare];
		position.board[enPassantSquare] = null;
	}
	var kingIsCheckFinally = isControlled(position, searchKing(position, position.turn), 1-position.turn);
	position.board[from] = position.board[to];
	position.board[to  ] = oldToContent;
	if(enPassantSquare!=null) {
		position.board[enPassantSquare] = oldEnPassantContent;
	}
	return !kingIsCheckFinally;
}

/**
 * Return true if a king can go from square 'from' to square 'to' (without castling)
 */
function isLegalKingMove(position, from, to)
{
	if(from==to) {
		return false;
	}
	var rowDiff    = Math.abs(squareToRow   (to) - squareToRow   (from));
	var columnDiff = Math.abs(squareToColumn(to) - squareToColumn(from));
	return rowDiff<=1 && columnDiff<=1;
}

/**
 * Return true if a knight can go from square 'from' to square 'to'
 */
function isLegalKnightMove(position, from, to)
{
	var rowDiff    = Math.abs(squareToRow   (to) - squareToRow   (from));
	var columnDiff = Math.abs(squareToColumn(to) - squareToColumn(from));
	return (rowDiff==2 && columnDiff==1) || (rowDiff==1 && columnDiff==2);
}

/**
 * Return true if a bishop can go from square 'from' to square 'to'
 */
function isLegalBishopMove(position, from, to)
{
	if(from==to) {
		return false;
	}
	var rowFrom    = squareToRow   (from);
	var rowTo      = squareToRow   (to  );
	var columnFrom = squareToColumn(from);
	var columnTo   = squareToColumn(to  );
	if(Math.abs(rowTo-rowFrom)==Math.abs(columnTo-columnFrom)) {
		var ri = rowTo   >rowFrom    ? 1 : -1;
		var ci = columnTo>columnFrom ? 1 : -1;
		var r = rowFrom    + ri;
		var c = columnFrom + ci;
		while(r!=rowTo) {
			if(position.board[makeSquare(r, c)]!=null) {
				return false;
			}
			r += ri;
			c += ci;
		}
		return true;
	}
	else {
		return false;
	}
}

/**
 * Return true if a bishop can go from square 'from' to square 'to'
 */
function isLegalRookMove(position, from, to)
{
	if(from==to) {
		return false;
	}
	var rowFrom    = squareToRow   (from);
	var rowTo      = squareToRow   (to  );
	var columnFrom = squareToColumn(from);
	var columnTo   = squareToColumn(to  );
	if(rowFrom==rowTo) {
		for(var k=Math.min(columnFrom, columnTo)+1; k<Math.max(columnFrom, columnTo); ++k) {
			if(position.board[makeSquare(rowFrom, k)]!=null)
				return false;
		}
		return true;
	}
	else if(columnFrom==columnTo) {
		for(var k=Math.min(rowFrom, rowTo)+1; k<Math.max(rowFrom, rowTo); ++k) {
			if(position.board[makeSquare(k, columnFrom)]!=null)
				return false;
		}
		return true;
	}
	else {
		return false;
	}
}

/**
 * Return true if a queen can go from square 'from' to square 'to'
 */
function isLegalQueenMove(position, from, to)
{
	return isLegalRookMove(position, from, to) || isLegalBishopMove(position, from, to);
}

/**
 * Check whether the given displacement is a promotion
 * \pre Both the position and the displacement must be legal
 */
function isPromotionDisplacement(position, from, to)
{
	var movingPiece = coloredPieceToPiece(position.board[from]);
	var rowTo       = squareToRow(to);
	return movingPiece==PAWN && (rowTo==ROW_1 || rowTo==ROW_8);
}

/**
 * Check if the given move is legal
 * \pre The position must be legal
 */
function isLegalMove(position, move)
{
	var from = getFrom(move);
	var to   = getTo  (move);
	if(isLegalDisplacement(position, from, to)) {
		var promotionDisplacement = isPromotionDisplacement(position, from, to);
		var promotionMove         = isPromotion(move);
		if(promotionDisplacement && promotionMove) {
			var promoted = getPromotion(move);
			return promoted==QUEEN || promoted==BISHOP || promoted==ROOK || promoted==KNIGHT;
		}
		else if(!promotionDisplacement && !promotionMove) {
			return true;
		}
		else {
			return false;
		}
	}
	else {
		return false;
	}
}

/**
 * Execute the given move
 * \pre Both the position and the move must be legal
 */
function play(position, move)
{
	var from        = getFrom(move);
	var to          = getTo  (move);
	var movingPiece = coloredPieceToPiece(position.board[from]);

	// Special case when the moving piece is a pawn
	if(movingPiece==PAWN) {
		var rowFrom    = squareToRow   (from);
		var rowTo      = squareToRow   (to  );
		var columnFrom = squareToColumn(from);
		var columnTo   = squareToColumn(to  );
		if(position.board[to]==null && columnFrom!=columnTo) {
			position.board[makeSquare(rowFrom, columnTo)] = null;
		}
		position.board[to  ] = isPromotion(move) ? makeColoredPiece(position.turn, getPromotion(move)) : position.board[from];
		position.board[from] = null;
		position.enPassant   = Math.abs(rowFrom-rowTo)==2 ? columnFrom : null;
	}

	// Regular case
	else {
		position.board[to  ] = position.board[from];
		position.board[from] = null;
		position.enPassant   = null;

		// Deal with rook displacement in case of castling move
		if(movingPiece==KING && squareToColumn(from)==COLUMN_E) {
			var columnTo = squareToColumn(to);
			if(columnTo==COLUMN_G) {
				var row      = position.turn==WHITE ? ROW_1 : ROW_8;
				var rookFrom = makeSquare(row, COLUMN_H);
				var rookTo   = makeSquare(row, COLUMN_F);
				position.board[rookTo  ] = position.board[rookFrom];
				position.board[rookFrom] = null;
			}
			else if(columnTo==COLUMN_C) {
				var row      = position.turn==WHITE ? ROW_1 : ROW_8;
				var rookFrom = makeSquare(row, COLUMN_A);
				var rookTo   = makeSquare(row, COLUMN_D);
				position.board[rookTo  ] = position.board[rookFrom];
				position.board[rookFrom] = null;
			}
		}
	}

	// Update the turn and castle right flags
	switch(from) {
		case E1: position.castleRight[WHITE_OOO]=false; position.castleRight[WHITE_OO]=false; break;
		case E8: position.castleRight[BLACK_OOO]=false; position.castleRight[BLACK_OO]=false; break;
		case A1: position.castleRight[WHITE_OOO]=false; break;
		case H1: position.castleRight[WHITE_OO ]=false; break;
		case A8: position.castleRight[BLACK_OOO]=false; break;
		case H8: position.castleRight[BLACK_OO ]=false; break;
		default: break;
	}
	switch(to) {
		case A1: position.castleRight[WHITE_OOO]=false; break;
		case H1: position.castleRight[WHITE_OO ]=false; break;
		case A8: position.castleRight[BLACK_OOO]=false; break;
		case H8: position.castleRight[BLACK_OO ]=false; break;
		default: break;
	}
	position.turn = 1-position.turn;
}

/**
 * Execute a null-move (i.e. reverse the turn)
 * \pre The position must be legal, and the player that is about to play must not be in check
 */
function playNullMove(position)
{
	position.turn      = 1-position.turn;
	position.enPassant = null;
}

/**
 * Return true if the player that is about to play is in check
 * \pre The position must be legal
 */
function isCheck(position)
{
	var kingSquare = searchKing(position, position.turn);
	return isControlled(position, kingSquare, 1-position.turn);
}

/**
 * Return true the player that is about to play is checkmate
 * \pre The position must be legal
 */
function isCheckmate(position)
{
	return noLegalMove(position) && isCheck(position);
}

/**
 * Return true the player that is about to play is stalemate
 * \pre The position must be legal
 */
function isStalemate(position)
{
	return noLegalMove(position) && !isCheck(position);
}

/**
 * Return true if no legal move can be executed in the current position (which
 * mean that either a checkmate or a stalemate situation has been reached)
 * \pre The position must be legal
 */
function noLegalMove(position)
{
	for(var from=A1; from<=H8; ++from) {
		var movingPiece = position.board[from];
		if(movingPiece!=null && coloredPieceToColor(movingPiece)==position.turn) {
			for(var to=A1; to<=H8; ++to) {
				if(isLegalDisplacement(position, from, to))
					return false;
			}
		}
	}
	return true;
}

/**
 * Return the list of legal moves
 * \pre The position must be legal
 */
function legalMoves(position)
{
	var retVal = Array();
	for(var from=A1; from<=H8; ++from) {
		var movingPiece = position.board[from];
		if(movingPiece!=null && coloredPieceToColor(movingPiece)==position.turn) {
			for(var to=A1; to<=H8; ++to) {
				if(isLegalDisplacement(position, from, to)) {
					if(isPromotionDisplacement(position, from, to)) {
						retVal.push(makePromotionMove(from, to, QUEEN ));
						retVal.push(makePromotionMove(from, to, ROOK  ));
						retVal.push(makePromotionMove(from, to, BISHOP));
						retVal.push(makePromotionMove(from, to, KNIGHT));
					}
					else {
						retVal.push(makeSimpleMove(from, to));
					}
				}
			}
		}
	}
	return retVal;
}

/**
 * Return the standard algebraic notation of a move
 * \pre Both the position and the move must be legal
 */
function getNotation(position, move)
{
	var from        = getFrom(move);
	var to          = getTo  (move);
	var movingPiece = coloredPieceToPiece(position.board[from]);
	var rowFrom     = squareToRow   (from);
	//var rowTo       = squareToRow   (to  );
	var columnFrom  = squareToColumn(from);
	var columnTo    = squareToColumn(to  );
	var retVal = "";

	// Special case for castling moves
	if(movingPiece==KING && columnFrom==COLUMN_E && columnTo==COLUMN_G) {
		retVal += castleToString(OO);
	}
	else if(movingPiece==KING && columnFrom==COLUMN_E && columnTo==COLUMN_C) {
		retVal += castleToString(OOO);
	}

	// Special case for pawn moves
	else if(movingPiece==PAWN) {
		if(columnFrom!=columnTo) {
			retVal += columnToString(columnFrom) + "x";
		}
		retVal += squareToString(to);
		if(isPromotion(move)) {
			retVal += "=" + pieceToString(getPromotion(move));
		}
	}

	// General case
	else {
		retVal += pieceToString(movingPiece);
		var needFromSquareIdentification = false;
		var rowIsDiscriminant            = true ;
		var columnIsDiscriminant         = true ;
		for(var k=A1; k<=H8; ++k) {
			if(k!=from && position.board[k]==position.board[from] && isLegalDisplacement(position, k, to)) {
				needFromSquareIdentification = true;
				if(squareToRow   (k)==rowFrom   ) rowIsDiscriminant    = false;
				if(squareToColumn(k)==columnFrom) columnIsDiscriminant = false;
			}
		}
		if(needFromSquareIdentification) {
			if(columnIsDiscriminant)
				retVal += columnToString(columnFrom);
			else
				retVal += rowIsDiscriminant ? rowToString(rowFrom) : squareToString(from);
		}
		if(position.board[to]!=null) {
			retVal += "x";
		}
		retVal += squareToString(to);
	}

	// '+' or '#' sign in case of check or checkmate
	var nextPosition = position.clone();
	play(nextPosition, move);
	if(isCheck(nextPosition)) {
		retVal += noLegalMove(nextPosition) ? "#" : "+";
	}
	return retVal;
}

/**
 * Return the move corresponding to a given SAN notation
 * \param position The position before the move is played
 * \param sanString Notation string to parse
 * \param strictSANVerification If set to true, the function will check that
 *        getNotation(position, move)==sanString where 'move' is the candidate
 *        result, and throw an exception if this equality does not hold
 *        (optional, default: true)
 * \throw ParsingException If there is no legal move corresponding to 'sanString',
 *        if there is an ambiguity, or if 'strictSANVerification' is set to true
 *        and there are notation errors in 'sanString'
 * \pre The position must be legal
 */
function parseNotation(position, sanString, strictSANVerification)
{
	if(strictSANVerification===undefined) {
		strictSANVerification = true;
	}

	// Keep only the meaningful characters
	var workingSAN = sanString.replace(/[^OKQRBNa-h1-8]/g, "");
	if(workingSAN.length<1) {
		throw new ParsingException(sanString);
	}
	var retVal = null;

	// Special case for castling
	if(workingSAN=="OO" || workingSAN=="OOO") {
		var castle = workingSAN=="OO" ? OO : OOO;
		if(!position.castleRight[makeColoredCastle(position.turn, castle)]) {
			throw new ParsingException(sanString);
		}
		var row      = position.turn==WHITE ? ROW_1 : ROW_8;
		var columnTo = castle==OO ? COLUMN_G : COLUMN_C;
		retVal       = makeSimpleMove(makeSquare(row, COLUMN_E), makeSquare(row, columnTo));
		if(!isLegalMove(position, retVal)) {
			throw new ParsingException(sanString);
		}
	}

	// General case
	else {

		// Extract information about the displacement from the notation
		var movingPiece;
		var promotedPiece = null;
		switch(workingSAN.charAt(0)) {
			case "K": movingPiece = KING  ; break;
			case "Q": movingPiece = QUEEN ; break;
			case "R": movingPiece = ROOK  ; break;
			case "B": movingPiece = BISHOP; break;
			case "N": movingPiece = KNIGHT; break;
			default:  movingPiece = PAWN  ; break;
		}
		if(movingPiece==PAWN) {
			switch(workingSAN.charAt(workingSAN.length-1)) {
				case "Q": promotedPiece = QUEEN ; break;
				case "R": promotedPiece = ROOK  ; break;
				case "B": promotedPiece = BISHOP; break;
				case "N": promotedPiece = KNIGHT; break;
				default: break;
			}
		}
		movingPiece = makeColoredPiece(position.turn, movingPiece);
		workingSAN = workingSAN.replace(/[^a-h1-8]/g, "");
		if(!(workingSAN.length>=2 && workingSAN.length<=4)) {
			throw new ParsingException(sanString);
		}
		var to = parseSquare(workingSAN.substr(workingSAN.length-2, 2));
		var rowFrom    = null;
		var columnFrom = null;
		if(workingSAN.length==4) {
			var from = parseSquare(workingSAN.substr(0, 2));
			rowFrom    = squareToRow   (from);
			columnFrom = squareToColumn(from);
		}
		else if(workingSAN.length==3) {
			switch(workingSAN.charAt(0)) {
				case "1": rowFrom = ROW_1; break;
				case "2": rowFrom = ROW_2; break;
				case "3": rowFrom = ROW_3; break;
				case "4": rowFrom = ROW_4; break;
				case "5": rowFrom = ROW_5; break;
				case "6": rowFrom = ROW_6; break;
				case "7": rowFrom = ROW_7; break;
				case "8": rowFrom = ROW_8; break;
				case "a": columnFrom = COLUMN_A; break;
				case "b": columnFrom = COLUMN_B; break;
				case "c": columnFrom = COLUMN_C; break;
				case "d": columnFrom = COLUMN_D; break;
				case "e": columnFrom = COLUMN_E; break;
				case "f": columnFrom = COLUMN_F; break;
				case "g": columnFrom = COLUMN_G; break;
				case "h": columnFrom = COLUMN_H; break;
				default: break;
			}
		}

		// Try to guess the corresponding move
		function tryFromSquare(from)
		{
			if(position.board[from]!=movingPiece) {
				return;
			}
			var triedMove = promotedPiece==null ? makeSimpleMove(from, to) : makePromotionMove(from, to, promotedPiece);
			if(isLegalMove(position, triedMove)) {
				if(retVal==null) {
					retVal = triedMove;
				}
				else {
					throw new ParsingException(sanString);
				}
			}
		}
		if(columnFrom==null) {
			if(rowFrom==null) {
				for(var k=A1; k<=H8; ++k) {
					tryFromSquare(k);
				}
			}
			else {
				for(var k=COLUMN_A; k<=COLUMN_H; ++k) {
					tryFromSquare(makeSquare(rowFrom, k));
				}
			}
		}
		else {
			if(rowFrom==null) {
				for(var k=ROW_1; k<=ROW_8; ++k) {
					tryFromSquare(makeSquare(k, columnFrom));
				}
			}
			else {
				tryFromSquare(makeSquare(rowFrom, columnFrom));
			}
		}
		if(retVal==null) {
			throw new ParsingException(sanString);
		}
	}

	// Strict SAN verification
	if(strictSANVerification && sanString!=getNotation(position, retVal)) {
		throw new ParsingException(sanString);
	}
	return retVal;
}



/******************************************************************************/
/*** Parsing routines ***/

/**
 * Exception returned by parsing routines
 */
function ParsingException(wrongString)
{
	this.wrongString = wrongString;
}

/**
 * Parse a column
 * \throw ParsingException If the input string does not understandable as a column
 */
function parseColumn(data)
{
	switch(data)
	{
		case "a": return COLUMN_A;
		case "b": return COLUMN_B;
		case "c": return COLUMN_C;
		case "d": return COLUMN_D;
		case "e": return COLUMN_E;
		case "f": return COLUMN_F;
		case "g": return COLUMN_G;
		case "h": return COLUMN_H;
		default: throw new ParsingException(data);
	}
}

/**
 * Parse a column
 * \throw ParsingException If the input string does not understandable as a row
 */
function parseRow(data)
{
	switch(data) {
		case "1": return ROW_1;
		case "2": return ROW_2;
		case "3": return ROW_3;
		case "4": return ROW_4;
		case "5": return ROW_5;
		case "6": return ROW_6;
		case "7": return ROW_7;
		case "8": return ROW_8;
		default: throw new ParsingException(data);
	}
}

/**
 * Parse a square
 * \throw ParsingException If the input string does not understandable as a square
 */
function parseSquare(data)
{
	if(data.length!=2) {
		throw new ParsingException(data);
	}
	return makeSquare(parseRow(data.charAt(1)), parseColumn(data.charAt(0)));
}

/**
 * Parse a color
 * \throw ParsingException If the input string does not understandable as a color
 */
function parseColor(data)
{
	switch(data) {
		case "w": return WHITE;
		case "b": return BLACK;
		default: throw new ParsingException(data);
	}
}

/**
 * Parse a piece
 * \throw ParsingException If the input string does not understandable as a piece
 */
function parsePiece(data)
{
	switch(data) {
		case "K": return KING  ;
		case "Q": return QUEEN ;
		case "R": return ROOK  ;
		case "B": return BISHOP;
		case "N": return KNIGHT;
		case "P": return PAWN  ;
		default: throw new ParsingException(data);
	}
}

/**
 * Parse a colored piece
 * \throw ParsingException If the input string does not understandable as a colored piece
 */
function parseColoredPiece(data)
{
	switch(data) {
		case "K": return WHITE_KING  ;
		case "Q": return WHITE_QUEEN ;
		case "R": return WHITE_ROOK  ;
		case "B": return WHITE_BISHOP;
		case "N": return WHITE_KNIGHT;
		case "P": return WHITE_PAWN  ;
		case "k": return BLACK_KING  ;
		case "q": return BLACK_QUEEN ;
		case "r": return BLACK_ROOK  ;
		case "b": return BLACK_BISHOP;
		case "n": return BLACK_KNIGHT;
		case "p": return BLACK_PAWN  ;
		default: throw new ParsingException(data);
	}
}

/**
 * Parse a castle
 * \throw ParsingException If the input string does not understandable as a castle
 */
function parseCastle(data)
{
	switch(data) {
		case "O-O"  : return OO ;
		case "O-O-O": return OOO;
		default: throw new ParsingException(data);
	}
}

/**
 * Parse a colored castle
 * \throw ParsingException If the input string does not understandable as a colored castle
 */
function parseColoredCastle(data)
{
	switch(data) {
		case "K": return WHITE_OO ;
		case "Q": return WHITE_OOO;
		case "k": return BLACK_OO ;
		case "q": return BLACK_OOO;
		default: throw new ParsingException(data);
	}
}

/**
 * Parse a move
 * \throw ParsingException If the input string does not understandable as a move
 */
function parseMove(data)
{
	if(data.length==4) {
		return makeSimpleMove(parseSquare(data.substr(0,2)), parseSquare(data.substr(2,2)));
	}
	else if(data.length==5) {
		return makePromotionMove(parseSquare(data.substr(0,2)), parseSquare(data.substr(2,2)), parsePiece(data.charAt(4)));
	}
	else {
		throw new ParsingException(data);
	}
}



/******************************************************************************/
/*** To-string routines ***/

/**
 * Convert a column into a string
 */
function columnToString(column)
{
	switch(column)
	{
		case COLUMN_A: return "a";
		case COLUMN_B: return "b";
		case COLUMN_C: return "c";
		case COLUMN_D: return "d";
		case COLUMN_E: return "e";
		case COLUMN_F: return "f";
		case COLUMN_G: return "g";
		case COLUMN_H: return "h";
		default: return null;
	}
}

/**
 * Convert a row into a string
 */
function rowToString(column)
{
	switch(column)
	{
		case ROW_1: return "1";
		case ROW_2: return "2";
		case ROW_3: return "3";
		case ROW_4: return "4";
		case ROW_5: return "5";
		case ROW_6: return "6";
		case ROW_7: return "7";
		case ROW_8: return "8";
		default: return null;
	}
}

/**
 * Convert a square into a string
 */
function squareToString(square)
{
	return columnToString(squareToColumn(square)) + rowToString(squareToRow(square));
}

/**
 * Convert a color into a string
 */
function colorToString(color)
{
	switch(color)
	{
		case WHITE: return "w";
		case BLACK: return "b";
		default: return null;
	}
}

/**
 * Convert a piece into a string
 */
function pieceToString(piece)
{
	switch(piece)
	{
		case KING  : return "K";
		case QUEEN : return "Q";
		case ROOK  : return "R";
		case BISHOP: return "B";
		case KNIGHT: return "N";
		case PAWN  : return "P";
		default: return null;
	}
}

/**
 * Convert a colored piece into a string
 */
function coloredPieceToString(coloredPiece)
{
	switch(coloredPiece)
	{
		case WHITE_KING  : return "K";
		case WHITE_QUEEN : return "Q";
		case WHITE_ROOK  : return "R";
		case WHITE_BISHOP: return "B";
		case WHITE_KNIGHT: return "N";
		case WHITE_PAWN  : return "P";
		case BLACK_KING  : return "k";
		case BLACK_QUEEN : return "q";
		case BLACK_ROOK  : return "r";
		case BLACK_BISHOP: return "b";
		case BLACK_KNIGHT: return "n";
		case BLACK_PAWN  : return "p";
		default: return null;
	}
}

/**
 * Convert a castle into a string
 */
function castleToString(castle)
{
	switch(castle)
	{
		case OO : return "O-O"  ;
		case OOO: return "O-O-O";
		default: return null;
	}
}

/**
 * Convert a colored castle into a string
 */
function coloredCastleToString(coloredCastle)
{
	switch(coloredCastle)
	{
		case WHITE_OO : return "K";
		case WHITE_OOO: return "Q";
		case BLACK_OO : return "k";
		case BLACK_OOO: return "q";
		default: return null;
	}
}

/**
 * Convert a move into a string
 */
function moveToString(move)
{
	var retVal = squareToString(getFrom(move)) + squareToString(getTo(move));
	if(isPromotion(move)) {
		retVal += pieceToString(getPromotion(move));
	}
	return retVal;
}

/**
 * Convert a position into an human-readable string
 * \remark Should be displayed with fixed-width font
 */
function positionToString(position)
{
	var retVal = "+---+---+---+---+---+---+---+---+\n";
	for(var r=ROW_8; r>=ROW_1; --r) {
		for(var c=COLUMN_A; c<=COLUMN_H; ++c) {
			var square = makeSquare(r, c);
			var coloredPiece = position.board[square];
			retVal += "| " + (coloredPiece==null ? " " : coloredPieceToString(coloredPiece)) + " ";
		}
		retVal += "|\n";
		retVal += "+---+---+---+---+---+---+---+---+\n";
	}
	retVal += colorToString(position.turn) + " ";
	var atLeastOneCastle = false;
	for(var cc=0; cc<4; ++cc) {
		if(position.castleRight[cc]) {
			atLeastOneCastle = true;
			retVal += coloredCastleToString(cc);
		}
	}
	retVal += atLeastOneCastle ? " " : "- ";
	retVal += (position.enPassant==null) ? "-" : columnToString(position.enPassant);
	return retVal;
}
