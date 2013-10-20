<?php

require_once(RPBCHESSBOARD_ABSPATH.'views/abstractview.php');


/**
 * View for the short-code [pgndiagram].
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardViewPgnDiagram extends RPBChessboardAbstractView
{
	public function display()
	{
		$model = $this->getModel();
		include(RPBCHESSBOARD_ABSPATH.'templates/shortcode/pgndiagram.php');
	}
}
