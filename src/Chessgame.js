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
import './Chessgame.css';

import PropTypes from 'prop-types';
import React from 'react';
import ReactDOM from 'react-dom';
import kokopu from 'kokopu';
import { Chessboard, ErrorBox, Movetext } from 'kokopu-react';

import NavigationButton from './NavigationButton';
import { showPopupFrame, hidePopupFrame } from './NavigationFrame';
import { format, parsePieceSymbols } from './util';

const i18n = RPBChessboard.i18n;

const TOOLBAR_MARGIN = 5;
const TOOLBAR_HEIGHT = 28;


/**
 * Chessgame widget (with its navigation board).
 */
export default class Chessgame extends React.Component {

	constructor(props) {
		super(props);
		this.state = {
			selection: this.props.navigationBoard === 'frame' || this.props.navigationBoard === 'none' ? false : this.props.initialSelection,
			withMove: false,
			withAdditionalFlip: false,
			urlFetchStatus: false,
		};
		this.movetextRef = React.createRef();
		this.blobDownloadLinkRef = React.createRef();
		this.dynamicURL = false;
	}

	componentWillUnmount() {
		this.releaseDynamicURL();
		if (this.props.navigationBoard === 'frame') {
			hidePopupFrame(this);
		}
	}

	render() {
		let pgn = '';
		if (this.props.url) {
			if (this.state.urlFetchStatus === 'ok') {
				pgn = this.state.urlData;
			}
			else if (this.state.urlFetchStatus === 'error') {
				return <ErrorBox title={i18n.PGN_DOWNLOAD_ERROR_TITLE} message={this.state.urlError} />;
			}
			else {
				fetchPGN(this.props.url)
					.then(text => this.setState({ urlFetchStatus: 'ok', urlData: text }))
					.catch(e => this.setState({ urlFetchStatus: 'error', urlError: e }));
				return <div></div>;
			}
		}
		else {
			pgn = this.props.pgn;
		}

		let info = this.parseGame(pgn);
		if (!info.valid) {
			return <ErrorBox title={i18n.PGN_PARSING_ERROR_TITLE} message={info.message} text={info.pgn} errorIndex={info.errorIndex} lineNumber={info.lineNumber} />;
		}
		let { selection, node } = this.getSelectionAndNode(info.game);

		if (this.props.navigationBoard === 'above') {
			return <div>{this.renderNavigationBoard(info.game, selection, node, this.props.navigationBoardOptions)}{this.renderMovetext(info.game, selection, true)}</div>;
		}
		else if (this.props.navigationBoard === 'below') {
			return <div>{this.renderMovetext(info.game, selection, true)}{this.renderNavigationBoard(info.game, selection, node, this.props.navigationBoardOptions)}</div>;
		}
		else if (this.props.navigationBoard === 'floatLeft' || this.props.navigationBoard === 'floatRight') {
			let className = this.props.navigationBoard === 'floatLeft' ? 'rpbchessboard-indentWithFloatLeft' : '';
			return (
				<div className={className}>
					{this.renderNavigationBoard(info.game, selection, node, this.props.navigationBoardOptions)}
					{this.renderMovetext(info.game, selection, true)}
					<div className="rpbchessboard-clearFloat"></div>
				</div>
			);
		}
		else if (this.props.navigationBoard === 'scrollLeft' || this.props.navigationBoard === 'scrollRight') {
			let boardOptions = this.props.navigationBoardOptions;
			let height = Chessboard.size(boardOptions.squareSize, boardOptions.coordinateVisible, boardOptions.smallScreenLimits).height + TOOLBAR_MARGIN + TOOLBAR_HEIGHT;
			return (
				<div className={'rpbchessboard-scrollBox-' + this.props.navigationBoard}>
					{this.renderNavigationBoard(info.game, selection, node, boardOptions)}
					<div className="rpbchessboard-scrollArea" style={{ 'max-height': height }}>{this.renderMovetext(info.game, selection, true)}</div>
				</div>
			);
		}
		else if (this.props.navigationBoard === 'frame') {
			return <div>{this.renderMovetext(info.game, selection, true)}{this.renderNavigationBoardInPopup(info.game, selection, node)}</div>;
		}
		else {
			return <div>{this.renderMovetext(info.game, selection, false)}</div>;
		}
	}

	renderMovetext(game, selection, withNavigationBoard) {
		return <Movetext ref={this.movetextRef}
			game={game}
			selection={selection}
			interactionMode={withNavigationBoard ? 'selectMove' : undefined}
			onMoveSelected={(nodeId, evtOrigin) => this.handleMoveSelected(nodeId, evtOrigin)}
			pieceSymbols={this.getPieceSymbols()}
			diagramOptions={this.props.diagramOptions}
		/>;
	}

	renderNavigationBoardInPopup(game, selection, node) {
		if (!selection || !this.state.popupAnchor) {
			return undefined;
		}
		return ReactDOM.createPortal(this.renderNavigationBoard(game, selection, node, this.state.popupBoardOptions, this.state.setPopupTitle), this.state.popupAnchor);
	}

	renderNavigationBoard(game, selection, node, navigationBoardOptions, setTitle) {
		let { position, move, csl, cal, ctl, label } = this.getCurrentPositionAndAnnotations(game, node);
		if (setTitle) {
			setTitle(label);
		}
		let classNames = [ 'rpbchessboard-navigationBoard', 'rpbchessboard-navigationBoard-' + this.props.navigationBoard ];
		return (
			<div className={classNames.join(' ')}>
				<Chessboard
					position={position}
					move={move}
					squareMarkers={csl}
					arrowMarkers={cal}
					textMarkers={ctl}
					flipped={this.props.navigationBoardOptions.flipped ^ this.state.withAdditionalFlip}
					squareSize={navigationBoardOptions.squareSize}
					coordinateVisible={navigationBoardOptions.coordinateVisible}
					colorset={navigationBoardOptions.colorset}
					pieceset={navigationBoardOptions.pieceset}
					smallScreenLimits={navigationBoardOptions.smallScreenLimits}
					animated={navigationBoardOptions.animated}
					moveArrowVisible={navigationBoardOptions.moveArrowVisible}
				/>
				<div className="rpbchessboard-navigationToolbar" style={{ marginTop: TOOLBAR_MARGIN }}>
					<NavigationButton size={TOOLBAR_HEIGHT} type="first" tooltip={i18n.PGN_TOOLTIP_GO_FIRST} onClick={() => this.handleNavClicked(game, selection, Movetext.firstNodeId, false)} />
					<NavigationButton size={TOOLBAR_HEIGHT} type="previous" tooltip={i18n.PGN_TOOLTIP_GO_PREVIOUS} onClick={() => this.handleNavClicked(game, selection, Movetext.previousNodeId, false)} />
					<NavigationButton size={TOOLBAR_HEIGHT} type="next" tooltip={i18n.PGN_TOOLTIP_GO_NEXT} onClick={() => this.handleNavClicked(game, selection, Movetext.nextNodeId, true)} />
					<NavigationButton size={TOOLBAR_HEIGHT} type="last" tooltip={i18n.PGN_TOOLTIP_GO_LAST} onClick={() => this.handleNavClicked(game, selection, Movetext.lastNodeId, false)} />
					{this.renderFlipButton()}
					{this.renderDownloadButton()}
				</div>
			</div>
		);
	}

	renderFlipButton() {
		if (!this.props.withFlipButton) {
			return undefined;
		}
		return (<>
			<div className="rpbchessboard-toolbarSpacer" />
			<NavigationButton size={TOOLBAR_HEIGHT} type="flip" tooltip={i18n.PGN_TOOLTIP_FLIP} onClick={() => this.handleFlipClicked()} />
		</>);
	}

	renderDownloadButton() {
		if (!this.props.withDownloadButton) {
			return undefined;
		}
		return (<>
			<div className="rpbchessboard-toolbarSpacer" />
			<NavigationButton size={TOOLBAR_HEIGHT} type="download" tooltip={i18n.PGN_TOOLTIP_DOWNLOAD} onClick={() => this.handleDownloadClicked()} />
			<a ref={this.blobDownloadLinkRef} className="rpbchessboard-blobDownloadLink" href="#" download="game.pgn" />
		</>);
	}

	getCurrentPositionAndAnnotations(game, node) {
		if (node === undefined) {
			let mainVariation = game.mainVariation();
			return {
				position: mainVariation.initialPosition(),
				csl: mainVariation.tag('csl'),
				cal: mainVariation.tag('cal'),
				ctl: mainVariation.tag('ctl'),
				label: i18n.PGN_INITIAL_POSITION,
			};
		}
		else {
			let result = this.state.withMove && node.notation() !== '--' ? { position: node.positionBefore(), move: node.notation() } : { position: node.position() };
			result.csl = node.tag('csl');
			result.cal = node.tag('cal');
			result.ctl = node.tag('ctl');
			result.label = node.fullMoveNumber() + (node.moveColor() === 'w' ? '.' : '\u2026') + node.notation(); // TODO adapt to piece symbols attr
			return result;
		}
	}

	getPieceSymbols() {
		let pieceSymbols = parsePieceSymbols(this.props.pieceSymbols);
		if (pieceSymbols) {
			return pieceSymbols;
		}
		else if (this.props.pieceSymbols === 'native' || this.props.pieceSymbols === 'localized' || this.props.pieceSymbols === 'figurines') {
			return this.props.pieceSymbols;
		}
		else {
			return undefined;
		}
	}

	parseGame(pgn) {
		try {
			let result = kokopu.pgnRead(pgn, this.props.gameIndex);
			return { valid: true, game: result };
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

	getSelectionAndNode(game) {

		// Handle the special cases.
		if (this.state.selection === 'start') {
			return { selection: 'start' };
		}
		else if (this.state.selection === 'end') {
			let previousNode = undefined;
			let currentNode = game.mainVariation().first();
			while (currentNode) {
				previousNode = currentNode;
				currentNode = currentNode.next();
			}
			return previousNode ? { selection: previousNode.id(), node: previousNode } : { selection: 'start' };
		}

		// FIXME: adapt `.findById(..)` to search only nodes (not variation) and remove test `endsWith('start')`.
		let node = !this.state.selection || this.state.selection.endsWith('start') ? undefined : game.findById(this.state.selection);
		return node ? { selection: this.state.selection, node: node } : {};
	}

	handleMoveSelected(nodeId, evtOrigin) {
		this.setState(nodeId ? { selection: nodeId, withMove: evtOrigin === 'key-next' } : { selection: false, withMove: false });
		if (this.props.navigationBoard === 'frame' && evtOrigin !== 'external') {
			if (nodeId) {
				let { anchor, boardOptions, setTitle } = showPopupFrame(this);
				this.setState({ popupAnchor: anchor, popupBoardOptions: boardOptions, setPopupTitle: setTitle });
			}
			else {
				hidePopupFrame(this);
			}
		}
	}

	handleNavClicked(game, selection, nodeIdProvider, withMove) {
		this.movetextRef.current.focus();
		let nodeId = nodeIdProvider(game, selection ? selection : 'start');
		if (nodeId) {
			this.setState({ selection: nodeId, withMove: withMove });
		}
	}

	handleFlipClicked() {
		this.movetextRef.current.focus();
		this.setState({ withAdditionalFlip: !this.state.withAdditionalFlip });
	}

	handleDownloadClicked() {
		if(this.props.url) {
			window.location.href = this.props.url;
		}
		else {
			let data = new Blob([ this.props.pgn ], { type: 'text/plain' });

			// Allocate a new URL for the current blob.
			this.releaseDynamicURL();
			this.dynamicURL = window.URL.createObjectURL(data);

			// Trigger the download.
			let blobDownloadLink = this.blobDownloadLinkRef.current;
			blobDownloadLink.href = this.dynamicURL;
			blobDownloadLink.click();
		}
	}

	releaseDynamicURL() {
		if (this.dynamicURL) {
			window.URL.revokeObjectURL(this.dynamicURL);
			this.dynamicURL = false;
		}
	}

	/**
	 * @public
	 */
	focus() {
		this.movetextRef.current.focus();
	}
}


Chessgame.propTypes = {
	url: PropTypes.string,
	pgn: PropTypes.string,
	gameIndex: PropTypes.number,
	initialSelection: PropTypes.string, // 'start', 'end', or a node ID (e.g. '3w', '12b', etc...)
	pieceSymbols: PropTypes.oneOfType([
		PropTypes.oneOf([ 'native', 'localized', 'figurines' ]),
		PropTypes.string, // example: 'R,D,T,F,C,P'
	]),
	navigationBoard: PropTypes.oneOf([ 'none', 'frame', 'floatLeft', 'floatRight', 'scrollLeft', 'scrollRight', 'above', 'below' ]),
	navigationBoardOptions: PropTypes.shape({
		flipped: Chessboard.propTypes.flipped,
		squareSize: Chessboard.propTypes.squareSize,
		coordinateVisible: Chessboard.propTypes.coordinateVisible,
		smallScreenLimits: Chessboard.propTypes.smallScreenLimits,
		colorset: Chessboard.propTypes.colorset,
		pieceset: Chessboard.propTypes.pieceset,
		animated: Chessboard.propTypes.animated,
		moveArrowVisible: Chessboard.propTypes.moveArrowVisible,
	}),
	diagramOptions: Movetext.propTypes.diagramOptions,
	withFlipButton: PropTypes.bool,
	withDownloadButton: PropTypes.bool,
};


Chessgame.defaultProps = {
	pgn: '',
	gameIndex: 0,
	initialSelection: false,
	pieceSymbols: 'native',
	navigationBoard: 'none',
	navigationBoardOptions: {},
	diagramOptions: {},
	withFlipButton: true,
	withDownloadButton: true,
};


async function fetchPGN(url) {
	let response = await fetch(url);
	return response.ok ? response.text() : Promise.reject(format(i18n.PGN_DOWNLOAD_ERROR_MESSAGE, url, response.status));
}
