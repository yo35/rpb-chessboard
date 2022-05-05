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

<div class="wrap rpbchessboard-adminPage">

	<h2><?php echo esc_html( $model->getTitle() ); ?></h2>

	<noscript>
		<div class="error">
			<p>
				<?php esc_html_e( 'To work properly, the RPB Chessboard plugin needs JavaScript to be activated in your browser.', 'rpb-chessboard' ); ?>
			</p>
		</div>
	</noscript>

	<?php if ( $model->hasPostMessage() ) : ?>
	<div class="updated">
		<p><?php echo esc_html( $model->getPostMessage() ); ?></p>
	</div>
	<?php endif; ?>

	<?php if ( $model->hasSubPages() ) : ?>
	<ul id="rpbchessboard-subPageSelector" class="subsubsub">
		<?php foreach ( $model->getSubPages() as $subPage ) : ?>
		<li>
			<a href="<?php echo esc_url( $subPage->link ); ?>" class="<?php echo $subPage->selected ? 'current' : ''; ?>">
				<?php echo wp_kses_post( $subPage->label ); ?>
			</a>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<?php RPBChessboardHelperLoader::printTemplate( $model->getPageTemplateName(), $model ); ?>

</div>
