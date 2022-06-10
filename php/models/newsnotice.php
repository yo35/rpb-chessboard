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
 * Model describing the news notice for RPB Chessboard, if any.
 */
class RPBChessboardModelNewsNotice {

	/**
	 * Unique ID (string) or `false`.
	 *
	 * WARNING: if defined, there must be a file named `/php/templates/newsnoticecontent.php` to define the content of the notice.
	 */
	private static $NOTICE_KEY = false;

	/**
	 * The notice is displayed until the given date and time, defined as `yyyy-mm-dd hh:MM` (e.g. 2022-06-09 17:00 for June 9th, 2022, at 5pm).
	 * Set to `9999-99-99 99:99` for unlimited display.
	 *
	 * WARNING: this is GMT time.
	 */
	private static $NOTICE_EXPIRATION_DATE = '9999-99-99 99:99';


	/**
	 * Whether the news notice should be displayed or not.
	 */
	public function hasNoticeToDisplay() {

		// News notice is only for the site admins. Don't bother regular users!
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		// ... and only on the general plugin page, the update page, and the RPB Chessboard's admin page. Don't pollute all the the admin!
		$currentScreenId = get_current_screen()->id;
		if ( 'plugins' !== $currentScreenId && 'update-core' !== $currentScreenId && 'settings_page_rpbchessboard' !== $currentScreenId ) {
			return false;
		}

		// Don't show the notice if there is no notice (!) or if it has expired.
		if ( ! self::$NOTICE_KEY || self::$NOTICE_EXPIRATION_DATE <= self::getCurrentDateTime() ) {
			return false;
		}

		// Don't show the notice if the user has already dismissed it.
		if ( $this->isDismissible() ) {
			$dismissedKey = get_option( self::getDismissOptionKey(), false );
			if ( $dismissedKey === self::$NOTICE_KEY ) {
				return false;
			}
		}

		return true;
	}


	/**
	 * Whether the notice can be dismissed or not -> this is always the case, except on RPB Chessboard's about page.
	 */
	public function isDismissible() {
		$currentScreenId = get_current_screen()->id;
		return ! ( 'settings_page_rpbchessboard' === $currentScreenId && isset( $_GET['subpage'] ) && 'about' === $_GET['subpage'] );
	}


	/**
	 * AJAX request entry point.
	 */
	public function handleDismissRequest() {
		if ( ! current_user_can( 'manage_options' ) || ! self::$NOTICE_KEY ) {
			return;
		}

		// Flag the notice corresponding to the current key as being dismissed for the current user.
		update_option( self::getDismissOptionKey(), self::$NOTICE_KEY );
	}


	private static function getDismissOptionKey() {
		return 'rpbchessboard_dismissNewsNotice_' . get_current_user_id();
	}


	private static function getCurrentDateTime() {
		return current_time( 'Y-m-d H:i', true );
	}

}
