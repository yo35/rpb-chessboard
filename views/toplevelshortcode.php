<?php

require_once(RPBCHESSBOARD_ABSPATH.'views/abstractview.php');


/**
 * Generic view for the short-codes [fen][/fen] and [pgn][/pgn].
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardViewTopLevelShortcode extends RPBChessboardAbstractView
{
	public function display()
	{
		$model = $this->getModel();
		include(RPBCHESSBOARD_ABSPATH.'templates/shortcode/'.strtolower($model->getTemplateName()).'.php');
	}
}
