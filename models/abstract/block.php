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
 * Base class for the models used to render the plugin blocks (and legacy shortcodes).
 */
abstract class RPBChessboardAbstractModelBlock extends RPBChessboardAbstractModel {

	private $attributes;
	private $content;
	private $contentFiltered = false;
	private $uniqueID;


	/**
	 * Constructor.
	 *
	 * @param array  $atts Attributes passed with the block/shortcode.
	 * @param string $content Block/shortcode content.
	 */
	public function __construct( $attributes, $content ) {
		parent::__construct();
		$this->attributes = isset( $attributes ) && is_array( $attributes ) ? $attributes : array();
		$this->content    = isset( $content ) && is_string( $content ) ? $content : '';
	}


	/**
	 * Return the attributes passed to the block.
	 *
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}


	/**
	 * Return the block content.
	 *
	 * @return string
	 */
	public function getContent() {
		if ( ! $this->contentFiltered ) {
			$this->content         = $this->filterShortcodeContent( $this->content );
			$this->contentFiltered = true;
		}
		return $this->content;
	}


	/**
	 * Pre-process the shortcode enclosed content, for instance to get rid of the
	 * auto-format HTML tags introduced by the WordPress engine. By default, this
	 * function returns the raw content "as-is". The function should be re-implemented
	 * in the derived models.
	 *
	 * @param string $content Raw content.
	 * @return string Filtered content.
	 */
	protected function filterShortcodeContent( $content ) {
		return $content;
	}


	/**
	 * Return a string that may be used as a unique DOM node ID.
	 *
	 * @return string
	 */
	public function getUniqueID() {
		if ( ! isset( $this->uniqueID ) ) {
			$this->uniqueID = self::makeUniqueID();
		}
		return $this->uniqueID;
	}


	/**
	 * Allocate a new HTML node ID.
	 *
	 * @return string
	 */
	private static function makeUniqueID() {
		if ( ! isset( self::$idPrefix ) ) {
			self::$idPrefix = 'rpbchessboard-' . uniqid() . '-';
		}
		++self::$idCounter;
		return self::$idPrefix . self::$idCounter;
	}


	/**
	 * Global ID counter.
	 */
	private static $idCounter = 0;


	/**
	 * Prefix for the dynamically allocated DOM IDs.
	 */
	private static $idPrefix;
}
