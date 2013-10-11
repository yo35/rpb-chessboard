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
	 *
	 * @return string
	 */
	public abstract function getTitle();


	/**
	 * Return the name of the template to use to display the page in the backend.
	 * By default, the template to use is the one with the same name than the model.
	 *
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->getName();
	}


	/**
	 * Return the name of the action that should be performed by the server.
	 * The action is initiated by the user when clicking on a 'submit' button in
	 * an HTML form with its method attribute set to POST.
	 *
	 * This function may return null if no action was posted.
	 *
	 * @return string
	 */
	public function getPostAction()
	{
		return array_key_exists('action', $_POST) ? $_POST['action'] : null;
	}
}
