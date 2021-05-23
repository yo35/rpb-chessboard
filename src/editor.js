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
import { useBlockProps, BlockControls, InspectorControls } from '@wordpress/block-editor';
import { Button, ComboboxControl, Dropdown, PanelBody, RadioControl, ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { moveTo, rotateLeft } from '@wordpress/icons';

import kokopu from 'kokopu';
import { Chessboard, piecesets } from 'kokopu-react';

import addWIconPath from './images/add-w.png';
import addBIconPath from './images/add-b.png';
import toggleTurnIconPath from './images/toggle-turn.png';
import squareMarkerAllIconPath from './images/square-marker-all.png';
import squareMarkerGIconPath from './images/square-marker-g.png';
import squareMarkerRIconPath from './images/square-marker-r.png';
import squareMarkerYIconPath from './images/square-marker-y.png';
import arrowMarkerAllIconPath from './images/arrow-marker-all.png';
import arrowMarkerGIconPath from './images/arrow-marker-g.png';
import arrowMarkerRIconPath from './images/arrow-marker-r.png';
import arrowMarkerYIconPath from './images/arrow-marker-y.png';

const addIconPath = {
	'w': addWIconPath,
	'b': addBIconPath,
};

const squareMarkerIconPath = {
	'all': squareMarkerAllIconPath,
	'g': squareMarkerGIconPath,
	'r': squareMarkerRIconPath,
	'y': squareMarkerYIconPath,
};

const arrowMarkerIconPath = {
	'all': arrowMarkerAllIconPath,
	'g': arrowMarkerGIconPath,
	'r': arrowMarkerRIconPath,
	'y': arrowMarkerYIconPath,
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

	handleToggleTurnClicked() {
		let position = new kokopu.Position(this.props.attributes.position);
		position.turn(kokopu.oppositeColor(position.turn()));
		this.props.setAttributes({ ...this.props.attributes, position: position.fen() });
	}

	handleFlipClicked() {
		let flipped = !this.props.attributes.flipped;
		this.props.setAttributes({ ...this.props.attributes, flipped: flipped });
	}

	handleSetPositionClicked(value) {
		this.props.setAttributes({ ...this.props.attributes, position: value });
	}

	handleClearAnnotationsClicked() {
		this.props.setAttributes({ ...this.props.attributes, squareMarkers: {}, arrowMarkers: {} });
	}

	handleAlignmentChanged(value) {
		this.props.setAttributes({ ...this.props.attributes, align: value });
	}

	handleCoordinateVisibleChanged(value) {
		this.props.setAttributes({ ...this.props.attributes, coordinateVisible: value });
	}

	handleColorsetChanged(value) {
		this.props.setAttributes({ ...this.props.attributes, colorset: value });
	}

	handlePiecesetChanged(value) {
		this.props.setAttributes({ ...this.props.attributes, pieceset: value });
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
		function AddMarkerDropdown({ label, iconPath, interactionModePrefix }) {
			let renderToggle = ({ isOpen, onToggle }) => {
				let icon = <img src={iconPath.all} width={24} height={24} />;
				return <ToolbarButton label={label} icon={icon} onClick={onToggle} aria-expanded={isOpen} />;
			};
			let renderContent = ({ onClose }) => {
				function AddMarkerButton({ color }) {
					let onClick = () => {
						setInterationMode(interactionModePrefix + color);
						onClose();
					};
					let icon = <img src={iconPath[color]} width={24} height={24} />;
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

		// Combox-box to select the colorset or the pieceset.
		function SetCodeControl({ label, value, available, onChange }) {
			let options = [ { value: '', label: '<' + i18n.FEN_EDITOR_USE_DEFAULT + '>' } ];
			Object.keys(available).sort().forEach(key => options.push({ value: key, label: available[key] }));
			return <ComboboxControl label={label} value={value} options={options} onChange={onChange} />;
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
						<ToolbarButton label={i18n.FEN_EDITOR_LABEL_MOVE_PIECES} icon={moveTo} onClick={() => setInterationMode('movePieces')} />
						<AddPieceDropdown color="w" />
						<AddPieceDropdown color="b" />
						<ToolbarButton label={i18n.FEN_EDITOR_LABEL_TOGGLE_TURN} icon={toggleTurnIcon} onClick={() => this.handleToggleTurnClicked()} />
					</ToolbarGroup>
					<ToolbarGroup>
						<ToolbarButton label={i18n.FEN_EDITOR_LABEL_FLIP} icon={rotateLeft} onClick={() => this.handleFlipClicked()} />
					</ToolbarGroup>
					<ToolbarGroup>
						<AddMarkerDropdown label={i18n.FEN_EDITOR_LABEL_ADD_SQUARE_MARKER} iconPath={squareMarkerIconPath} interactionModePrefix="addSquareMarker-" />
						<AddMarkerDropdown label={i18n.FEN_EDITOR_LABEL_ADD_ARROW_MARKER} iconPath={arrowMarkerIconPath} interactionModePrefix="addArrowMarker-" />
					</ToolbarGroup>
				</BlockControls>
				<InspectorControls>
					<PanelBody title={i18n.FEN_EDITOR_PANEL_POSITION}>
						<Button text={i18n.FEN_EDITOR_LABEL_RESET_POSITION} label={i18n.FEN_EDITOR_TOOLTIP_RESET_POSITION} onClick={() => this.handleSetPositionClicked('start')} />
						<Button text={i18n.FEN_EDITOR_LABEL_CLEAR_POSITION} label={i18n.FEN_EDITOR_TOOLTIP_CLEAR_POSITION} onClick={() => this.handleSetPositionClicked('empty')} />
						<Button text={i18n.FEN_EDITOR_LABEL_CLEAR_ANNOTATIONS} label={i18n.FEN_EDITOR_TOOLTIP_CLEAR_ANNOTATIONS} onClick={() => this.handleClearAnnotationsClicked()} />
					</PanelBody>
					<PanelBody title={i18n.FEN_EDITOR_PANEL_APPEARANCE} initialOpen={false}>
						<RadioControl label={i18n.FEN_EDITOR_CONTROL_ALIGNMENT} selected={this.props.attributes.align}
							onChange={value => this.handleAlignmentChanged(value)} options={[
							{ label: i18n.FEN_EDITOR_USE_DEFAULT, value: '' },
							{ label: i18n.FEN_EDITOR_OPTION_CENTER, value: 'center' },
							{ label: i18n.FEN_EDITOR_OPTION_FLOAT_LEFT, value: 'floatLeft' },
							{ label: i18n.FEN_EDITOR_OPTION_FLOAT_RIGHT, value: 'floatRight' },
						]} />
						<RadioControl label={i18n.FEN_EDITOR_CONTROL_COORDINATES} selected={this.props.attributes.coordinateVisible}
							onChange={value => this.handleCoordinateVisibleChanged(value)} options={[
							{ label: i18n.FEN_EDITOR_USE_DEFAULT, value: '' },
							{ label: i18n.FEN_EDITOR_OPTION_HIDDEN, value: 'false' },
							{ label: i18n.FEN_EDITOR_OPTION_VISIBLE, value: 'true' },
						]} />
						<SetCodeControl label={i18n.FEN_EDITOR_CONTROL_COLORSET} value={this.props.attributes.colorset}
							available={RPBChessboard.availableColorsets} onChange={value => this.handleColorsetChanged(value)}
						/>
						<SetCodeControl label={i18n.FEN_EDITOR_CONTROL_PIECESET} value={this.props.attributes.pieceset}
							available={RPBChessboard.availablePiecesets} onChange={value => this.handlePiecesetChanged(value)}
						/>
					</PanelBody>
				</InspectorControls>
				<Chessboard position={position} flipped={this.props.attributes.flipped} squareSize={40}
					interactionMode={innerInteractionMode} editedArrowColor={editedArrowColor}
					squareMarkers={this.props.attributes.squareMarkers}
					arrowMarkers={this.props.attributes.arrowMarkers}
					coordinateVisible={this.props.attributes.coordinateVisible === '' ? RPBChessboard.defaultSettings.showCoordinates : this.props.attributes.coordinateVisible === 'true'}
					colorset={this.props.attributes.colorset === '' ? RPBChessboard.defaultSettings.colorset : this.props.attributes.colorset}
					pieceset={this.props.attributes.pieceset === '' ? RPBChessboard.defaultSettings.pieceset : this.props.attributes.pieceset}
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
function flattenMarkers(args, markers, fenShortcodeAttribute) {
	let markersAsString = Object.entries(markers).map(([ key, value ]) => value.toUpperCase() + key);
	if (markersAsString.length !== 0) {
		args.push(fenShortcodeAttribute + '=' + markersAsString.join(','));
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
		flipped: {
			type: 'boolean',
			default: false
		},
		squareMarkers: {
			type: 'object',
			default: {}
		},
		arrowMarkers: {
			type: 'object',
			default: {}
		},
		align: {
			type: 'string',
			default: ''
		},
		coordinateVisible: {
			type: 'string',
			default: ''
		},
		colorset: {
			type: 'string',
			default: ''
		},
		pieceset: {
			type: 'string',
			default: ''
		},
	},
	example: {
		attributes: {
			position: 'start',
			flipped: false,
			squareMarkers: {},
			arrowMarkers: {},
			align: '',
			coordinateVisible: '',
			colorset: '',
			pieceset: '',
		}
	},
	edit: ({ attributes, setAttributes }) => {
		let blockProps = useBlockProps();
		return <FENEditor blockProps={blockProps} attributes={attributes} setAttributes={setAttributes} />;
	},
	save: ({ attributes }) => {
		let args = [ RPBChessboard.fenShortcode, 'flip=' + attributes.flipped ];
		flattenMarkers(args, attributes.squareMarkers, 'csl');
		flattenMarkers(args, attributes.arrowMarkers, 'cal');
		if (attributes.align !== '') {
			args.push('align=' + attributes.align);
		}
		if (attributes.coordinateVisible !== '') {
			args.push('show_coordinates=' + attributes.coordinateVisible);
		}
		if (attributes.colorset !== '') {
			args.push('colorset=' + attributes.colorset);
		}
		if (attributes.pieceset !== '') {
			args.push('pieceset=' + attributes.pieceset);
		}
		return '[' + args.join(' ') + ']' + attributes.position + '[/' + RPBChessboard.fenShortcode + ']';
	},
});
