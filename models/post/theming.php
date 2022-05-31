<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php';


/**
 * Abstract class for theming request processing.
 */
abstract class RPBChessboardModelPostTheming extends RPBChessboardAbstractModel {

	private $customSetCodes;


	public function add() {
		$setCode = $this->getNewSetCode();

		// Update attributes and list of custom set-codes.
		if ( ! ( $this->processLabel( $setCode ) && $this->processAttributes( $setCode ) ) ) {
			return null;
		}
		$this->updateCustomSetCodes( array_merge( $this->getCustomSetCodes(), array( $setCode ) ) );

		return $this->getCreationSuccessMessage();
	}


	public function edit() {
		$setCode = $this->getSetCode();
		if ( ! isset( $setCode ) ) {
			return null;
		}

		$this->processLabel( $setCode );
		$this->processAttributes( $setCode );

		return $this->getEditionSuccessMessage();
	}


	public function delete() {
		$setCode = $this->getSetCode();
		if ( ! isset( $setCode ) ) {
			return null;
		}

		// Remove the set-code from the database.
		$this->updateCustomSetCodes( array_diff( $this->getCustomSetCodes(), array( $setCode ) ) );

		// Reset default set-code if it corresponds to the set-code being deleted.
		if ( $setCode === $this->getDefaultSetCode() ) {
			delete_option( 'rpbchessboard_' . $this->getManagedSetCode() );
		}

		// Cleanup the database.
		delete_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . '_label_' . $setCode );
		delete_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . '_attributes_' . $setCode );

		return $this->getDeletionSuccessMessage();
	}


	private function processLabel( $setCode ) {
		if ( isset( $_POST['label'] ) ) {
			$value = RPBChessboardHelperValidation::validateString( $_POST['label'] );
			if ( isset( $value ) ) {
				update_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . '_label_' . $setCode, $value );
				return true;
			}
		}
		return false;
	}


	/**
	 * Set the attributes of the given set-code according to the current POST data.
	 *
	 * @return `true` if the attributes are successfully set, `false` otherwise.
	 */
	abstract protected function processAttributes( $setCode);


	private function updateCustomSetCodes( $setCodes ) {
		update_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . 's', implode( '|', $setCodes ) );
	}


	/**
	 * Check whether the given set-code represents an existing custom theming set or not.
	 */
	private function isCustomSetCode( $setCode ) {
		return in_array( $setCode, $this->getCustomSetCodes(), true );
	}


	/**
	 * Retrieve the list of existing custom set-codes.
	 */
	private function getCustomSetCodes() {
		if ( ! isset( $this->customSetCodes ) ) {
			$result               = RPBChessboardHelperValidation::validateSetCodeList( get_option( 'rpbchessboard_custom_' . $this->getManagedSetCode() . 's' ) );
			$this->customSetCodes = isset( $result ) ? $result : array();
		}
		return $this->customSetCodes;
	}


	/**
	 * Retrieve the set-code used by default, if any.
	 */
	private function getDefaultSetCode() {
		return RPBChessboardHelperValidation::validateSetCode( get_option( 'rpbchessboard_' . $this->getManagedSetCode() ) );
	}


	/**
	 * Retrieve the set-code concerned by this operation and make sure that it corresponds to a custom theming set.
	 */
	private function getSetCode() {
		$managedSetCode = $this->getManagedSetCode();
		$setCode        = isset( $_POST[ $managedSetCode ] ) ? RPBChessboardHelperValidation::validateSetCode( $_POST[ $managedSetCode ] ) : null;
		if ( isset( $setCode ) && ! $this->isCustomSetCode( $setCode ) ) {
			return null;
		}
		return $setCode;
	}


	/**
	 * Retrieve (and sanitize) the set-code to use to create the new theming set.
	 */
	private function getNewSetCode() {
		$managedSetCode = $this->getManagedSetCode();
		$setCode        = isset( $_POST[ $managedSetCode ] ) ? $_POST[ $managedSetCode ] : '';
		if ( trim( $setCode ) === '' && isset( $_POST['label'] ) ) {
			$setCode = $_POST['label'];
		}

		// Convert all upper case to lower case, spaces to '-', and remove the rest.
		$setCode = strtolower( $setCode );
		$setCode = preg_replace( '/\s/', '-', $setCode );
		$setCode = preg_replace( '/[^a-z0-9\-]/', '', $setCode );

		// Concat consecutive '-', and trim the result.
		$setCode = preg_replace( '/-+/', '-', $setCode );
		$setCode = trim( $setCode, '-' );

		// Ensure that the result is valid and not already used for another set-code.
		$counter = 1;
		$base    = '' === $setCode ? $managedSetCode : $setCode;
		$setCode = '' === $setCode ? $managedSetCode . '-1' : $setCode;
		while ( $this->isCustomSetCode( $setCode ) || $this->isBuiltinSetCode( $setCode ) ) {
			$setCode = $base . '-' . ( $counter++ );
		}
		return $setCode;
	}


	/**
	 * Either `'colorset'` or `'pieceset'`.
	 */
	abstract protected function getManagedSetCode();


	/**
	 * Whether the given set-code corresponds to a builtin colorset or pieceset.
	 */
	abstract protected function isBuiltinSetCode( $setCode);


	/**
	 * Human-readable message for set-code creation success.
	 */
	abstract protected function getCreationSuccessMessage();


	/**
	 * Human-readable message for set-code edition success.
	 */
	abstract protected function getEditionSuccessMessage();


	/**
	 * Human-readable message for set-code deletion success.
	 */
	abstract protected function getDeletionSuccessMessage();
}
