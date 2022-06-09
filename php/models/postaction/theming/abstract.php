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


/**
 * Base class for the models in charge of processing the theming add/edit/delete requests.
 */
abstract class RPBChessboardAbstractModelPostActionTheming {


	final public function add() {
		$slug = $this->computeNewSlug();

		// Load and validate the label and attributes.
		$label      = self::loadLabel();
		$attributes = $this->loadAttributes();
		if ( ! isset( $label ) || ! isset( $attributes ) ) {
			return (object) array(
				'message'     => $this->getCreationFailureMessage(),
				'messageType' => 'error',
			);
		}

		// Update the database.
		$this->updateLabelAndAttributes( $slug, $label, $attributes );
		$this->updateCustomSlugs( array_merge( $this->getCustomSlugs(), array( $slug ) ) );
		return (object) array(
			'message'     => $this->getCreationSuccessMessage(),
			'messageType' => 'success',
		);
	}


	final public function edit() {
		$slug = $this->loadSlug();
		if ( ! isset( $slug ) ) {
			return (object) array(
				'message'     => $this->getEditionFailureMessage(),
				'messageType' => 'error',
			);
		}

		// Load and validate the label and attributes.
		$label      = self::loadLabel();
		$attributes = $this->loadAttributes();
		if ( ! isset( $label ) || ! isset( $attributes ) ) {
			return (object) array(
				'message'     => $this->getEditionFailureMessage(),
				'messageType' => 'error',
			);
		}

		// Update the database.
		$this->updateLabelAndAttributes( $slug, $label, $attributes );
		return (object) array(
			'message'     => $this->getEditionSuccessMessage(),
			'messageType' => 'success',
		);
	}


	final public function delete() {
		$slug = $this->loadSlug();
		if ( ! isset( $slug ) ) {
			return (object) array(
				'message'     => $this->getDeletionFailureMessage(),
				'messageType' => 'error',
			);
		}

		// Update the database
		$this->resetDefaultSettingIfNecessary( 'sdo', $slug );
		$this->resetDefaultSettingIfNecessary( 'nbo', $slug );
		$this->resetDefaultSettingIfNecessary( 'ido', $slug );
		$this->updateCustomSlugs( array_diff( $this->getCustomSlugs(), array( $slug ) ) );
		$this->deleteLabelAndAttributes( $slug );
		return (object) array(
			'message'     => $this->getDeletionSuccessMessage(),
			'messageType' => 'success',
		);
	}


	private static function loadLabel() {
		if ( isset( $_POST['label'] ) ) {
			$value = RPBChessboardHelperValidation::validateString( $_POST['label'] );
			if ( isset( $value ) ) {
				return $value;
			}
		}
		return null;
	}


	/**
	 * Load the attributes from the current POST data.
	 *
	 * @return Flat string representation of the attributes if they are defined in the POST data and valid, `null` otherwise.
	 */
	abstract protected function loadAttributes();


	private function updateLabelAndAttributes( $slug, $label, $attributes ) {
		$dataType = $this->getDataType();
		update_option( 'rpbchessboard_custom_' . $dataType . '_label_' . $slug, $label );
		update_option( 'rpbchessboard_custom_' . $dataType . '_attributes_' . $slug, $attributes );
	}


	private function deleteLabelAndAttributes( $slug ) {
		$dataType = $this->getDataType();
		delete_option( 'rpbchessboard_custom_' . $dataType . '_label_' . $slug );
		delete_option( 'rpbchessboard_custom_' . $dataType . '_attributes_' . $slug );
	}


	private function updateCustomSlugs( $slugs ) {
		update_option( 'rpbchessboard_custom_' . $this->getDataType() . 's', implode( '|', $slugs ) );
	}


	/**
	 * Retrieve the slug concerned by this operation and make sure that it corresponds to a custom colorset or pieceset.
	 */
	private function loadSlug() {
		$dataType = $this->getDataType();
		$slug     = isset( $_POST[ $dataType ] ) ? RPBChessboardHelperValidation::validateSetCode( $_POST[ $dataType ] ) : null;
		return isset( $slug ) && in_array( $slug, $this->getCustomSlugs(), true ) ? $slug : null;
	}


	/**
	 * Retrieve (and sanitize) the slug to use to create the new theming set.
	 */
	private function computeNewSlug() {
		$dataType = $this->getDataType();
		$slug     = isset( $_POST[ $dataType ] ) ? $_POST[ $dataType ] : '';
		if ( trim( $slug ) === '' && isset( $_POST['label'] ) ) {
			$slug = $_POST['label'];
		}

		// Convert all upper case to lower case, spaces to '-', and remove the rest.
		$slug = strtolower( $slug );
		$slug = preg_replace( '/\s/', '-', $slug );
		$slug = preg_replace( '/[^a-z0-9\-]/', '', $slug );

		// Concat consecutive '-', and trim the result.
		$slug = preg_replace( '/-+/', '-', $slug );
		$slug = trim( $slug, '-' );

		// Ensure that the result is valid and not already used for another slug.
		$counter = 1;
		$base    = '' === $slug ? $dataType : $slug;
		$slug    = $base;
		while ( $this->isAlreadyUsedSlug( $slug ) ) {
			$slug = $base . '-' . ( $counter++ );
		}
		return $slug;
	}


	private function resetDefaultSettingIfNecessary( $key, $slug ) {
		$dataType  = $this->getDataType();
		$fullKey   = 'rpbchessboard_' . $key . ucfirst( $dataType );
		$legacyKey = 'rpbchessboard_' . $dataType; // FIXME Deprecated parameter (since 7.2)

		if ( get_option( $fullKey ) === $slug ) {
			delete_option( $legacyKey );
			delete_option( $fullKey );
		} elseif ( get_option( $legacyKey ) === $slug ) {
			delete_option( $legacyKey );
		}
	}


	/**
	 * Either `'colorset'` or `'pieceset'`.
	 */
	abstract protected function getDataType();


	/**
	 * Whether the given slug corresponds to an already used colorset or pieceset.
	 */
	abstract protected function isAlreadyUsedSlug( $slug );


	/**
	 * Pre-existing custom colorsets or piecesets.
	 */
	abstract protected function getCustomSlugs();


	abstract protected function getCreationSuccessMessage();

	abstract protected function getCreationFailureMessage();

	abstract protected function getEditionSuccessMessage();

	abstract protected function getEditionFailureMessage();

	abstract protected function getDeletionSuccessMessage();

	abstract protected function getDeletionFailureMessage();
}
