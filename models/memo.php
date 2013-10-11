<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractadminmodel.php');


/**
 * Model associated to the 'Memo' page in the backend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelMemo extends RPBChessboardAbstractAdminModel
{
	public function getTitle()
	{
		return __('Memo', 'rpbchessboard');
	}
}
