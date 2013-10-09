<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractmodel.php');

/**
 * Base class for the models used in the backend of the RPBChessboard plugin.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractAdminModel extends RPBChessboardAbstractModel
{
	/**
	 * Title of the page in the backend.
	 */
	public abstract function getTitle();


	/**
	 * Return the name of the template to use to display the page in the backend.
	 * By default, the template to use is the one with the same name than the model.
	 */
	public function getTemplateName()
	{
		return $this->getName();
	}
}
