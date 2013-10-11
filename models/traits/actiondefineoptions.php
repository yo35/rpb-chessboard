<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstractactiontrait.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/validation.php');


/**
 * Process the request resulting from a click in the "Options" form in the backend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardTraitActionDefineOptions extends RPBChessboardAbstractActionTrait
{
	public function processRequest()
	{
		// Set the square size parameter
		$value = $this->getPostSquareSize();
		if(!is_null($value)) {
			update_option('rpbchessboard_squareSize', $value);
		}

		// Set the show-coordinates parameter
		$value = $this->getPostShowCoordinates();
		if(!is_null($value)) {
			update_option('rpbchessboard_showCoordinates', $value ? 1 : 0);
		}
	}


	/**
	 * New default square size for the chessboard widgets.
	 *
	 * @return int May be null if the corresponding POST field is undefined or invalid.
	 */
	public function getPostSquareSize()
	{
		if(array_key_exists('squareSize', $_POST)) {
			return RPBChessboardHelperValidation::validateSquareSize($_POST['squareSize']);
		}
		else {
			return null;
		}
	}


	/**
	 * New default show-coordinates parameter for the chessboard widgets.
	 *
	 * @return boolean May be null if the corresponding POST field is undefined or invalid.
	 */
	public function getPostShowCoordinates()
	{
	if(array_key_exists('showCoordinates', $_POST)) {
			return RPBChessboardHelperValidation::validateShowCoordinates($_POST['showCoordinates']);
		}
		else {
			return null;
		}
	}
}
