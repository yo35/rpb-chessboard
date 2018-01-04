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

<h3><?php _e( 'Compatibility with plugins and themes that modify jQuery', 'rpb-chessboard' ); ?></h3>

<p>
	<?php
		echo sprintf(
			__(
				'The RPB Chessboard plugin relies heavily on the %1$sjQuery%2$s JavaScript library, ' .
				'which is packaged by default with WordPress. Yet, some plugins and themes modify ' .
				'the standard behavior of jQuery for their own needs, thus creating a conflict ' .
				'with RPB Chessboard. These settings aim at providing a workaround to deal with ' .
				'these conflicts.',
				'rpb-chessboard'
			),
			'<a href="https://jquery.com/">',
			'</a>'
		);
	?>
</p>



<p>
	<input type="hidden" name="noConflictForButton" value="0" />
	<input type="checkbox" id="rpbchessboard-noConflictForButtonField" name="noConflictForButton" value="1"
		<?php
		if ( $model->getNoConflictForButton() ) :
?>
checked="yes"<?php endif; ?>
	/>
	<label for="rpbchessboard-noConflictForButtonField">
		<?php _e( 'Try to fix jQuery\'s buttons', 'rpb-chessboard' ); ?>
	</label>
</p>

<p class="description">
	<?php
		_e(
			'Enable this option if the navigation bar does not appear below the navigation board, ' .
			'or if the closing button of the popup frame appears to be weirdly placed.',
			'rpb-chessboard'
		);
	?>
</p>
