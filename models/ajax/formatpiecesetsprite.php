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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php');
require_once(RPBCHESSBOARD_ABSPATH . 'helpers/validation.php');


/**
 * Process a pieceset formatting request.
 */
class RPBChessboardModelAjaxFormatPiecesetSprite extends RPBChessboardAbstractModel {

	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel('Common/DefaultOptionsEx');
	}


	public function run() {

		$attachment = self::getAttachment();
		if(!isset($attachment)) {
			wp_die();
		}

		if($attachment->width !== $attachment->height) {
			wp_send_json_error(array('message' => __('Images used to create piecesets must be square images (identical height and width).', 'rpbchessboard')));
		}
		if($attachment->type !== IMAGETYPE_PNG) {
			wp_send_json_error(array('message' => __('Only PNG images can be used to create piecesets.', 'rpbchessboard')));
		}

		$outputPath = $this->computeCustomPiecesetFormattedDataPath($attachment->path);
		if(!file_exists($outputPath)) {
			if(!$this->generateSprite($outputPath, $attachment->path, $attachment->width, $attachment->height)) {
				wp_send_json_error(array('message' => __('Error while processing the selected image.', 'rpbchessboard')));
			}
		}

		$formattedDataURL = $this->computeCustomPiecesetFormattedDataPath($attachment->url);
		wp_send_json_success(array('attachmentId' => $attachment->id, 'rawDataURL' => $attachment->url, 'formattedDataURL' => $formattedDataURL));
	}


	private function generateSprite($destPath, $srcPath, $srcWidth, $srcHeight) {

		// Load the input image.
		$srcImage = imagecreatefrompng($srcPath);
		if(!$srcImage) {
			return false;
		}

		// Compute sizes.
		$sizeMin = $this->getMinimumSquareSize();
		$sizeMax = $this->getMaximumSquareSize();
		$sizeMid = round($sizeMax / 2);
		$destWidth = ($sizeMax + $sizeMid + 1) * ($sizeMax - $sizeMid) / 2;
		$destHeight = $sizeMid * 2 + 1;

		// Allocate the output image.
		$destImage = imagecreatetruecolor($destWidth, $destHeight);
		imagesavealpha($destImage, true);
		imagefill($destImage, 0, 0, imagecolorallocatealpha($destImage, 0, 0, 0, 127));

		// Top line (i.e. large size sprites).
		$offset = 0;
		for($size = $sizeMid + 1; $size <= $sizeMax; ++$size) {
			imagecopyresampled($destImage, $srcImage, $offset, 0, 0, 0, $size, $size, $srcWidth, $srcHeight);
			$offset += $size;
		}

		// Bottom line (i.e. small size sprites).
		$offset = 0;
		for($size = $sizeMid; $size >= $sizeMin; --$size) {
			imagecopyresampled($destImage, $srcImage, $offset, $destHeight - $size, 0, 0, $size, $size, $srcWidth, $srcHeight);
			$offset += $size;
		}

		// Save the output image.
		imagepng($destImage, $destPath);

		// Free resources.
		imagedestroy($destImage);
		imagedestroy($srcImage);
		return true;
	}


	private static function getAttachment() {
		$attachmentId = isset($_POST['attachmentId']) ? RPBChessboardHelperValidation::validateInteger($_POST['attachmentId'], 0) : null;
		if(!isset($attachmentId)) {
			return null;
		}

		$path = get_attached_file($attachmentId);
		if(!$path) {
			return null;
		}

		$url = wp_get_attachment_image_url($attachmentId, array());

		list($width, $height, $type) = getimagesize($path);

		return (object) array('id' => $attachmentId, 'path' => $path, 'url' => $url, 'width' => $width, 'height' => $height, 'type' => $type);
	}
}
