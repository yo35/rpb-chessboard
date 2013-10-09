<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractadminmodel.php');

/**
 * Model associated to the 'Options' page in the backend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelOptions extends RPBChessboardAbstractAdminModel
{
	public function __construct()
	{
		parent::__construct();
		$this->loadTrait('ChessWidgetDefaultOptions');
	}

	public function getTitle()
	{
		return __('Options', 'rpbchessboard');
	}
}
