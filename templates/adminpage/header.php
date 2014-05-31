<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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

<h2><?php echo htmlspecialchars($model->getTitle()); ?></h2>

<noscript>
	<div class="error">
		<p><?php
			_e('To work properly, the RPB Chessboard plugin needs javascript to be activated in your browser.',
				'rpbchessboard');
		?></p>
	</div>
</noscript>

<?php if($model->getPostMessage() !== ''): ?>
	<div class="updated">
		<p><?php echo htmlspecialchars($model->getPostMessage()); ?></p>
	</div>
<?php endif; ?>

<?php if($model->hasSubPages()): ?>
	<ul id="rpbchessboard-be-subPageSelector" class="subsubsub">
		<?php foreach($model->getSubPages() as $subPage): ?>

			<li>
				<a
					href="<?php echo htmlspecialchars($subPage->link); ?>"
					class="<?php if($subPage->selected) { echo 'current'; } ?>"
				><?php echo htmlspecialchars($subPage->label); ?></a>
			</li>

		<?php endforeach; ?>
	</ul>
<?php endif; ?>
