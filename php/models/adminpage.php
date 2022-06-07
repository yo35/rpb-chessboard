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
 * Model used to populate the admin page.
 */
class RPBChessboardModelAdminPage {

	private $subPages;
	private $currentSubPageId;
	private $subPageModel;


	public function __construct() {
		$this->subPages         = array(
			'chess-diagram-settings' => (object) array(
				'type'  => 'default',
				'label' => __( 'Chess diagram block settings', 'rpb-chessboard' ),
				'icon'  => 'fen',
			),
			'chess-game-settings'    => (object) array(
				'type'  => 'regular',
				'label' => __( 'Chess game block settings', 'rpb-chessboard' ),
				'icon'  => 'pgn',
			),
			'compatibility-settings' => (object) array(
				'type'  => 'regular',
				'label' => __( 'Compatibility settings', 'rpb-chessboard' ),
			),
			'small-screens'          => (object) array(
				'type'  => 'regular',
				'label' => __( 'Small-screen devices', 'rpb-chessboard' ),
			),
			'theming'                => (object) array(
				'type'  => 'regular',
				'label' => __( 'Theming', 'rpb-chessboard' ),
			),
			'help'                   => (object) array(
				'type'  => 'https://rpb-chessboard.yo35.org/',
				'label' => __( 'Documentation', 'rpb-chessboard' ),
			),
			'about'                  => (object) array(
				'type'  => 'regular',
				'label' => __( 'About', 'rpb-chessboard' ),
			),
		);
		$this->currentSubPageId = $this->computeCurrentSubPage();
		$this->subPageModel     = self::isDefaultOrRegularType( $this->subPages[ $this->currentSubPageId ]->type ) ?
			RPBChessboardHelperLoader::loadModel( $this->computeSubPageModelName() ) : null;
	}


	private function computeCurrentSubPage() {
		$val       = isset( $_GET['subpage'] ) ? $_GET['subpage'] : '';
		$defaultId = null;
		foreach ( $this->subPages as $id => $data ) {
			if ( $id === $val && self::isDefaultOrRegularType( $data->type ) ) {
				return $id;
			}
			if ( 'default' === $data->type ) {
				$defaultId = $id;
			}
		}
		return $defaultId;
	}


	private function computeSubPageModelName() {
		return 'AdminSubPage/' . implode( array_map( 'ucfirst', explode( '-', $this->currentSubPageId ) ) );
	}


	private static function isDefaultOrRegularType( $type ) {
		return 'default' === $type || 'regular' === $type;
	}


	/**
	 * IDs of the sub-pages.
	 */
	public function getSubPages() {
		return array_keys( $this->subPages );
	}


	/**
	 * ID of the current sub-page.
	 */
	public function getCurrentSubPage() {
		return $this->currentSubPageId;
	}


	/**
	 * Whether the given ID corresponds to an external sub-page.
	 */
	public function isExternalSubPage( $subPageId ) {
		return ! self::isDefaultOrRegularType( $this->subPages[ $subPageId ]->type );
	}


	/**
	 * URL to the sub-page corresponding to the given ID.
	 */
	public function getSubPageLink( $subPageId ) {
		switch ( $this->subPages[ $subPageId ]->type ) {
			case 'default':
				return admin_url( 'options-general.php?page=rpbchessboard' );
			case 'regular':
				return admin_url( 'options-general.php?page=rpbchessboard&subpage=' . $subPageId );
			default:
				return $this->subPages[ $subPageId ]->type;
		}
	}


	/**
	 * Label of the given sub-page in the menu.
	 */
	public function getSubPageLabel( $subPageId ) {
		return $this->subPages[ $subPageId ]->label;
	}


	/**
	 * Whether the given given sub-page has a menu icon or not.
	 */
	public function hasSubPageIcon( $subPageId ) {
		return isset( $this->subPages[ $subPageId ]->icon );
	}


	/**
	 * Icon of the given sub-page in the menu, if any.
	 */
	public function getSubPageIcon( $subPageId ) {
		return $this->subPages[ $subPageId ]->icon;
	}


	/**
	 * Unresolved method calls are delegated to the sub-page model.
	 */
	public function __call( $method, $args ) {
		return call_user_func_array( array( $this->subPageModel, $method ), $args );
	}

}
