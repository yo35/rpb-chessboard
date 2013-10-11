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
		$this->loadTrait('ChessWidgetLimits');
	}

	public function getTitle()
	{
		return __('Options', 'rpbchessboard');
	}

	/**
	 * URL to which the the request for modifying the options of the plugin will be dispatched.
	 *
	 * @return string
	 */
	public function getFormActionURL()
	{
		return site_url().'/wp-admin/admin.php?page=rpbchessboard-options';
	}

	/**
	 * Action code corresponding to the request for modifying the options of the plugin.
	 *
	 * @return string
	 */
	public function getFormAction()
	{
		return 'DefineOptions';
	}
}
