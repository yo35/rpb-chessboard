<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2024  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h3><?php esc_html_e( 'Piecesets', 'rpb-chessboard' ); ?></h3>

<p>
	<a href="#" class="button rpbchessboard-action-add rpbchessboard-action-addPieceset"><?php esc_html_e( 'Add new pieceset', 'rpb-chessboard' ); ?></a>
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
			<?php RPBChessboardHelperLoader::printTemplate( 'admin-page/theming/pieceset-edition', $model, array( 'isNew' => true ) ); ?>
		</tr>

		<?php foreach ( $model->getAvailablePiecesets() as $pieceset ) : ?>
		<tr class="rpbchessboard-piecesetRow" data-slug="<?php echo esc_attr( $pieceset ); ?>">

			<td class="has-row-actions rpbchessboard-nameCell">
				<strong class="row-title"><?php echo esc_html( $model->getPiecesetLabel( $pieceset ) ); ?></strong>
				<?php if ( ! $model->isBuiltinPieceset( $pieceset ) ) : ?>
				<div class="row-actions rpbchessboard-rowActions">
					<span><a href="#" class="rpbchessboard-action-editPieceset"><?php esc_html_e( 'Edit', 'rpb-chessboard' ); ?></a> |</span>
					<span><a href="#" class="rpbchessboard-action-deletePieceset"><?php esc_html_e( 'Delete', 'rpb-chessboard' ); ?></a></span>
				</div>
				<?php endif; ?>
			</td>

			<td class="rpbchessboard-slugCell"><?php echo esc_html( $pieceset ); ?></td>

			<td class="rpbchessboard-previewCell">
				<input type="radio" name="preview_pieceset" value="<?php echo esc_attr( $pieceset ); ?>" id="rpbchessboard-piecesetPreview-<?php echo esc_attr( $pieceset ); ?>"
					<?php echo 'cburnett' === $pieceset ? 'checked="yes"' : ''; ?>
				/>
				<label for="rpbchessboard-piecesetPreview-<?php echo esc_attr( $pieceset ); ?>">
					<div class="rpbchessboard-table">
						<div>
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-bk" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-bq" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-br" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-bb" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-bn" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-bp" src="#" />
						</div>
						<div>
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-wk" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-wq" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-wr" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-wb" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-wn" src="#" />
							<img class="rpbchessboard-piecesetSnippet rpbchessboard-piecesetSnippet-wp" src="#" />
						</div>
					</div>
				</label>
			</td>

			<?php
				if ( ! $model->isBuiltinPieceset( $pieceset ) ) {
					RPBChessboardHelperLoader::printTemplate(
						'admin-page/theming/pieceset-edition',
						$model,
						array(
							'isNew'    => false,
							'pieceset' => $pieceset,
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


<form id="rpbchessboard-deletePiecesetForm" action="<?php echo esc_url( $model->getSubPageLink( $model->getCurrentSubPage() ) ); ?>" method="post">
	<input type="hidden" name="rpbchessboard_action" value="<?php echo esc_attr( $model->getFormDeletePiecesetAction() ); ?>" />
	<input type="hidden" name="pieceset" value="" />
	<?php wp_nonce_field( 'rpbchessboard_post_action' ); ?>
</form>


<script type="text/javascript">
	jQuery(document).ready(function($) {

		// Fill the snippets.
		$('.rpbchessboard-piecesetRow').each(function() {
			var pieceset = $(this).data('slug');
			$('.rpbchessboard-piecesetSnippet-bk', this).attr('src', RPBChessboard.piecesetData[pieceset].bk);
			$('.rpbchessboard-piecesetSnippet-bq', this).attr('src', RPBChessboard.piecesetData[pieceset].bq);
			$('.rpbchessboard-piecesetSnippet-br', this).attr('src', RPBChessboard.piecesetData[pieceset].br);
			$('.rpbchessboard-piecesetSnippet-bb', this).attr('src', RPBChessboard.piecesetData[pieceset].bb);
			$('.rpbchessboard-piecesetSnippet-bn', this).attr('src', RPBChessboard.piecesetData[pieceset].bn);
			$('.rpbchessboard-piecesetSnippet-bp', this).attr('src', RPBChessboard.piecesetData[pieceset].bp);
			$('.rpbchessboard-piecesetSnippet-wk', this).attr('src', RPBChessboard.piecesetData[pieceset].wk);
			$('.rpbchessboard-piecesetSnippet-wq', this).attr('src', RPBChessboard.piecesetData[pieceset].wq);
			$('.rpbchessboard-piecesetSnippet-wr', this).attr('src', RPBChessboard.piecesetData[pieceset].wr);
			$('.rpbchessboard-piecesetSnippet-wb', this).attr('src', RPBChessboard.piecesetData[pieceset].wb);
			$('.rpbchessboard-piecesetSnippet-wn', this).attr('src', RPBChessboard.piecesetData[pieceset].wn);
			$('.rpbchessboard-piecesetSnippet-wp', this).attr('src', RPBChessboard.piecesetData[pieceset].wp);
		});

	});
</script>
