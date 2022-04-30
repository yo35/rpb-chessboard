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
import './chessgame.css';

import React from 'react';
import kokopu from 'kokopu';
import { Chessboard, ErrorBox, Movetext } from 'kokopu-react';

import Box from '@mui/material/Box';
import IconButton from '@mui/material/IconButton';
import Stack from '@mui/material/Stack';
import Tooltip from '@mui/material/Tooltip';

import DownloadIcon from '@mui/icons-material/Download';
import FirstPageIcon from '@mui/icons-material/FirstPage';
import FlipIcon from '@mui/icons-material/FlipCameraAndroid';
import NavigateBeforeIcon from '@mui/icons-material/NavigateBefore';
import NavigateNextIcon from '@mui/icons-material/NavigateNext';
import LastPageIcon from '@mui/icons-material/LastPage';

const i18n = RPBChessboard.i18n;


/**
 * Chessgame widget (with its navigation board).
 */
export default class Chessgame extends React.Component {

	constructor(props) {
		super(props);
		this.state = {
			selection: false,
			withMove: false,
			withAdditionalFlip: false,
		};
		this.movetextRef = React.createRef();
	}

	render() {
		if (this.props.game === undefined) {
			return <div>TODO</div>; // TODO impl async fetch
		}

		let info = this.parseGame();
		if (!info.valid) {
			return <ErrorBox title={i18n.PGN_PARSING_ERROR_TITLE} message={info.message} text={info.pgn} errorIndex={info.errorIndex} lineNumber={info.lineNumber} />;
		}

		if (this.props.navigationBoard === 'above') {
			return <div>{this.renderNavigationBoard(info.game)}{this.renderMovetext(info.game, true)}</div>;
		}
		else if (this.props.navigationBoard === 'below') {
			return <div>{this.renderMovetext(info.game, true)}{this.renderNavigationBoard(info.game)}</div>;
		}
		else if (this.props.navigationBoard === 'floatLeft' || this.props.navigationBoard === 'floatRight') {
			return <div>{this.renderNavigationBoard(info.game)}{this.renderMovetext(info.game, true)}<div className="rpbchessboard-clearFloat"></div></div>;
		}
		else {
			return <div>{this.renderMovetext(info.game, false)}</div>;
		} // TODO impl scrollLeft/scrollRight/frame
	}

	renderMovetext(game, withNavigationBoard) {
		return <Movetext ref={this.movetextRef}
			game={game}
			selection={this.state.selection ? this.state.selection : undefined}
			interactionMode={withNavigationBoard ? 'selectMove' : undefined}
			onMoveSelected={(nodeId, evtOrigin) => this.handleMoveSelected(nodeId, evtOrigin)}
			pieceSymbols={this.getPieceSymbols()}
			diagramOptions={this.props.diagramOptions}
		/>;
	}

	renderNavigationBoard(game) {
		let { position, move, csl, cal, ctl } = this.getCurrentPositionAndAnnotations(game);
		let boardOptions = this.props.navigationBoardOptions;
		return (
			<Stack className={'rpbchessboard-navigationBoard-' + this.props.navigationBoard} alignItems="center" spacing="5px">
				<Chessboard
					position={position}
					move={move}
					squareMarkers={csl}
					arrowMarkers={cal}
					textMarkers={ctl}
					flipped={boardOptions.flipped ^ this.state.withAdditionalFlip}
					squareSize={boardOptions.squareSize}
					coordinateVisible={boardOptions.coordinateVisible}
					colorset={boardOptions.colorset}
					pieceset={boardOptions.pieceset}
					animated={this.props.animated}
					moveArrowVisible={this.props.moveArrowVisible}
				/>
				<Box>
					<Tooltip title={i18n.PGN_TOOLTIP_GO_FIRST}><IconButton size="small" onClick={() => this.handleNavClicked(game, Movetext.firstNodeId, false)}><FirstPageIcon /></IconButton></Tooltip>
					<Tooltip title={i18n.PGN_TOOLTIP_GO_PREVIOUS}><IconButton size="small" onClick={() => this.handleNavClicked(game, Movetext.previousNodeId, false)}><NavigateBeforeIcon /></IconButton></Tooltip>
					<Tooltip title={i18n.PGN_TOOLTIP_GO_NEXT}><IconButton size="small" onClick={() => this.handleNavClicked(game, Movetext.nextNodeId, true)}><NavigateNextIcon /></IconButton></Tooltip>
					<Tooltip title={i18n.PGN_TOOLTIP_GO_LAST}><IconButton size="small" onClick={() => this.handleNavClicked(game, Movetext.lastNodeId, false)}><LastPageIcon /></IconButton></Tooltip>
					{this.renderFlipButton()}
					{this.renderDownloadButton()}
				</Box>
			</Stack>
		);
	}

	renderFlipButton() {
		if (!this.props.withFlipButton) {
			return undefined;
		}
		return (<>
			<span className="rpbchessboard-toolbarSpacer" />
			<Tooltip title={i18n.PGN_TOOLTIP_FLIP}><IconButton size="small" onClick={() => this.handleFlipClicked()}><FlipIcon /></IconButton></Tooltip>
		</>);
	}

	renderDownloadButton() {
		if (!this.props.withDownloadButton) {
			return undefined;
		}
		return (<>
			<span className="rpbchessboard-toolbarSpacer" />
			<Tooltip title={i18n.PGN_TOOLTIP_DOWNLOAD}><IconButton size="small" onClick={() => this.handleDownloadClicked()}><DownloadIcon /></IconButton></Tooltip>
		</>);
	}

	getCurrentPositionAndAnnotations(game) {
		let node = this.state.selection && this.state.selection !== 'start' ? game.findById(this.state.selection) : undefined;
		if (node === undefined) {
			let mainVariation = game.mainVariation();
			return { position: mainVariation.initialPosition(), csl: mainVariation.tag('csl'), cal: mainVariation.tag('cal'), ctl: mainVariation.tag('ctl') };
		}
		else {
			let result = this.state.withMove ? { position: node.positionBefore(), move: node.notation() } : { position: node.position() };
			result.csl = node.tag('csl');
			result.cal = node.tag('cal');
			result.ctl = node.tag('ctl');
			return result;
		}
	}

	getPieceSymbols() {
		let pieceSymbols = this.props.pieceSymbols;
		if (pieceSymbols === 'native' || pieceSymbols === 'localized' || pieceSymbols === 'figurines') {
			return pieceSymbols;
		}
		else if (/\([A-Z]{6}\)/.test(pieceSymbols)) {
			return { K: pieceSymbols.charAt(0), Q: pieceSymbols.charAt(1), R: pieceSymbols.charAt(2), B: pieceSymbols.charAt(3), N: pieceSymbols.charAt(4), P: pieceSymbols.charAt(5) };
		}
		else {
			return undefined;
		}
	}

	parseGame() {
		let pgn = this.props.game;
		let gameIndex = this.props.gameIndex;

		try {
			let result = kokopu.pgnRead(pgn, gameIndex);
			return { valid: true, pgn: pgn, game: result };
		}
		catch (e) {
			if (e instanceof kokopu.exception.InvalidPGN) {
				return { valid: false, message: e.message, pgn: e.pgn, errorIndex: e.index, lineNumber: e.lineNumber };
			}
			else {
				throw e;
			}
		}
	}

	handleMoveSelected(nodeId, evtOrigin) {
		this.setState(nodeId ? { selection: nodeId, withMove: evtOrigin === 'key-next' } : { selection: false, withMove: false });
	}

	handleNavClicked(game, nodeIdProvider, withMove) {
		this.movetextRef.current.focus();
		if (!this.state.selection) {
			return;
		}
		let nodeId = nodeIdProvider(game, this.state.selection);
		if (nodeId) {
			this.setState({ selection: nodeId, withMove: withMove });
		}
	}

	handleFlipClicked() {
		this.setState({ withAdditionalFlip: !this.state.withAdditionalFlip });
	}

	handleDownloadClicked() {
		// TODO impl download
	}
}


Chessgame.defaultProps = {
	gameIndex: 0,
	navigationBoardOptions: {},
};
