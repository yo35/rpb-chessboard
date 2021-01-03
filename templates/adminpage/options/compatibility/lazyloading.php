<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php esc_html_e( 'Compatibility with themes and plugins with complex behaviors', 'rpb-chessboard' ); ?></h3>

<p>
	<?php
		esc_html_e(
			'By default, the RPB Chessboard plugin tries to avoid loading its CSS and JavaScript files on pages ' .
			'with no chess diagram or game content, in order to reduce its impact on performance of your website as much as possible. ' .
			'Still, this approach may fail in non-standard situations: for example, if the the theme makes use of AJAX queries ' .
			'to render posts/pages, if you try to use chess diagrams or chess games in post/page comments, in bbPress forums, etc... ' .
			'Disable this option to avoid issues if you are in those situations.',
			'rpb-chessboard'
		);
	?>
</p>



<p>
	<input type="hidden" name="lazyLoadingForCSSAndJS" value="0" />
	<input type="checkbox" id="rpbchessboard-lazyLoadingForCSSAndJSField" name="lazyLoadingForCSSAndJS" value="1"
		<?php echo $model->getLazyLoadingForCSSAndJS() ? 'checked="yes"' : ''; ?>
	/>
	<label for="rpbchessboard-lazyLoadingForCSSAndJSField">
		<?php esc_html_e( 'Lazy-loading for CSS/JavaScript files', 'rpb-chessboard' ); ?>
	</label>
</p>

<p class="description">
	<?php
		esc_html_e(
			'Disable this option FEN diagrams or PGN games are not properly-rendered on your website.',
			'rpb-chessboard'
		);
	?>
</p>
