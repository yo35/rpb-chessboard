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

<div id="rpbchessboard-adminPage" class="wrap">
	<h1>RPB Chessboard</h1>

	<div id="rpbchessboard-adminPageMenu">
		<?php foreach ( $model->getSubPages() as $subPage ) : ?>
			<?php if ( $subPage === $model->getCurrentSubPage() ) : ?>
				<span id="rpbchessboard-activePageButton">
					<?php RPBChessboardHelperLoader::printTemplate( 'admin-page/menu/' . $subPage, $model ); ?>
				</span>
			<?php else : ?>
				<a href="<?php echo esc_url( $model->getSubPageLink( $subPage ) ); ?>" target="<?php echo $model->isExternalSubPage( $subPage ) ? '_blank' : '_self'; ?>">
					<?php RPBChessboardHelperLoader::printTemplate( 'admin-page/menu/' . $subPage, $model ); ?>
				</a>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>

	<div id="rpbchessboard-adminPageContent">
		<?php RPBChessboardHelperLoader::printTemplate( 'admin-page/' . $model->getTemplateName(), $model ); ?>
	</div>

</div>
