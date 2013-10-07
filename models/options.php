<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractmodel.php');

/**
 * Model associated to the 'Options' page in the backend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelOptions extends RPBChessboardAbstractModel
{
	public function __construct()
	{
		parent::__construct(array('ChessWidgetOptionsGet'));
	}

	public function getTitle()
	{
		return __('Options', 'rpbchessboard');
	}
}
