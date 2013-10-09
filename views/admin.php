<?php

require_once(RPBCHESSBOARD_ABSPATH.'views/abstractview.php');

/**
 * Generic view for the administration pages.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardViewAdmin extends RPBChessboardAbstractView
{
	public function display()
	{
		$model = $this->getModel();
		echo '<div class="wrap">';
		include(RPBCHESSBOARD_ABSPATH.'templates/admin/header.php');
		include(RPBCHESSBOARD_ABSPATH.'templates/admin/'.strtolower($model->getTemplateName()).'.php');
		echo '</div>';
	}
}
