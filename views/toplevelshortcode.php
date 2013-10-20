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
		if($model->mustEnqueueInitializationTemplate()) {
			include(RPBCHESSBOARD_ABSPATH.'templates/shortcode/initialization.php');
		}
		if($model->mustEnqueueLocalizationTemplate()) {
			include(RPBCHESSBOARD_ABSPATH.'templates/localization.php');
		}
		include(RPBCHESSBOARD_ABSPATH.'templates/shortcode/javascriptwarning.php');
		include(RPBCHESSBOARD_ABSPATH.'templates/shortcode/'.strtolower($model->getTemplateName()).'.php');
	}
}
