/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2022  Yoann Le Montagner <yo35 -at- melix.net>       *
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
import './PGNEditor.css';

import PropTypes from 'prop-types';
import React from 'react';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, PanelRow, RadioControl, TextControl } from '@wordpress/components';

import { parsePieceSymbols, flattenPieceSymbols } from './util';

const i18n = RPBChessboard.i18n;


/**
 * Global counter for DOM ID generation.
 */
let pgnTextareaIdCounter = 0;


/**
 * Whether localization is available for piece symbols or not.
 */
function isPieceSymbolLocalizationAvailable() {
	return [ ...'KQRBNP' ].some(p => i18n.PIECE_SYMBOLS[p] !== p);
}


/**
 * PGN editor
 */
class PGNEditor extends React.Component {

	constructor(props) {
		super(props);
		this.pgnTextareaId = 'rpbchessboard-pgnTextarea-' + (pgnTextareaIdCounter++);
	}

	handlePgnTextareaChanged(value) {
		this.props.setAttributes({ ...this.props.attributes, pgn: value });
	}

	handlePieceSymbolCodeChanged(code, elements) {
		let pieceSymbols = code === 'custom' ? flattenPieceSymbols(elements) : code;
		this.props.setAttributes({ ...this.props.attributes, pieceSymbols: pieceSymbols });
	}

	handlePieceSymbolChanged(piece, newValue, elements) {
		let newElements = { ...elements };
		newElements[piece] = newValue;
		this.props.setAttributes({ ...this.props.attributes, pieceSymbols: flattenPieceSymbols(newElements) });
	}


	/**
	 * Rendering entry point.
	 */
	render() {
		return (
			<div { ...this.props.blockProps }>
				{this.renderSidePanel()}
				{this.renderBlockContent()}
			</div>
		);
	}


	/**
	 * Rendering method for the controls in the right-side column.
	 */
	renderSidePanel() {
		return (
			<InspectorControls>
				{this.renderPieceSymbolsPanel()}
			</InspectorControls>
		);
	}


	/**
	 * Piece-symbol customization panel.
	 */
	renderPieceSymbolsPanel() {
		let isLocalizationAvailable = isPieceSymbolLocalizationAvailable();
		let { code, elements, futureElements } = this.getPieceSymbols(isLocalizationAvailable);

		let options = [];
		options.push({ label: i18n.PGN_EDITOR_USE_DEFAULT, value: '' });
		options.push({ label: i18n.PGN_EDITOR_OPTION_NATIVE, value: 'native' });
		if (isLocalizationAvailable) {
			options.push({ label: i18n.PGN_EDITOR_OPTION_LOCALIZED, value: 'localized' });
		}
		options.push({ label: i18n.PGN_EDITOR_OPTION_FIGURINES, value: 'figurines' });
		options.push({ label: i18n.PGN_EDITOR_OPTION_CUSTOM, value: 'custom' });

		let disabled = code !== 'custom';
		return (
			<PanelBody title={i18n.PGN_EDITOR_PANEL_PIECE_SYMBOLS} initialOpen={false}>
				<PanelRow>
					<RadioControl selected={code} onChange={value => this.handlePieceSymbolCodeChanged(value, futureElements)} options={options} />
				</PanelRow>
				<PanelRow>
					<TextControl className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.K} disabled={disabled} onChange={value => this.handlePieceSymbolChanged('K', value, futureElements)} />
					<TextControl className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.Q} disabled={disabled} onChange={value => this.handlePieceSymbolChanged('Q', value, futureElements)} />
					<TextControl className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.R} disabled={disabled} onChange={value => this.handlePieceSymbolChanged('R', value, futureElements)} />
					<TextControl className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.B} disabled={disabled} onChange={value => this.handlePieceSymbolChanged('B', value, futureElements)} />
					<TextControl className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.N} disabled={disabled} onChange={value => this.handlePieceSymbolChanged('N', value, futureElements)} />
					<TextControl className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.P} disabled={disabled} onChange={value => this.handlePieceSymbolChanged('P', value, futureElements)} />
				</PanelRow>
			</PanelBody>
		);
	}


	/**
	 * Render the text editor.
	 */
	renderBlockContent() {
		return (
			<div className="components-placeholder rpbchessboard-fixPlaceholder">
				<label className="components-placeholder__label" htmlFor={this.pgnTextareaId}>{i18n.PGN_EDITOR_TEXT_LABEL}</label>
				<textarea
					id={this.pgnTextareaId}
					className="block-editor-plain-text blocks-shortcode__textarea rpbchessboard-fixMarginBottom rpbchessboard-pgnTextarea"
					value={this.props.attributes.pgn}
					onChange={evt => this.handlePgnTextareaChanged(evt.target.value)}
				/>
			</div>
		);
	}

	getPieceSymbols(isLocalizationAvailable) {
		let isDefault = this.props.attributes.pieceSymbols === '';
		let code = isDefault ? RPBChessboard.defaultSettings.pieceSymbols : this.props.attributes.pieceSymbols;
		let customPieceSymbols = parsePieceSymbols(code);
		if (customPieceSymbols) {
			return { code: isDefault ? '' : 'custom', elements: customPieceSymbols, futureElements: customPieceSymbols };
		}
		else if (code === 'figurines') {
			return { code: isDefault ? '' : 'figurines', elements: { K: '-', Q: '-', R: '-', B: '-', N: '-', P: '-' }, futureElements: { K: 'K', Q: 'Q', R: 'R', B: 'B', N: 'N', P: 'P' } };
		}
		else if (code === 'localized' && isLocalizationAvailable) {
			let elements = { K: i18n.PIECE_SYMBOLS.K, Q: i18n.PIECE_SYMBOLS.Q, R: i18n.PIECE_SYMBOLS.R, B: i18n.PIECE_SYMBOLS.B, N: i18n.PIECE_SYMBOLS.N, P: i18n.PIECE_SYMBOLS.P };
			return { code: isDefault ? '' : 'localized', elements: elements, futureElements: elements };
		}
		else {
			let elements = { K: 'K', Q: 'Q', R: 'R', B: 'B', N: 'N', P: 'P' };
			return { code: isDefault ? '' : 'native', elements: elements, futureElements: elements };
		}
	}
}


PGNEditor.propTypes = {
	blockProps: PropTypes.object,
	attributes: PropTypes.object,
	setAttributes: PropTypes.func,
};


/**
 * Registration
 */
registerBlockType('rpb-chessboard/pgn', {
	apiVersion: 2,
	title: i18n.PGN_EDITOR_TITLE,
	// TODO icon: <FENEditorIcon />,
	category: 'media',
	attributes: {
		pgn: {
			type: 'string',
			default: ''
		},
		pieceSymbols: {
			type: 'string',
			default: ''
		},
	},
	example: {
		attributes: {
			pgn: '', // TODO fill PGN example
			pieceSymbols: '',
		}
	},
	edit: ({ attributes, setAttributes }) => {
		let blockProps = useBlockProps();
		return <PGNEditor blockProps={blockProps} attributes={attributes} setAttributes={setAttributes} />;
	},
});
