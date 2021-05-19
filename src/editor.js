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

import addWIconPath from './add-w.png';
import addBIconPath from './add-b.png';
import toggleTurnIconPath from './toggle-turn.png';
import addGSquareMarkerIconPath from './add-csl-g.png';
import addRSquareMarkerIconPath from './add-csl-r.png';
import addYSquareMarkerIconPath from './add-csl-y.png';
import addAllSquareMarkerIconPath from './add-csl.png';

const addIconPath = {
	'w': addWIconPath,
	'b': addBIconPath,
};

const addSquareMarkerIconPath = {
	'g': addGSquareMarkerIconPath,
	'r': addRSquareMarkerIconPath,
	'y': addYSquareMarkerIconPath,
};

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
		if (this.state.interactionMode === 'movePieces') {
			let position = new kokopu.Position(this.props.attributes.position);
			position.square(to, position.square(from));
			position.square(from, '-');
			this.props.setAttributes({ ...this.props.attributes, position: position.fen() });
		}
	}

	handleSquareClicked(sq) {
		if (/addPiece-([wb][pnbrqk])/.test(this.state.interactionMode)) {
			let coloredPiece = RegExp.$1;
			let position = new kokopu.Position(this.props.attributes.position);
			position.square(sq, position.square(sq) === coloredPiece ? '-' : coloredPiece);
			this.props.setAttributes({ ...this.props.attributes, position: position.fen() });
		}
		else if (/addSquareMarker-([gry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			let squareMarkers = { ...this.props.attributes.squareMarkers };
			if (squareMarkers[sq] === color) {
				delete squareMarkers[sq];
			}
			else {
				squareMarkers[sq] = color;
			}
			this.props.setAttributes({ ...this.props.attributes, squareMarkers: squareMarkers });
		}
	}

	handleArrowEdited(from, to) {
		if (/addArrowMarker-([gry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			let key = from + to;
			let arrowMarkers = { ...this.props.attributes.arrowMarkers };
			if (arrowMarkers[key] === color) {
				delete arrowMarkers[key];
			}
			else {
				arrowMarkers[key] = color;
			}
			this.props.setAttributes({ ...this.props.attributes, arrowMarkers: arrowMarkers });
		}
	}

	handleTurnToggled() {
		let position = new kokopu.Position(this.props.attributes.position);
		position.turn(kokopu.oppositeColor(position.turn()));
		this.props.setAttributes({ ...this.props.attributes, position: position.fen() });
	}

	render() {
		let setInterationMode = newInteractionMode => this.setState({ interactionMode: newInteractionMode });
		let position = new kokopu.Position(this.props.attributes.position);

		// Piece selector in the FEN editor toolbar.
		function AddPieceDropdown({ color }) {
			let renderToggle = ({ isOpen, onToggle }) => {
				let icon = <img src={addIconPath[color]} width={24} height={24} />;
				return <ToolbarButton label={i18n.FEN_EDITOR_LABEL_ADD_PIECES[color]} icon={icon} onClick={onToggle} aria-expanded={isOpen} />;
			};
			let renderContent = ({ onClose }) => {
				function AddPieceButton({ coloredPiece }) {
					let onClick = () => {
						setInterationMode('addPiece-' + coloredPiece);
						onClose();
					};
					let icon = <img src={piecesets.cburnett[coloredPiece]} width={24} height={24} />;
					return <Button label={i18n.FEN_EDITOR_LABEL_ADD_PIECE[coloredPiece]} icon={icon} onClick={onClick} />;
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

		// Square/arrow marker selector in FEN editor toolbar.
		function AddMarkerDropdown({ label, interactionModePrefix }) {
			let renderToggle = ({ isOpen, onToggle }) => {
				let icon = <img src={addAllSquareMarkerIconPath} width={24} height={24} />; // TODO customize icon
				return <ToolbarButton label={label} icon={icon} onClick={onToggle} aria-expanded={isOpen} />;
			};
			let renderContent = ({ onClose }) => {
				function AddMarkerButton({ color }) {
					let onClick = () => {
						setInterationMode(interactionModePrefix + color);
						onClose();
					};
					let icon = <img src={addSquareMarkerIconPath[color]} width={24} height={24} />; // TODO customize icon
					return <Button icon={icon} onClick={onClick} />;
				}
				return (
					<div>
						<AddMarkerButton color="g" />
						<AddMarkerButton color="r" />
						<AddMarkerButton color="y" />
					</div>
				);
			};
			return <Dropdown renderToggle={renderToggle} renderContent={renderContent} />;
		}

		// Chessboard widget interaction mode
		let innerInteractionMode = '';
		let editedArrowColor = '';
		if (this.state.interactionMode === 'movePieces') {
			innerInteractionMode = 'movePieces';
		}
		else if (this.state.interactionMode.startsWith('addPiece-') || this.state.interactionMode.startsWith('addSquareMarker-')) {
			innerInteractionMode = 'clickSquares';
		}
		else if (/addArrowMarker-([gry])/.test(this.state.interactionMode)) {
			editedArrowColor = RegExp.$1;
			innerInteractionMode = 'editArrows';
		}

		// Icons
		let toggleTurnIcon = <img src={toggleTurnIconPath} width={24} height={24} />;

		// Render the block
		return (
			<div { ...this.props.blockProps }>
				<BlockControls>
					<ToolbarGroup>
						<ToolbarButton label={i18n.FEN_EDITOR_LABEL_MOVE_PIECES} icon={edit /* TODO change icon */ } onClick={() => setInterationMode('movePieces')} />
						<AddPieceDropdown color="w" />
						<AddPieceDropdown color="b" />
						<ToolbarButton label={i18n.FEN_EDITOR_LABEL_TOGGLE_TURN} icon={toggleTurnIcon} onClick={() => this.handleTurnToggled()} />
					</ToolbarGroup>
					<ToolbarGroup>
						<AddMarkerDropdown label={i18n.FEN_EDITOR_LABEL_ADD_SQUARE_MARKER} interactionModePrefix="addSquareMarker-" />
						<AddMarkerDropdown label={i18n.FEN_EDITOR_LABEL_ADD_ARROW_MARKER} interactionModePrefix="addArrowMarker-" />
					</ToolbarGroup>
				</BlockControls>
				<Chessboard position={position} interactionMode={innerInteractionMode} editedArrowColor={editedArrowColor}
					squareMarkers={this.props.attributes.squareMarkers}
					arrowMarkers={this.props.attributes.arrowMarkers}
					onPieceMoved={(from, to) => this.handlePieceMoved(from, to)}
					onSquareClicked={sq => this.handleSquareClicked(sq)}
					onArrowEdited={(from, to) => this.handleArrowEdited(from, to)}
				/>
			</div>
		);
	}
}


/**
 * Helper method for shortcode rendering.
 */
function flattenMarkers(markers, fenShortcodeAttribute) {
	let markersAsString = Object.entries(markers).map(([ key, value ]) => value.toUpperCase() + key);
	return markersAsString.length === 0 ? '' : ' ' + fenShortcodeAttribute + '=' + markersAsString.join(',');
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
		squareMarkers: {
			type: 'object',
			default: {}
		},
		arrowMarkers: {
			type: 'object',
			default: {}
		}
	},
	example: {
		attributes: {
			position: 'start',
			squareMarkers: {},
			arrowMarkers: {},
		}
	},
	edit: ({ attributes, setAttributes }) => {
		let blockProps = useBlockProps();
		return <FENEditor blockProps={blockProps} attributes={attributes} setAttributes={setAttributes} />;
	},
	save: ({ attributes }) => {
		let csl = flattenMarkers(attributes.squareMarkers, 'csl');
		let cal = flattenMarkers(attributes.arrowMarkers, 'cal');
		return '[fen' + csl + cal + ']' + attributes.position + '[/fen]'; // TODO plug fen_compat
	},
});
