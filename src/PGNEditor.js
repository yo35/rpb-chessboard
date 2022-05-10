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
import { useBlockProps } from '@wordpress/block-editor';

const i18n = RPBChessboard.i18n;


/**
 * Global counter for DOM ID generation.
 */
let pgnTextareaIdCounter = 0;


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


	/**
	 * Rendering entry point.
	 */
	render() {
		return (
			<div { ...this.props.blockProps }>
				{this.renderBlockContent()}
			</div>
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
					className="block-editor-plain-text blocks-shortcode__textarea rpbchessboard-fixTextarea rpbchessboard-pgnTextarea"
					value={this.props.attributes.pgn}
					onChange={evt => this.handlePgnTextareaChanged(evt.target.value)}
				/>
			</div>
		);
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
	},
	example: {
		attributes: {
			pgn: '', // TODO fill PGN example
		}
	},
	edit: ({ attributes, setAttributes }) => {
		let blockProps = useBlockProps();
		return <PGNEditor blockProps={blockProps} attributes={attributes} setAttributes={setAttributes} />;
	},
});
