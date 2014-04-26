<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


require_once(RPBCHESSBOARD_ABSPATH.'models/traits/abstracttrait.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/validation.php');


/**
 * Process the request resulting from a click in the "Options" form in the backend.
 */
class RPBChessboardTraitPostOptions extends RPBChessboardAbstractTrait
{
	private $squareSize;
	private $showCoordinates;
	private $fenCompatibilityMode;
	private $pgnCompatibilityMode;


	/**
	 * Constructor.
	 */
	public function __construct()
	{
		// Load the square-size parameter.
		if(isset($_POST['squareSize'])) {
			$this->squareSize = RPBChessboardHelperValidation::validateSquareSize($_POST['squareSize']);
		}

		// Load the boolean parameters.
		$this->showCoordinates      = $this->loadBooleanParameter('showCoordinates'     );
		$this->fenCompatibilityMode = $this->loadBooleanParameter('fenCompatibilityMode');
		$this->pgnCompatibilityMode = $this->loadBooleanParameter('pgnCompatibilityMode');
	}


	/**
	 * Load and validate a boolean post parameter with the given name.
	 *
	 * @param string $paramName Name of the parameter to return.
	 * @return boolean May be null if the corresponding POST field is undefined or invalid.
	 */
	private function loadBooleanParameter($paramName)
	{
		return isset($_POST[$paramName]) ? RPBChessboardHelperValidation::validateBooleanFromInt($_POST[$paramName]) : null;
	}


	/**
	 * Update the plugin general options.
	 *
	 * @return string
	 */
	public function updateOptions()
	{
		// Set the square size parameter.
		if(isset($this->squareSize)) {
			update_option('rpbchessboard_squareSize', $this->squareSize);
		}

		// Set the boolean parameters.
		$this->updateBooleanParameter('showCoordinates'     , $this->showCoordinates     );
		$this->updateBooleanParameter('fenCompatibilityMode', $this->fenCompatibilityMode);
		$this->updateBooleanParameter('pgnCompatibilityMode', $this->pgnCompatibilityMode);

		// Notify the user.
		return __('Settings saved.', 'rpbchessboard');
	}


	/**
	 * Update a global boolean parameter.
	 *
	 * @param string $key Name of the parameter in the dedicated WP table.
	 * @param boolean $value New value (if null, nothing happens).
	 */
	private function updateBooleanParameter($key, $value)
	{
		if(isset($value)) {
			update_option('rpbchessboard_' . $key, $value ? 1 : 0);
		}
	}
}
