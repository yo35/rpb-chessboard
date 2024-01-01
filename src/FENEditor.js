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


import './public-path';
import './FENEditor.css';

import PropTypes from 'prop-types';
import React from 'react';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, BlockControls, InspectorControls } from '@wordpress/block-editor';
import { Button, ButtonGroup, Dropdown, PanelBody, PanelRow, RadioControl, SelectControl, TextControl, ToggleControl, ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { moveTo as moveToIcon, rotateLeft as rotateLeftIcon } from '@wordpress/icons';

import { exception, Position, oppositeColor } from 'kokopu';
import { Chessboard, ErrorBox, SquareMarkerIcon, ArrowMarkerIcon, TextMarkerIcon } from 'kokopu-react';
import { format } from './util';
import ChessboardOptionEditor from './ChessboardOptionEditor';

import addWIconPath from './images/add-w.png';
import addBIconPath from './images/add-b.png';
import toggleTurnIconPath from './images/toggle-turn.png';

const addIconPath = {
	'w': addWIconPath,
	'b': addBIconPath,
};

const i18n = RPBChessboard.i18n;
const mainColorset = Chessboard.colorsets().original;
const mainPieceset = Chessboard.piecesets().cburnett;


/**
 * Icon of the FEN editor
 */
export function FENEditorIcon() {
	let squares = [];
	for(let x = 0; x < 4; ++x) {
		for(let y = 1 - x % 2; y < 4; y += 2) {
			squares.push(<rect x={x} y={y} width={1} height={1} />);
		}
	}
	return <svg viewBox="0 0 4 4">{squares}</svg>;
}


/**
 * Label used for text marker symbols in the combo-box in the side panel.
 */
function textMarkerLabel(symbol) {
	switch(symbol) {
		case 'plus':
			return '+';
		case 'times':
			return '\u00d7';
		case 'dot':
			return '\u2022';
		case 'circle':
			return '\u25cb';
		default:
			return symbol;
	}
}


/**
 * FEN editor
 */
class FENEditor extends React.Component {

	constructor(props) {
		super(props);
		this.state = {
			interactionMode: 'movePieces',
			textMarkerMode: 'circle',
		};
	}

	handleAttributeChanged(attribute, value) {
		let newAttributes = { ...this.props.attributes };
		newAttributes[attribute] = value;
		this.props.setAttributes(newAttributes);
	}

	handlePieceMoved(from, to) {
		if (this.state.interactionMode === 'movePieces') {
			let position = new Position(this.props.attributes.position);
			position.square(to, position.square(from));
			position.square(from, '-');
			this.props.setAttributes({ ...this.props.attributes, position: position.fen() });
		}
	}

	handleSquareClicked(sq) {
		if (/addPiece-([wb][pnbrqk])/.test(this.state.interactionMode)) {
			let coloredPiece = RegExp.$1;
			let position = new Position(this.props.attributes.position);
			position.square(sq, position.square(sq) === coloredPiece ? '-' : coloredPiece);
			this.props.setAttributes({ ...this.props.attributes, position: position.fen() });
		}
		else if (/addSquareMarker-([bgry])/.test(this.state.interactionMode)) {
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
		else if (/addTextMarker-([bgry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			let textMarkers = { ...this.props.attributes.textMarkers };
			if (textMarkers[sq] && textMarkers[sq].symbol === this.state.textMarkerMode && textMarkers[sq].color === color) {
				delete textMarkers[sq];
			}
			else {
				textMarkers[sq] = { symbol: this.state.textMarkerMode, color: color };
			}
			this.props.setAttributes({ ...this.props.attributes, textMarkers: textMarkers });
		}
	}

	handleArrowEdited(from, to) {
		if (/addArrowMarker-([bgry])/.test(this.state.interactionMode)) {
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
		let position = new Position(this.props.attributes.position);
		position.turn(oppositeColor(position.turn()));
		this.props.setAttributes({ ...this.props.attributes, position: position.fen() });
	}

	handleFlipClicked() {
		let flipped = !this.props.attributes.flipped;
		this.props.setAttributes({ ...this.props.attributes, flipped: flipped });
	}

	handleClearAnnotationsClicked() {
		this.props.setAttributes({ ...this.props.attributes, squareMarkers: {}, arrowMarkers: {}, textMarkers: {} });
	}


	/**
	 * Rendering entry point.
	 */
	render() {
		let setInteractionMode = newInteractionMode => this.setState({ interactionMode: newInteractionMode });
		let data = this.parsePositionAttribute();

		// Chessboard widget interaction mode
		let innerInteractionMode = '';
		let editedArrowColor = '';
		let editionModeIcon = undefined;
		if (this.state.interactionMode === 'movePieces') {
			innerInteractionMode = 'movePieces';
			editionModeIcon = <div style={{ width: '24px', height: '24px' }}>{moveToIcon}</div>;
		}
		else if (/addPiece-([wb][pnbrqk])/.test(this.state.interactionMode)) {
			let coloredPiece = RegExp.$1;
			innerInteractionMode = 'clickSquares';
			editionModeIcon = <img src={mainPieceset[coloredPiece]} width={24} height={24} />;
		}
		else if (/addSquareMarker-([bgry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			innerInteractionMode = 'clickSquares';
			editionModeIcon = <SquareMarkerIcon size={24} color={mainColorset['c' + color]} />;
		}
		else if (/addArrowMarker-([bgry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			innerInteractionMode = 'editArrows';
			editedArrowColor = color;
			editionModeIcon = <ArrowMarkerIcon size={24} color={mainColorset['c' + color]} />;
		}
		else if (/addTextMarker-([bgry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			innerInteractionMode = 'clickSquares';
			editionModeIcon = <TextMarkerIcon size={24} color={mainColorset['c' + color]} symbol={this.state.textMarkerMode} />;
		}

		// Render the block
		return (
			<div { ...this.props.blockProps }>
				{this.renderToolbar(setInteractionMode)}
				{this.renderSidePanel(data.fen, editionModeIcon, setInteractionMode)}
				{this.renderBlockContent(data, innerInteractionMode, editedArrowColor)}
			</div>
		);
	}


	/**
	 * Rendering method for the toolbar (above the block).
	 */
	renderToolbar(setInteractionMode) {

		// Piece selector in the FEN editor toolbar.
		function AddPieceDropdown({ color }) {
			let renderToggle = ({ isOpen, onToggle }) => {
				let icon = <img src={addIconPath[color]} width={24} height={24} />;
				return <ToolbarButton label={i18n.FEN_EDITOR_LABEL_ADD_PIECES[color]} icon={icon} onClick={onToggle} aria-expanded={isOpen} />;
			};
			let renderContent = ({ onClose }) => {
				function AddPieceButton({ coloredPiece }) {
					let onClick = () => {
						setInteractionMode('addPiece-' + coloredPiece);
						onClose();
					};
					let icon = <img src={mainPieceset[coloredPiece]} width={24} height={24} />;
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

		let toggleTurnIcon = <img src={toggleTurnIconPath} width={24} height={24} />;
		return (
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton label={i18n.FEN_EDITOR_LABEL_MOVE_PIECES} icon={moveToIcon} onClick={() => setInteractionMode('movePieces')} />
					<AddPieceDropdown color="w" />
					<AddPieceDropdown color="b" />
					<ToolbarButton label={i18n.FEN_EDITOR_LABEL_TOGGLE_TURN} icon={toggleTurnIcon} onClick={() => this.handleToggleTurnClicked()} />
				</ToolbarGroup>
				<ToolbarGroup>
					<ToolbarButton label={i18n.FEN_EDITOR_LABEL_FLIP} icon={rotateLeftIcon} onClick={() => this.handleFlipClicked()} />
				</ToolbarGroup>
			</BlockControls>
		);
	}


	/**
	 * Rendering method for the controls in the right-side column.
	 */
	renderSidePanel(fen, editionModeIcon, setInteractionMode) {
		return (
			<InspectorControls>
				{this.renderPositionAndAnnotationPanel(fen, editionModeIcon, setInteractionMode)}
				{this.renderChessboardAspectPanel()}
			</InspectorControls>
		);
	}


	/**
	 * Chessboard content panel (FEN field, marker edition mode, etc.).
	 */
	renderPositionAndAnnotationPanel(fen, editionModeIcon, setInteractionMode) {

		// Square/arrow marker selector.
		function AddMarkerButtonGroup({ iconBuilder, interactionModePrefix }) {
			function AddMarkerButton({ color }) {
				return <Button icon={iconBuilder(color)} onClick={() => setInteractionMode(interactionModePrefix + color)} />;
			}
			return (
				<ButtonGroup>
					<AddMarkerButton color="b" />
					<AddMarkerButton color="g" />
					<AddMarkerButton color="r" />
					<AddMarkerButton color="y" />
				</ButtonGroup>
			);
		}

		// Combo-box to select the type of text marker
		function TextMarkerTypeControl({ value, onChange }) {
			let availableSymbols = [ 'plus', 'times', 'dot', 'circle' ].concat([ ...'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' ]);
			let options = availableSymbols.map(symbol => {
				let label = format(i18n.FEN_EDITOR_LABEL_TEXT_MARKER, textMarkerLabel(symbol));
				return { value: symbol, label: label };
			});
			return <SelectControl value={value} options={options} onChange={onChange} />;
		}

		return (
			<PanelBody title={i18n.FEN_EDITOR_PANEL_POSITION}>
				<PanelRow>
					<Button isSecondary text={i18n.FEN_EDITOR_LABEL_RESET_POSITION} label={i18n.FEN_EDITOR_TOOLTIP_RESET_POSITION} onClick={() => this.handleAttributeChanged('position', 'start')} />
					<Button isSecondary text={i18n.FEN_EDITOR_LABEL_CLEAR_POSITION} label={i18n.FEN_EDITOR_TOOLTIP_CLEAR_POSITION} onClick={() => this.handleAttributeChanged('position', 'empty')} />
					<Button isSecondary text={i18n.FEN_EDITOR_LABEL_CLEAR_ANNOTATIONS} label={i18n.FEN_EDITOR_TOOLTIP_CLEAR_ANNOTATIONS} onClick={() => this.handleClearAnnotationsClicked()} />
				</PanelRow>
				<PanelRow className="rpbchessboard-fixMarginBottom rpbchessboard-fixWidth">
					<TextControl label={i18n.FEN_EDITOR_LABEL_FEN} value={fen} onChange={value => this.handleAttributeChanged('position', value)} />
				</PanelRow>
				<PanelRow className="rpbchessboard-editionModeRow">
					<span>{i18n.FEN_EDITOR_CURRENT_EDITION_MODE}</span>
					{editionModeIcon}
				</PanelRow>
				<PanelRow>
					{i18n.FEN_EDITOR_LABEL_SQUARE_MARKER}
					<AddMarkerButtonGroup interactionModePrefix="addSquareMarker-" iconBuilder={color => <SquareMarkerIcon size={24} color={mainColorset['c' + color]} />} />
				</PanelRow>
				<PanelRow>
					{i18n.FEN_EDITOR_LABEL_ARROW_MARKER}
					<AddMarkerButtonGroup interactionModePrefix="addArrowMarker-" iconBuilder={color => <ArrowMarkerIcon size={24} color={mainColorset['c' + color]} />} />
				</PanelRow>
				<PanelRow className="rpbchessboard-fixMarginBottom">
					<TextMarkerTypeControl value={this.state.textMarkerMode} onChange={value => this.setState({ textMarkerMode: value })} />
					<AddMarkerButtonGroup interactionModePrefix="addTextMarker-"
						iconBuilder={color => <TextMarkerIcon size={24} color={mainColorset['c' + color]} symbol={this.state.textMarkerMode} />}
					/>
				</PanelRow>
				<PanelRow>
					<ToggleControl className="rpbchessboard-fixMissingMarginTop" label={i18n.FEN_EDITOR_CONTROL_FLIP} checked={this.props.attributes.flipped}
						onChange={() => this.handleFlipClicked()} />
				</PanelRow>
			</PanelBody>
		);
	}


	/**
	 * Chessboard option panel (FEN field, marker edition mode, etc.).
	 */
	renderChessboardAspectPanel() {
		return (
			<PanelBody title={i18n.FEN_EDITOR_PANEL_APPEARANCE} initialOpen={false}>
				<RadioControl label={i18n.FEN_EDITOR_CONTROL_ALIGNMENT} selected={this.props.attributes.align} onChange={value => this.handleAttributeChanged('align', value)} options={[
					{ label: i18n.FEN_EDITOR_USE_DEFAULT, value: '' },
					{ label: i18n.FEN_EDITOR_OPTION_CENTER, value: 'center' },
					{ label: i18n.FEN_EDITOR_OPTION_FLOAT_LEFT, value: 'floatLeft' },
					{ label: i18n.FEN_EDITOR_OPTION_FLOAT_RIGHT, value: 'floatRight' },
				]} />
				<ChessboardOptionEditor
					defaultSquareSize={RPBChessboard.defaultSettings.sdoSquareSize}
					flipped={this.props.attributes.flipped}
					squareSize={this.props.attributes.squareSize}
					coordinateVisible={this.props.attributes.coordinateVisible}
					colorset={this.props.attributes.colorset}
					pieceset={this.props.attributes.pieceset}
					onFlipChanged={() => this.handleFlipClicked()}
					onSquareSizeChanged={value => this.handleAttributeChanged('squareSize', value)}
					onCoordinateVisibleChanged={value => this.handleAttributeChanged('coordinateVisible', value)}
					onColorsetChanged={value => this.handleAttributeChanged('colorset', value)}
					onPiecesetChanged={value => this.handleAttributeChanged('pieceset', value)}
				/>
			</PanelBody>
		);
	}


	/**
	 * Render the chessboard (or the error message if the FEN is invalid).
	 */
	renderBlockContent(data, innerInteractionMode, editedArrowColor) {
		if (data.valid) {
			return (
				<Chessboard position={data.position} flipped={this.props.attributes.flipped} squareSize={40}
					interactionMode={innerInteractionMode} editedArrowColor={editedArrowColor}
					squareMarkers={this.props.attributes.squareMarkers}
					arrowMarkers={this.props.attributes.arrowMarkers}
					textMarkers={this.props.attributes.textMarkers}
					coordinateVisible={this.props.attributes.coordinateVisible === '' ? RPBChessboard.defaultSettings.sdoCoordinateVisible : this.props.attributes.coordinateVisible === 'true'}
					colorset={this.props.attributes.colorset === '' ? RPBChessboard.defaultSettings.sdoColorset : this.props.attributes.colorset}
					pieceset={this.props.attributes.pieceset === '' ? RPBChessboard.defaultSettings.sdoPieceset : this.props.attributes.pieceset}
					onPieceMoved={(from, to) => this.handlePieceMoved(from, to)}
					onSquareClicked={sq => this.handleSquareClicked(sq)}
					onArrowEdited={(from, to) => this.handleArrowEdited(from, to)}
				/>
			);
		}
		else {
			return <ErrorBox title={i18n.FEN_PARSING_ERROR_TITLE} message={data.message} />;
		}
	}


	parsePositionAttribute() {
		try {
			let position = new Position(this.props.attributes.position);
			return { valid: true, position: position, fen: position.fen() };
		}
		catch (error) {
			if (error instanceof exception.InvalidFEN) {
				return { valid: false, message: error.message, fen: this.props.attributes.position };
			}
			else {
				throw error;
			}
		}
	}
}


FENEditor.propTypes = {
	blockProps: PropTypes.object,
	attributes: PropTypes.object,
	setAttributes: PropTypes.func,
};


/**
 * Registration
 */
export function registerFENBlock() {
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
			textMarkers: {
				type: 'object',
				default: {}
			},
			align: {
				type: 'string',
				default: ''
			},
			squareSize: {
				type: 'number',
				default: 0
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
				squareSize: 32,
			},
		},
		edit: ({ attributes, setAttributes }) => {
			let blockProps = useBlockProps();
			return <FENEditor blockProps={blockProps} attributes={attributes} setAttributes={setAttributes} />;
		},
	});
}
