<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2016  Yoann Le Montagner <yo35 -at- melix.net>       *
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
 * Register the AJAX request processing callbacks, the custom MIME types, etc...
 */
abstract class RPBChessboardMisc {

	public static function register() {
		add_action('wp_ajax_rpbchessboard_format_pieceset_sprite', array(__CLASS__, 'callbackFormatPiecesetSprite'));
		add_action('delete_attachment', array(__CLASS__, 'callbackDeleteAttachment'));

		add_filter('upload_mimes', array(__CLASS__, 'callbackFilterMimeTypes'));
	}


	public static function callbackFormatPiecesetSprite() {
		check_ajax_referer('rpbchessboard_format_pieceset_sprite');
		if(!current_user_can('manage_options')) {
			wp_die();
		}

		$ajaxModel = RPBChessboardHelperLoader::loadModel('Ajax/FormatPiecesetSprite');
		$ajaxModel->run();
	}


	public static function callbackDeleteAttachment($attachmentId) {
		$model = RPBChessboardHelperLoader::loadModel('Ajax/RemovePiecesetSprite');
		$model->run($attachmentId);
	}


	public static function callbackFilterMimeTypes($mimeTypes) {
		$mimeTypes['pgn'] = 'application/x-chess-pgn';
		return $mimeTypes;
	}
}
