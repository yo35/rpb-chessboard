<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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
	<?php
		esc_html_e(
			'These settings control the default aspect and behavior of the chess diagrams and chess games ' .
			'inserted in posts and pages with the corresponding blocks. ' .
			'All these settings can be overridden for each block by tuning the appropriate option in the block right-side panel.',
			'rpb-chessboard'
		);
	?>
</p>

<?php
	RPBChessboardHelperLoader::printTemplateLegacy( 'AdminPage/Options/General/BoardAspect', $model );
	RPBChessboardHelperLoader::printTemplateLegacy( 'AdminPage/Options/General/DiagramAlignment', $model );
	RPBChessboardHelperLoader::printTemplateLegacy( 'AdminPage/Options/General/PieceSymbols', $model );
	RPBChessboardHelperLoader::printTemplateLegacy( 'AdminPage/Options/General/NavigationBoard', $model );
	RPBChessboardHelperLoader::printTemplateLegacy( 'AdminPage/Options/General/NavigationButtons', $model );
	RPBChessboardHelperLoader::printTemplateLegacy( 'AdminPage/Options/General/MoveAnimation', $model );
?>
