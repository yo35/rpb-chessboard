<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2023  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php esc_html_e( 'Colorsets', 'rpb-chessboard' ); ?></h3>

<p>
	<a href="#" class="button rpbchessboard-action-add rpbchessboard-action-addColorset"><?php esc_html_e( 'Add new colorset', 'rpb-chessboard' ); ?></a>
</p>

<table class="wp-list-table widefat striped">

	<thead>
		<tr>
			<th scope="col"><?php esc_html_e( 'Name', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Slug', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Preview', 'rpb-chessboard' ); ?></th>
		</tr>
	</thead>

	<tbody>

		<tr>
			<?php RPBChessboardHelperLoader::printTemplate( 'admin-page/theming/colorset-edition', $model, array( 'isNew' => true ) ); ?>
		</tr>

		<?php foreach ( $model->getAvailableColorsets() as $colorset ) : ?>
		<tr class="rpbchessboard-colorsetRow" data-slug="<?php echo esc_attr( $colorset ); ?>">

			<td class="has-row-actions rpbchessboard-nameCell">
				<strong class="row-title"><?php echo esc_html( $model->getColorsetLabel( $colorset ) ); ?></strong>
				<?php if ( ! $model->isBuiltinColorset( $colorset ) ) : ?>
				<div class="row-actions rpbchessboard-rowActions">
					<span><a href="#" class="rpbchessboard-action-editColorset"><?php esc_html_e( 'Edit', 'rpb-chessboard' ); ?></a> |</span>
					<span><a href="#" class="rpbchessboard-action-deleteColorset"><?php esc_html_e( 'Delete', 'rpb-chessboard' ); ?></a></span>
				</div>
				<?php endif; ?>
			</td>

			<td class="rpbchessboard-slugCell"><?php echo esc_html( $colorset ); ?></td>

			<td class="rpbchessboard-previewCell">
				<input type="radio" name="preview_colorset" value="<?php echo esc_attr( $colorset ); ?>" id="rpbchessboard-colorsetPreview-<?php echo esc_attr( $colorset ); ?>"
					<?php echo 'original' === $colorset ? 'checked="yes"' : ''; ?>
				/>
				<label for="rpbchessboard-colorsetPreview-<?php echo esc_attr( $colorset ); ?>">
					<div class="rpbchessboard-table">
						<div>
							<div class="rpbchessboard-colorsetSnippet rpbchessboard-colorsetSnippet-w"></div>
							<div class="rpbchessboard-colorsetSnippet rpbchessboard-colorsetSnippet-b"></div>
						</div>
						<div>
							<div class="rpbchessboard-colorsetSnippet rpbchessboard-colorsetSnippet-b"></div>
							<div class="rpbchessboard-colorsetSnippet rpbchessboard-colorsetSnippet-w"></div>
						</div>
					</div>
				</label>
			</td>

			<?php
				if ( ! $model->isBuiltinColorset( $colorset ) ) {
					RPBChessboardHelperLoader::printTemplate(
						'admin-page/theming/colorset-edition',
						$model,
						array(
							'isNew'    => false,
							'colorset' => $colorset,
						)
					);
				}
			?>

		</tr>
		<?php endforeach; ?>
	</tbody>

	<tfoot>
		<tr>
			<th scope="col"><?php esc_html_e( 'Name', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Slug', 'rpb-chessboard' ); ?></th>
			<th scope="col"><?php esc_html_e( 'Preview', 'rpb-chessboard' ); ?></th>
		</tr>
	</tfoot>

</table>


<form id="rpbchessboard-deleteColorsetForm" action="<?php echo esc_url( $model->getSubPageLink( $model->getCurrentSubPage() ) ); ?>" method="post">
	<input type="hidden" name="rpbchessboard_action" value="<?php echo esc_attr( $model->getFormDeleteColorsetAction() ); ?>" />
	<input type="hidden" name="colorset" value="" />
	<?php wp_nonce_field( 'rpbchessboard_post_action' ); ?>
</form>


<script type="text/javascript">
	jQuery(document).ready(function($) {

		// Fill the snippets.
		$('.rpbchessboard-colorsetRow').each(function() {
			var colorset = $(this).data('slug');
			$('.rpbchessboard-colorsetSnippet-b', this).css('background-color', RPBChessboard.colorsetData[colorset].b);
			$('.rpbchessboard-colorsetSnippet-w', this).css('background-color', RPBChessboard.colorsetData[colorset].w);
		});

	});
</script>
