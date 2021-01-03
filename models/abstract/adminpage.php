<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Base class for the models used to render the plugin administration pages.
 */
abstract class RPBChessboardAbstractModelAdminPage extends RPBChessboardAbstractModel {

	private $adminPageName;
	private $title;
	private $subPages;
	private $defaultSubPageName;
	private $selectedSubPageName;
	private static $postMessage;


	/**
	 * Return the name of the template associated to the page.
	 */
	public function getPageTemplateName() {
		return 'AdminPage/' . $this->getAdminPageName();
	}


	/**
	 * Return the name of the template associated to the current sub-page.
	 */
	public function getSubPageTemplateName() {
		return isset( $this->subPages ) ? 'AdminPage/' . $this->getAdminPageName() . '/' . $this->getSelectedSubPageName() : '';
	}


	/**
	 * Name of the administration page.
	 *
	 * @return string
	 */
	public function getAdminPageName() {
		if ( ! isset( $this->adminPageName ) ) {
			$this->adminPageName = preg_match( '/^AdminPage(.*)$/', $this->getModelName(), $m ) ? $m[1] : '';
		}
		return $this->adminPageName;
	}


	/**
	 * Human-readable title of the page.
	 *
	 * @return string
	 */
	public function getTitle() {
		if ( ! isset( $this->title ) ) {
			$this->title = html_entity_decode( get_admin_page_title(), ENT_QUOTES );
		}
		return $this->title;
	}


	/**
	 * Whether the page has sub-pages or not.
	 *
	 * @return boolean
	 */
	public function hasSubPages() {
		return isset( $this->subPages );
	}


	/**
	 * Return the name of the selected sub-page.
	 *
	 * @return string An empty string is returned if no sub-page is defined.
	 */
	public function getSelectedSubPageName() {
		$this->initializeSelectedSubPageInfo();
		return $this->selectedSubPageName;
	}


	/**
	 * List of the sub-pages.
	 *
	 * Each entry of the returned array has the following fields:
	 *  - `name` (string): name of the sub-page (also the template name).
	 *  - `label` (string): human-readable label for the sub-page button.
	 *  - `link` (string): HTTP link to activate the sub-page.
	 *  - `selected` (boolean): whether the given sub-page is currently selected or not.
	 *
	 * @return array
	 */
	public function getSubPages() {
		$this->initializeSelectedSubPageInfo();
		return isset( $this->subPages ) ? $this->subPages : array();
	}


	/**
	 * Return the sub-page corresponding to the given name.
	 *
	 * @param string $name
	 * @return array Null is returned if the corresponding sub-page does not exist.
	 */
	public function getSubPage( $name ) {
		$this->initializeSelectedSubPageInfo();
		if ( isset( $this->subPages ) ) {
			foreach ( $this->subPages as $subPage ) {
				if ( $name === $subPage->name ) {
					return $subPage;
				}
			}
		}
		return null;
	}


	/**
	 * Initialize the name of the selected sub-page and the `selected` flags in
	 * the sub-page list.
	 */
	private function initializeSelectedSubPageInfo() {
		if ( isset( $this->selectedSubPageName ) ) {
			return;
		}

		// Regular case => use the GET parameter `subpage` or the default subpage name
		// to determine the currently selected sub-page.
		if ( isset( $this->subPages ) ) {
			$selectedSubPageFromGET    = isset( $_GET['subpage'] ) ? $this->validateSubPageName( $_GET['subpage'] ) : null;
			$this->selectedSubPageName = isset( $selectedSubPageFromGET ) ? $selectedSubPageFromGET : $this->defaultSubPageName;

			// Update the `selected` flags in the sub-page list.
			foreach ( $this->subPages as $subPage ) {
				$subPage->selected = ( $this->selectedSubPageName === $subPage->name );
			}
		} else { // Fallback case if no sub-page exists.
			$this->selectedSubPageName = '';
		}
	}


	/**
	 * Ensure that the given input is a valid sub-page name.
	 *
	 * @param string $name
	 * @return string `null` if the input is not a valid sub-page name.
	 */
	private function validateSubPageName( $name ) {
		if ( isset( $this->subPages ) ) {
			$name = strtolower( $name );
			foreach ( $this->subPages as $subPage ) {
				if ( strtolower( $subPage->name ) === $name ) {
					return $subPage->name;
				}
			}
		}
		return null;
	}


	/**
	 * Register a new sub-page.
	 *
	 * @param string  $name
	 * @param string  $label
	 * @param boolean $default=false
	 */
	protected function addSubPage( $name, $label, $default = false ) {

		// Initialize the sub-page array.
		if ( ! isset( $this->subPages ) ) {
			$this->subPages = array();
		}

		// Push the new-page at the end of the sub-page list.
		$this->subPages[] = (object) array(
			'name'  => $name,
			'label' => $label,
			'link'  => self::makeSubPageLink( $name ),
		);

		// Mark the sub-page as the default one if it explicitly requested
		// or if it is the first created sub-page.
		if ( ! isset( $this->defaultSubPageName ) || $default ) {
			$this->defaultSubPageName = $name;
		}

		// The name of the selected page needs to be updated now.
		$this->selectedSubPageName = null;
	}


	/**
	 * Link to the sub-page named `$subPageName` in the current page.
	 *
	 * @param string $subPageName
	 * @return string
	 */
	private static function makeSubPageLink( $subPageName ) {
		global $pagenow;
		return admin_url( $pagenow ) . '?page=' . rawurlencode( $_GET['page'] ) . '&subpage=' . rawurlencode( strtolower( $subPageName ) );
	}


	/**
	 * Whether a POST message has been defined or not.
	 *
	 * @return boolean
	 */
	public function hasPostMessage() {
		return isset( self::$postMessage );
	}


	/**
	 * Human-readable message informing the user about the result of the POST action.
	 * or an empty string if no action were performed.
	 *
	 * @return string
	 */
	public function getPostMessage() {
		return isset( self::$postMessage ) ? self::$postMessage : '';
	}


	/**
	 * Define the POST action message.
	 *
	 * @param string $message
	 */
	public static function initializePostMessage( $message ) {
		self::$postMessage = $message;
	}
}
