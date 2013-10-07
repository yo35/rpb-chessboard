<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractmodel.php');

/**
 * Model associated to the 'Memo' page in the backend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelMemo extends RPBChessboardAbstractModel
{
	public function getTitle()
	{
		return __('Memo', 'rpbchessboard');
	}
}
