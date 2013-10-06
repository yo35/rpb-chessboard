<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/admin/abstractmodel.php');

/**
 * Model associated to the 'Options' page in the backend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelOptions extends RPBChessboardAbstractModel
{
	public function getTitle()
	{
		return __('Options', 'rpbchessboard');
	}
}
