<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Register the plugin JavaScript scripts.
 *
 * This class is not constructible. Call the static method `register()`
 * to trigger the registration operations (must be called only once).
 */
abstract class RPBChessboardScripts
{
	public static function register()
	{
		$ext = self::getJSFileExtension();

		// Moment.js (http://momentjs.com/)
		wp_register_script('rpbchessboard-momentjs', RPBCHESSBOARD_URL . 'third-party-libs/moment-js/moment' . $ext, false, '2.8.1');
		$momentjs = self::localizeJavaScriptLib('rpbchessboard-momentjs', 'third-party-libs/moment-js/locales/%1$s.js', '2.8.1');

		// RPBChess
		wp_register_script('rpbchessboard-core', RPBCHESSBOARD_URL . 'js/rpbchess' . $ext, false, RPBCHESSBOARD_VERSION);
		wp_register_script('rpbchessboard-pgn' , RPBCHESSBOARD_URL . 'js/rpbchess-pgn' . $ext, array(
			'rpbchessboard-core'
		), RPBCHESSBOARD_VERSION);

		// Chessboard widget
		wp_register_script('rpbchessboard-chessboard', RPBCHESSBOARD_URL . 'js/uichess-chessboard' . $ext, array(
			'rpbchessboard-core',
			'jquery-ui-widget',
			'jquery-ui-selectable'
		), RPBCHESSBOARD_VERSION);

		// Chessgame widget
		wp_register_script('rpbchessboard-chessgame', RPBCHESSBOARD_URL . 'js/uichess-chessgame' . $ext, array(
			'rpbchessboard-core',
			'rpbchessboard-pgn',
			'rpbchessboard-chessboard',
			$momentjs,
			'jquery-ui-widget',
			'jquery-color',
			'jquery-ui-dialog',
			'jquery-ui-resizable'
		), RPBCHESSBOARD_VERSION);

		// Plugin specific
		wp_register_script('rpbchessboard-backend', RPBCHESSBOARD_URL . 'js/backend' . $ext, array(
			'rpbchessboard-chessboard',
			'jquery-ui-dialog',
			'jquery-ui-accordion',
			'jquery-ui-draggable',
			'jquery-ui-droppable'
		), RPBCHESSBOARD_VERSION);

		// Enqueue the scripts.
		wp_enqueue_script('rpbchessboard-chessboard');
		wp_enqueue_script('rpbchessboard-chessgame' );

		// Additional scripts for the backend.
		if(is_admin()) {
			wp_enqueue_script('jquery-ui-slider');
			wp_enqueue_script('jquery-ui-tabs'  );
			wp_enqueue_script('rpbchessboard-backend');
		}

		// Inlined scripts
		add_action(is_admin() ? 'admin_print_footer_scripts' : 'wp_print_footer_scripts', array(__CLASS__, 'callbackInlinedScripts'));

		// TinyMCE editor
		add_filter('mce_external_plugins', array(__CLASS__, 'callbackRegisterTinyMCEPlugin'));
		add_filter('mce_buttons', array(__CLASS__, 'callbackRegisterTinyMCEButtons'));

		// QuickTags editor
		add_action('admin_print_footer_scripts', array(__CLASS__, 'callbackRegisterQuickTagsButtons'));
	}


	public static function callbackInlinedScripts()
	{
		$model = RPBChessboardHelperLoader::loadTrait('Compatibility');
		include(RPBCHESSBOARD_ABSPATH . 'templates/localization.php');
	}


	public static function callbackRegisterTinyMCEPlugin($plugins)
	{
		$plugins['RPBChessboard'] = RPBCHESSBOARD_URL . 'js/tinymce' . self::getJSFileExtension();
		return $plugins;
	}

	public static function callbackRegisterTinyMCEButtons($buttons)
	{
		array_push($buttons, 'rpb-chessboard');
		return $buttons;
	}


	public static function callbackRegisterQuickTagsButtons()
	{
		$url = RPBCHESSBOARD_URL . 'js/quicktags' . self::getJSFileExtension();
		echo '<script type="text/javascript" src="' . $url . '"></script>';
	}


	/**
	 * Return the extension to use for the included JS files.
	 *
	 * @return string
	 */
	private static function getJSFileExtension()
	{
		return WP_DEBUG ? '.js' : '.min.js';
	}


	/**
	 * Determine the language code to use to configure a given JavaScript library, and enqueue the required file.
	 *
	 * @param string $handle Handle of the file to localize.
	 * @param string $relativeFilePathTemplate Where the localized files should be searched.
	 * @param string $version Version the library.
	 * @return string Handle of the localized file a suitable translation has been found, original handle otherwise.
	 */
	private static function localizeJavaScriptLib($handle, $relativeFilePathTemplate, $version)
	{
		foreach(self::getBlogLangCodes() as $langCode)
		{
			// Does the translation script file exist for the current language code?
			$relativeFilePath = sprintf($relativeFilePathTemplate, $langCode);
			if(!file_exists(RPBCHESSBOARD_ABSPATH . $relativeFilePath)) {
				continue;
			}

			// If it exists, register it, and return a handle pointing to the localization file.
			$localizedHandle = $handle . '-localized';
			wp_register_script($localizedHandle, RPBCHESSBOARD_URL . $relativeFilePath, array($handle), $version);
			return $localizedHandle;
		}

		// Otherwise, if no translation file exists, return the handle of the original library.
		return $handle;
	}


	/**
	 * Return an array of language codes that may be relevant for the blog.
	 *
	 * @return array
	 */
	private static function getBlogLangCodes()
	{
		if(!isset(self::$blogLangCodes)) {
			$mainLanguage = str_replace('_', '-', strtolower(get_locale()));
			self::$blogLangCodes = array($mainLanguage);

			if(preg_match('/([a-z]+)\\-([a-z]+)/', $mainLanguage, $m)) {
				self::$blogLangCodes[] = $m[1];
			}
		}
		return self::$blogLangCodes;
	}


	/**
	 * Blog language codes.
	 */
	private static $blogLangCodes;
}
