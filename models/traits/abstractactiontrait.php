<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');


/**
 * Base class for traits implementing the operations that should be executed to
 * answer to a HTML POST request.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractActionTrait extends RPBChessboardAbstractTrait
{
	/**
	 * Action entry-point called by the controller.
	 */
	public abstract function processRequest();
}
