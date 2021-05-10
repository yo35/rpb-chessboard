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


import './public-path';
import './editor.css';

import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';

import kokopu from 'kokopu';
import { Chessboard } from 'kokopu-react';

const i18n = RPBChessboard.i18n;


/**
 * Icon of the FEN editor
 */
function renderFENEditorIcon() {
	let squares = [];
	for(let x = 0; x < 4; ++x) {
		for(let y = 1 - x % 2; y < 4; y += 2) {
			squares.push(<rect x={x} y={y} width={1} height={1} />);
		}
	}
	return <svg viewBox="0 0 4 4">{squares}</svg>;
}


/**
 * FEN editor
 */
function renderFENEditor(blockProps, attributes, setAttributes) {

	let position = new kokopu.Position(attributes.position);

	function handlePieceMoved(from, to) {
		position.square(to, position.square(from));
		position.square(from, '-');
		setAttributes({ position: position.fen() });
	}

	return (
		<div { ...blockProps }>
			<Chessboard position={position} interactionMode="movePieces" onPieceMoved={handlePieceMoved} />
		</div>
	);
}


/**
 * Registration
 */
registerBlockType('rpb-chessboard/fen', {
	apiVersion: 2,
	title: i18n.FEN_EDITOR_TITLE,
	icon: renderFENEditorIcon(),
	category: 'media',
	attributes: {
		position: {
			type: 'string',
			default: 'start'
		},
	},
	example: {
		attributes: {
			position: 'start',
		}
	},
	edit: ({ attributes, setAttributes }) => {
		let blockProps = useBlockProps();
		return renderFENEditor(blockProps, attributes, setAttributes);
	},
	save: ({ attributes }) => {
		return '[fen]' + attributes.position + '[/fen]';
	},
});
