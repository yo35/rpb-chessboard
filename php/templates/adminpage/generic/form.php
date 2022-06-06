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

<form action="<?php echo esc_url( $model->getSubPageLink( $model->getCurrentSubPage() ) ); ?>" method="post">

	<input type="hidden" name="rpbchessboard_action" value="<?php echo esc_attr( $model->getFormSubmitAction() ); ?>" />
	<?php wp_nonce_field( 'rpbchessboard_post_action' ); ?>

	<div>
		<?php RPBChessboardHelperLoader::printTemplate( 'admin-page/' . $model->getFormTemplateName(), $model ); ?>
	</div>

	<p class="submit">
		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save changes', 'rpb-chessboard' ); ?>" />
		<a class="button" href="<?php echo esc_url( $model->getSubPageLink( $model->getCurrentSubPage() ) ); ?>"><?php esc_html_e( 'Cancel', 'rpb-chessboard' ); ?></a>
		<a class="button" id="rpbchessboard-resetButton" href="#"><?php esc_html_e( 'Reset settings', 'rpb-chessboard' ); ?></a>
	</p>

	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#rpbchessboard-resetButton').click(function(e) {
				e.preventDefault();

				// Ask for confirmation from the user.
				var message = <?php echo wp_json_encode( __( 'This will reset all the settings in this page to their default values. Press OK to confirm...', 'rpb-chessboard' ) ); ?>;
				if(!confirm(message)) { return; }

				// Change the action and validate the form.
				var form = $(this).closest('form');
				$('input[name="rpbchessboard_action"]', form).val(<?php echo wp_json_encode( $model->getFormResetAction() ); ?>);
				form.submit();
			});
		});
	</script>

</form>
