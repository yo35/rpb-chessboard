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
import { useBlockProps, BlockControls } from '@wordpress/block-editor';
import { Button, Dropdown, ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { edit } from '@wordpress/icons';

import kokopu from 'kokopu';
import { Chessboard, piecesets } from 'kokopu-react';

const i18n = RPBChessboard.i18n;


/**
 * Icon of the FEN editor
 */
function FENEditorIcon() {
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
class FENEditor extends React.Component {

	constructor(props) {
		super(props);
		this.state = {
			interactionMode: 'movePieces',
		};
	}

	handlePieceMoved(from, to) {
		let position = new kokopu.Position(this.props.attributes.position);
		position.square(to, position.square(from));
		position.square(from, '-');
		this.props.setAttributes({ position: position.fen() });
	}

	handleSquareClicked(sq) {
		if (/addPiece-([wb][pnbrqk])/.test(this.state.interactionMode)) {
			let coloredPiece = RegExp.$1;
			let position = new kokopu.Position(this.props.attributes.position);
			position.square(sq, position.square(sq) === coloredPiece ? '-' : coloredPiece);
			this.props.setAttributes({ position: position.fen() });
		}
	}

	render() {
		let setInterationMode = newInteractionMode => this.setState({ interactionMode: newInteractionMode });
		let position = new kokopu.Position(this.props.attributes.position);

		// Piece selector in the FEN editor toolbar.
		function AddPieceDropdown({ color }) {
			let renderToggle = ({ isOpen, onToggle }) => {
				return <ToolbarButton label={i18n.FEN_EDITOR_LABEL_ADD_PIECES[color]} icon={edit /* TODO change icon */ } onClick={onToggle} aria-expanded={isOpen} />;
			};
			let renderContent = ({ onClose }) => {
				function AddPieceButton({ coloredPiece }) {
					let onClick = () => {
						setInterationMode('addPiece-' + coloredPiece);
						onClose();
					};
					let coloredPieceIcon = <img src={piecesets.cburnett[coloredPiece]} width={24} height={24} />;
					return <Button label={i18n.FEN_EDITOR_LABEL_ADD_PIECE[coloredPiece]} icon={coloredPieceIcon} onClick={onClick} />;
				}
				return (
					<div>
						<AddPieceButton coloredPiece={color + 'p'} />
						<AddPieceButton coloredPiece={color + 'n'} />
						<AddPieceButton coloredPiece={color + 'b'} />
						<AddPieceButton coloredPiece={color + 'r'} />
						<AddPieceButton coloredPiece={color + 'q'} />
						<AddPieceButton coloredPiece={color + 'k'} />
					</div>
				);
			};
			return <Dropdown renderToggle={renderToggle} renderContent={renderContent} />;
		}

		// Chessboard widget interaction mode
		let innerInteractionMode = '';
		if (this.state.interactionMode === 'movePieces') {
			innerInteractionMode = 'movePieces';
		}
		else if (this.state.interactionMode.startsWith('addPiece-')) {
			innerInteractionMode = 'clickSquares';
		}

		// Render the block
		return (
			<div { ...this.props.blockProps }>
				<BlockControls>
					<ToolbarGroup>
						<ToolbarButton label={i18n.FEN_EDITOR_LABEL_MOVE_PIECES} icon={edit /* TODO change icon */ } onClick={() => setInterationMode('movePieces')} />
						<AddPieceDropdown color="w" />
						<AddPieceDropdown color="b" />
					</ToolbarGroup>
				</BlockControls>
				<Chessboard position={position} interactionMode={innerInteractionMode}
					onPieceMoved={(from, to) => this.handlePieceMoved(from, to)}
					onSquareClicked={(sq) => this.handleSquareClicked(sq)}
				/>
			</div>
		);
	}
}


/**
 * Registration
 */
registerBlockType('rpb-chessboard/fen', {
	apiVersion: 2,
	title: i18n.FEN_EDITOR_TITLE,
	icon: <FENEditorIcon />,
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
		return <FENEditor blockProps={blockProps} attributes={attributes} setAttributes={setAttributes} />;
	},
	save: ({ attributes }) => {
		return '[fen]' + attributes.position + '[/fen]';
	},
});
