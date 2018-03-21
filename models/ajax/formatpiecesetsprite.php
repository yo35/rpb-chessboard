<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
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
require_once RPBCHESSBOARD_ABSPATH . 'helpers/validation.php';


/**
 * Process a pieceset formatting request.
 */
class RPBChessboardModelAjaxFormatPiecesetSprite extends RPBChessboardAbstractModel {

	public function __construct() {
		parent::__construct();
		$this->loadDelegateModel( 'Common/DefaultOptionsEx' );
	}


	public function run() {

		$attachment = self::getAttachment();
		if ( ! isset( $attachment ) ) {
			wp_die();
		}

		if ( $attachment->width !== $attachment->height ) {
			wp_send_json_error( array( 'message' => __( 'Images used to create piecesets must be square images (identical height and width).', 'rpb-chessboard' ) ) );
		}
		if ( IMAGETYPE_PNG !== $attachment->type ) {
			wp_send_json_error( array( 'message' => __( 'Only PNG images can be used to create piecesets.', 'rpb-chessboard' ) ) );
		}

		$sprite = $this->generateSprite( $attachment->path, $attachment->width, $attachment->height );
		if ( ! $sprite ) {
			wp_send_json_error( array( 'message' => __( 'Error while processing the selected image.', 'rpb-chessboard' ) ) );
		}

		$thumbnailURL = wp_get_attachment_image_url( $attachment->id );
		$spriteURL    = $sprite;

		// Store the sprite URL for later reuse.
		update_option( 'rpb_sprite_' . md5( $thumbnailURL ), $sprite );

		wp_send_json_success(
			array(
				'attachmentId' => $attachment->id,
				'thumbnailURL' => $thumbnailURL,
				'spriteURL'    => $spriteURL,
			)
		);
	}

	private function generateSprite( $srcPath, $srcWidth, $srcHeight ) {

		// Load the input image.
		$srcImage = imagecreatefrompng( $srcPath );
		if ( ! $srcImage ) {
			return false;
		}

		// Create a temporary image we can remove later.
		$dir        = get_temp_dir();
		$filename   = uniqid();
		$temp_image = basename( $filename ) . '-' . wp_generate_password( 6, false ) . '.jpg';
		$temp_image = $dir . wp_unique_filename( $dir, $temp_image );

		// Compute sizes.
		$sizeMin    = $this->getMinimumSquareSize();
		$sizeMax    = $this->getMaximumSquareSize();
		$sizeMid    = round( $sizeMax / 2 );
		$destWidth  = ( $sizeMax + $sizeMid + 1 ) * ( $sizeMax - $sizeMid ) / 2;
		$destHeight = $sizeMid * 2 + 1;

		// Allocate the output image.
		$destImage = imagecreatetruecolor( $destWidth, $destHeight );
		imagesavealpha( $destImage, true );
		imagefill( $destImage, 0, 0, imagecolorallocatealpha( $destImage, 0, 0, 0, 127 ) );

		// Top line (i.e. large size sprites).
		$offset = 0;
		for ( $size = $sizeMid + 1; $size <= $sizeMax; ++$size ) {
			imagecopyresampled( $destImage, $srcImage, $offset, 0, 0, 0, $size, $size, $srcWidth, $srcHeight );
			$offset += $size;
		}

		// Bottom line (i.e. small size sprites).
		$offset = 0;
		for ( $size = $sizeMid; $size >= $sizeMin; --$size ) {
			imagecopyresampled( $destImage, $srcImage, $offset, $destHeight - $size, 0, 0, $size, $size, $srcWidth, $srcHeight );
			$offset += $size;
		}

		// Save the output image.
		imagepng( $destImage, $temp_image );

		$file_array = [
			'name'     => basename( $temp_image ),
			'tmp_name' => $temp_image,
		];

		// Sideload the image
		$sprite = media_handle_sideload( $file_array, 0 );

		// Free resources.
		imagedestroy( $destImage );
		imagedestroy( $srcImage );

		// Store the generated image URL, and make it an auto-draft to hide from the media library.
		$url = wp_get_attachment_image_url( $sprite );
		wp_update_post( array(
			'ID'          => $sprite,
			'post_status' => 'auto-draft',
		) );

		return $url;
	}


	private static function getAttachment() {
		$attachmentId = isset( $_POST['attachmentId'] ) ? RPBChessboardHelperValidation::validateInteger( $_POST['attachmentId'], 0 ) : null;
		if ( ! isset( $attachmentId ) ) {
			return null;
		}

		$path = get_attached_file( $attachmentId );
		if ( ! $path ) {
			return null;
		}

		$url = wp_get_attachment_url( $attachmentId );

		list($width, $height, $type) = getimagesize( $path );

		return (object) array(
			'id'     => $attachmentId,
			'path'   => $path,
			'url'    => $url,
			'width'  => $width,
			'height' => $height,
			'type'   => $type,
		);
	}
}
