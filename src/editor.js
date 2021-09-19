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
import { Button, ButtonGroup, ComboboxControl, Dropdown, PanelBody, PanelRow, RadioControl, RangeControl, SelectControl,
	TextControl, ToggleControl, ToolbarButton, ToolbarGroup } from '@wordpress/components';
import { moveTo, rotateLeft } from '@wordpress/icons';

import kokopu from 'kokopu';
import { Chessboard, flattenSquareMarkers, flattenArrowMarkers, flattenTextMarkers, SquareMarkerIcon, ArrowMarkerIcon, TextMarkerIcon } from 'kokopu-react';

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
			textMarkerMode: 'A',
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
		else if (/addTextMarker-([gry])/.test(this.state.interactionMode)) {
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
		this.props.setAttributes({ ...this.props.attributes, squareMarkers: {}, arrowMarkers: {}, textMarkers: {} });
	}

	handleAlignmentChanged(value) {
		this.props.setAttributes({ ...this.props.attributes, align: value });
	}

	handleSquareSizeChanged(value) {
		this.props.setAttributes({ ...this.props.attributes, squareSize: value });
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
			editionModeIcon = <div style={{ width: '24px', height: '24px' }}>{moveTo}</div>;
		}
		else if (/addPiece-([wb][pnbrqk])/.test(this.state.interactionMode)) {
			let coloredPiece = RegExp.$1;
			innerInteractionMode = 'clickSquares';
			editionModeIcon = <img src={mainPieceset[coloredPiece]} width={24} height={24} />;
		}
		else if (/addSquareMarker-([gry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			innerInteractionMode = 'clickSquares';
			editionModeIcon = <SquareMarkerIcon size={24} color={mainColorset[color]} />;
		}
		else if (/addArrowMarker-([gry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			innerInteractionMode = 'editArrows';
			editedArrowColor = color;
			editionModeIcon = <ArrowMarkerIcon size={24} color={mainColorset[color]} />;
		}
		else if (/addTextMarker-([gry])/.test(this.state.interactionMode)) {
			let color = RegExp.$1;
			innerInteractionMode = 'clickSquares';
			editionModeIcon = <TextMarkerIcon size={24} color={mainColorset[color]} symbol={this.state.textMarkerMode} />
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
					<ToolbarButton label={i18n.FEN_EDITOR_LABEL_MOVE_PIECES} icon={moveTo} onClick={() => setInteractionMode('movePieces')} />
					<AddPieceDropdown color="w" />
					<AddPieceDropdown color="b" />
					<ToolbarButton label={i18n.FEN_EDITOR_LABEL_TOGGLE_TURN} icon={toggleTurnIcon} onClick={() => this.handleToggleTurnClicked()} />
				</ToolbarGroup>
				<ToolbarGroup>
					<ToolbarButton label={i18n.FEN_EDITOR_LABEL_FLIP} icon={rotateLeft} onClick={() => this.handleFlipClicked()} />
				</ToolbarGroup>
			</BlockControls>
		);
	}


	/**
	 * Rendering method for the controls in the right-side column.
	 */
	renderSidePanel(fen, editionModeIcon, setInteractionMode) {

		// Square/arrow marker selector.
		function AddMarkerButtonGroup({ iconBuilder, interactionModePrefix }) {
			function AddMarkerButton({ color }) {
				return <Button icon={iconBuilder(color)} onClick={() => setInteractionMode(interactionModePrefix + color)} />;
			}
			return (
				<ButtonGroup>
					<AddMarkerButton color="g" />
					<AddMarkerButton color="r" />
					<AddMarkerButton color="y" />
				</ButtonGroup>
			);
		}

		// Combo-box to select the type of text marker
		function TextMarkerTypeControl({ value, onChange }) {
			let options = [...'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'].map(mode => {
				let label = i18n.FEN_EDITOR_LABEL_TEXT_MARKER.replace('%s', mode); // FIXME use a proper text replacement method
				return { value: mode, label: label };
			});
			return <SelectControl value={value} options={options} onChange={onChange} />;
		}

		// Combox-box to select the colorset or the pieceset.
		function SetCodeControl({ label, value, available, onChange }) {
			let options = [ { value: '', label: '<' + i18n.FEN_EDITOR_USE_DEFAULT + '>' } ];
			Object.keys(available).sort().forEach(key => options.push({ value: key, label: available[key] }));
			return <ComboboxControl label={label} value={value} options={options} onChange={onChange} />;
		}

		let isDefaultSize = this.props.attributes.squareSize === 0;
		let squareSizeControl = isDefaultSize ? undefined : <RangeControl label={i18n.FEN_EDITOR_CONTROL_SQUARE_SIZE}
			value={this.props.attributes.squareSize} min={RPBChessboard.availableSquareSize.min} max={RPBChessboard.availableSquareSize.max} step={1}
			onChange={value => this.handleSquareSizeChanged(value)}
		/>;
		return (
			<InspectorControls>
				<PanelBody title={i18n.FEN_EDITOR_PANEL_POSITION}>
					<PanelRow>
						<Button isSecondary text={i18n.FEN_EDITOR_LABEL_RESET_POSITION} label={i18n.FEN_EDITOR_TOOLTIP_RESET_POSITION}
							onClick={() => this.handleSetPositionClicked('start')} />
						<Button isSecondary text={i18n.FEN_EDITOR_LABEL_CLEAR_POSITION} label={i18n.FEN_EDITOR_TOOLTIP_CLEAR_POSITION}
							onClick={() => this.handleSetPositionClicked('empty')} />
						<Button isSecondary text={i18n.FEN_EDITOR_LABEL_CLEAR_ANNOTATIONS} label={i18n.FEN_EDITOR_TOOLTIP_CLEAR_ANNOTATIONS}
							onClick={() => this.handleClearAnnotationsClicked()} />
					</PanelRow>
					<PanelRow>
						<TextControl label={i18n.FEN_EDITOR_LABEL_FEN} value={fen} onChange={value => this.handleSetPositionClicked(value)} />
					</PanelRow>
					<PanelRow className="rpbchessboard-editionModeRow">
						<span>{i18n.FEN_EDITOR_CURRENT_EDITION_MODE}</span>
						{editionModeIcon}
					</PanelRow>
					<PanelRow>
						{i18n.FEN_EDITOR_LABEL_SQUARE_MARKER}
						<AddMarkerButtonGroup interactionModePrefix="addSquareMarker-" iconBuilder={color => <SquareMarkerIcon size={24} color={mainColorset[color]} />} />
					</PanelRow>
					<PanelRow>
						{i18n.FEN_EDITOR_LABEL_ARROW_MARKER}
						<AddMarkerButtonGroup interactionModePrefix="addArrowMarker-" iconBuilder={color => <ArrowMarkerIcon size={24} color={mainColorset[color]} />} />
					</PanelRow>
					<PanelRow className="rpbchessboard-fixMarginBottom">
						<TextMarkerTypeControl value={this.state.textMarkerMode} onChange={value => this.setState({ textMarkerMode: value })} />
						<AddMarkerButtonGroup interactionModePrefix="addTextMarker-"
							iconBuilder={color => <TextMarkerIcon size={24} color={mainColorset[color]} symbol={this.state.textMarkerMode} />} />
					</PanelRow>
				</PanelBody>
				<PanelBody title={i18n.FEN_EDITOR_PANEL_APPEARANCE} initialOpen={false}>
					<ToggleControl label={i18n.FEN_EDITOR_CONTROL_USE_DEFAULT_SIZE} checked={isDefaultSize}
						onChange={() => this.handleSquareSizeChanged(isDefaultSize ? RPBChessboard.defaultSettings.squareSize : 0)} />
					{squareSizeControl}
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
					coordinateVisible={this.props.attributes.coordinateVisible === '' ? RPBChessboard.defaultSettings.showCoordinates : this.props.attributes.coordinateVisible === 'true'}
					colorset={this.props.attributes.colorset === '' ? RPBChessboard.defaultSettings.colorset : this.props.attributes.colorset}
					pieceset={this.props.attributes.pieceset === '' ? RPBChessboard.defaultSettings.pieceset : this.props.attributes.pieceset}
					onPieceMoved={(from, to) => this.handlePieceMoved(from, to)}
					onSquareClicked={sq => this.handleSquareClicked(sq)}
					onArrowEdited={(from, to) => this.handleArrowEdited(from, to)}
				/>
			);
		}
		else {
			return <div className="rpbchessboard-editorErrorBox">{i18n.FEN_EDITOR_PARSING_ERROR}</div>
		}
	}


	parsePositionAttribute() {
		try {
			let position = new kokopu.Position(this.props.attributes.position);
			return { valid: true, position: position, fen: position.fen() };
		}
		catch (error) {
			if (error instanceof kokopu.exception.InvalidFEN) {
				return { valid: false, fen: this.props.attributes.position };
			}
			else {
				throw error;
			}
		}
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
			position: 'start',
			flipped: false,
			squareMarkers: {},
			arrowMarkers: {},
			textMarkers: {},
			align: '',
			squareSize: '',
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

		function pushArg(value, defaultValue, fenShortcodeAttribute) {
			if (value !== defaultValue) {
				args.push(fenShortcodeAttribute + '=' + value);
			}
		}

		pushArg(flattenSquareMarkers(attributes.squareMarkers), '', 'csl');
		pushArg(flattenArrowMarkers(attributes.arrowMarkers), '', 'cal');
		pushArg(flattenTextMarkers(attributes.textMarkers), '', 'ctl');
		pushArg(attributes.align, '', 'align');
		pushArg(attributes.squareSize, 0, 'square_size');
		pushArg(attributes.coordinateVisible, '', 'show_coordinates');
		pushArg(attributes.colorset, '', 'colorset');
		pushArg(attributes.pieceset, '', 'pieceset');
		return '[' + args.join(' ') + ']' + attributes.position + '[/' + RPBChessboard.fenShortcode + ']';
	},
});
