<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2024  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Helper functions for dynamic class loading.
 */
abstract class RPBChessboardHelperLoader {


	/**
	 * Load the model corresponding to the given model name.
	 *
	 * @param string $modelName Name of the model.
	 * @param mixed ... Arguments to pass to the model (optional).
	 * @return object New instance of the model.
	 */
	public static function loadModel( $modelName ) {
		$fileName  = strtolower( $modelName );
		$className = 'RPBChessboardModel' . str_replace( '/', '', $modelName );
		require_once RPBCHESSBOARD_ABSPATH . 'php/models/' . $fileName . '.php';
		if ( func_num_args() === 1 ) {
			return new $className();
		} else {
			$args  = func_get_args();
			$clazz = new ReflectionClass( $className );
			return $clazz->newInstanceArgs( array_slice( $args, 1 ) );
		}
	}


	/**
	 * Load the controller corresponding to the given name.
	 *
	 * @param string $controllerName Name of the controller.
	 * @return object New instance of the controller.
	 */
	public static function loadController( $controllerName ) {
		$fileName  = strtolower( $controllerName );
		$className = 'RPBChessboard' . $controllerName;
		require_once RPBCHESSBOARD_ABSPATH . 'php/' . $fileName . '.php';
		return new $className();
	}


	/**
	 * Print the given template to the current output.
	 *
	 * @param string $templateName
	 * @param object $model
	 * @param array  $args
	 */
	public static function printTemplate( $templateName, $model, $args = null ) {

		if ( isset( $args ) ) {
			foreach ( $args as $key_ => $value_ ) {
				if ( 'model' === $key_ || 'templateName' === $key_ || 'fileName' === $key_ || 'args' === $key_ ) {
					continue;
				}
				$$key_ = $value_;
			}
		}

		$fileName = RPBCHESSBOARD_ABSPATH . 'php/templates/' . str_replace( '-', '', $templateName );
		include $fileName . ( is_dir( $fileName ) ? '/main.php' : '.php' );
	}


	/**
	 * Print the given template to a string.
	 *
	 * @param string $templateName
	 * @param object $model
	 * @param array  $args
	 * @return string
	 */
	public static function printTemplateOffScreen( $templateName, $model, $args = null ) {
		ob_start();
		self::printTemplate( $templateName, $model, $args );
		return ob_get_clean();
	}
}
