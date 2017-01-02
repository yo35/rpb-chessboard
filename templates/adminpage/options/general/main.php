<?php
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
?>

<p>
	<?php echo sprintf(
		__(
			'These settings control the default aspect and behavior of the chess diagrams and games ' .
			'inserted in posts and pages with the %1$s[%3$s][/%3$s]%2$s and %1$s[%4$s][/%4$s]%2$s tags. ' .
			'They can be overridden at each tag by passing appropriate tag attributes: ' .
			'see %5$shelp on FEN diagram attributes%7$s and %6$shelp on PGN game attributes%7$s for more details.',
		'rpbchessboard'),
		'<span class="rpbchessboard-sourceCode">',
		'</span>',
		htmlspecialchars($model->getFENShortcode()),
		htmlspecialchars($model->getPGNShortcode()),
		'<a href="' . htmlspecialchars($model->getHelpOnFENAttributesURL()) . '">',
		'<a href="' . htmlspecialchars($model->getHelpOnPGNAttributesURL()) . '">',
		'</a>'
	); ?>
</p>

<?php
	RPBChessboardHelperLoader::printTemplate('AdminPage/Options/General/BoardAspect'    , $model);
	RPBChessboardHelperLoader::printTemplate('AdminPage/Options/General/PieceSymbols'   , $model);
	RPBChessboardHelperLoader::printTemplate('AdminPage/Options/General/NavigationBoard', $model);
	RPBChessboardHelperLoader::printTemplate('AdminPage/Options/General/MoveAnimation'  , $model);
?>
