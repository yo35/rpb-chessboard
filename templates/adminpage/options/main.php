<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<div id="rpbchessboard-optionPage" class="rpbchessboard-jQuery-enableSmoothness">
	<form action="<?php echo htmlspecialchars( $model->getFormActionURL() ); ?>" method="post">

		<input type="hidden" name="rpbchessboard_action" value="<?php echo htmlspecialchars( $model->getFormAction() ); ?>" />

		<div>
			<?php RPBChessboardHelperLoader::printTemplate( $model->getSubPageTemplateName(), $model ); ?>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save changes', 'rpb-chessboard' ); ?>" />
				<a class="button" href="<?php echo htmlspecialchars( $model->getFormActionURL() ); ?>"><?php _e( 'Cancel', 'rpb-chessboard' ); ?></a>
				<a class="button" id="rpbchessboard-resetButton" href="#"><?php _e( 'Reset settings', 'rpb-chessboard' ); ?></a>
			</p>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#rpbchessboard-resetButton').click(function(e) {
					e.preventDefault();

					// Ask for confirmation from the user.
					var message = 
					<?php
						echo json_encode( __( 'This will reset all the settings in this page to their default values. Press OK to confirm...', 'rpb-chessboard' ) );
					?>
					;
					if(!confirm(message)) { return; }

					// Change the action and validate the form.
					var form = $(this).closest('form');
					$('input[name="rpbchessboard_action"]', form).val(<?php echo json_encode( $model->getFormResetAction() ); ?>);
					form.submit();
				});
			});
		</script>

	</form>
</div>
