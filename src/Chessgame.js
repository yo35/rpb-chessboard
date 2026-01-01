/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2026  Yoann Le Montagner <yo35 -at- melix.net>       *
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
import { exception, pgnRead, Variation } from 'kokopu';
import { ErrorBox, Movetext, NavigationBoard, formatMove } from 'kokopu-react';

import { DOWNLOAD_PATH } from './downloadPath';
import { showPopupFrame, hidePopupFrame } from './NavigationFrame';
import { format, parsePieceSymbols } from './util';

const i18n = RPBChessboard.i18n;


/**
 * Chessgame widget (with its navigation board).
 */
export default class Chessgame extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            selection: isNavigationBoardWithinPageMode(this.props.navigationBoard) ? this.props.initialSelection : false,
            isPlaying: false,
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

        const info = this.parseGame(pgn);
        if (!info.valid) {
            return (
                <ErrorBox
                    title={i18n.PGN_PARSING_ERROR_TITLE} message={info.message} text={info.pgn} errorIndex={info.errorIndex} lineNumber={info.lineNumber}
                />
            );
        }
        const nodeId = this.getNodeId(info.game);

        if (this.props.navigationBoard === 'above') {
            return (
                <div>
                    {this.renderNavigationBoard(info.game, nodeId, this.props.navigationBoardOptions)}
                    {this.renderMovetext(info.game, nodeId, true)}
                </div>
            );
        }
        else if (this.props.navigationBoard === 'below') {
            return (
                <div>
                    {this.renderMovetext(info.game, nodeId, true)}
                    {this.renderNavigationBoard(info.game, nodeId, this.props.navigationBoardOptions)}
                </div>
            );
        }
        else if (this.props.navigationBoard === 'floatLeft' || this.props.navigationBoard === 'floatRight') {
            return (
                <div className={this.props.navigationBoard === 'floatLeft' ? 'rpbchessboard-indentWithFloatLeft' : ''}>
                    {this.renderNavigationBoard(info.game, nodeId, this.props.navigationBoardOptions)}
                    {this.renderMovetext(info.game, nodeId, true)}
                    <div className="rpbchessboard-clearFloat"></div>
                </div>
            );
        }
        else if (this.props.navigationBoard === 'scrollLeft' || this.props.navigationBoard === 'scrollRight') {
            const boardOptions = this.props.navigationBoardOptions;
            const { height } = NavigationBoard.size(boardOptions);
            return (
                <div className={'rpbchessboard-scrollBox-' + this.props.navigationBoard}>
                    {this.renderNavigationBoard(info.game, nodeId, boardOptions)}
                    <div className="rpbchessboard-scrollArea" style={{ 'max-height': height }}>{this.renderMovetext(info.game, nodeId, true)}</div>
                </div>
            );
        }
        else if (this.props.navigationBoard === 'frame') {
            return <div>{this.renderMovetext(info.game, nodeId, true)}{this.renderNavigationBoardInPopup(info.game, nodeId)}</div>;
        }
        else {
            return <div>{this.renderMovetext(info.game, nodeId, false)}</div>;
        }
    }

    renderMovetext(game, nodeId, withNavigationBoard) {
        return (
            <Movetext
                ref={this.movetextRef}
                game={game}
                selection={nodeId}
                interactionMode={withNavigationBoard ? 'selectMove' : undefined}
                onMoveSelected={(newNodeId, evtOrigin) => this.handleMoveSelected(newNodeId, evtOrigin)}
                pieceSymbols={this.getPieceSymbols()}
                diagramOptions={this.props.diagramOptions}
            />
        );
    }

    renderNavigationBoardInPopup(game, nodeId) {
        if (nodeId === undefined || !this.state.popupAnchor) {
            return undefined;
        }
        return ReactDOM.createPortal(this.renderNavigationBoard(game, nodeId, this.state.popupBoardOptions, this.state.setPopupTitle), this.state.popupAnchor);
    }

    renderNavigationBoard(game, nodeId, navigationBoardOptions, setTitle) {
        if (setTitle) {
            setTitle(this.getPopupTitle(game, nodeId));
        }
        const classNames = [ 'rpbchessboard-navigationBoard', 'rpbchessboard-navigationBoard-' + this.props.navigationBoard ];
        const additionalButtons = [];
        if (navigationBoardOptions.downloadButtonVisible) {
            additionalButtons.push({
                iconPath: DOWNLOAD_PATH,
                tooltip: i18n.PGN_TOOLTIP_DOWNLOAD,
                onClick: () => this.handleDownloadClicked(),
            });
        }
        return (
            <div className={classNames.join(' ')}>
                <NavigationBoard
                    game={game}
                    nodeId={nodeId}
                    flipped={this.props.navigationBoardOptions.flipped ^ this.state.withAdditionalFlip}
                    isPlaying={this.state.isPlaying}
                    squareSize={navigationBoardOptions.squareSize}
                    coordinateVisible={navigationBoardOptions.coordinateVisible}
                    turnVisible={navigationBoardOptions.turnVisible}
                    colorset={navigationBoardOptions.colorset}
                    pieceset={navigationBoardOptions.pieceset}
                    smallScreenLimits={navigationBoardOptions.smallScreenLimits}
                    animated={navigationBoardOptions.animated}
                    moveArrowVisible={navigationBoardOptions.moveArrowVisible}
                    moveArrowColor={navigationBoardOptions.moveArrowColor}
                    playButtonVisible={navigationBoardOptions.playButtonVisible}
                    flipButtonVisible={navigationBoardOptions.flipButtonVisible}
                    onNodeIdChanged={newNodeId => this.handleNavClicked(newNodeId)}
                    onIsPlayingChanged={newIsPlaying => this.handleIsPlayingClicked(newIsPlaying)}
                    onFlippedChanged={() => this.handleFlipClicked()}
                    additionalButtons={additionalButtons}
                />
                {this.renderDownloadLink(navigationBoardOptions.downloadButtonVisible)}
            </div>
        );
    }

    renderDownloadLink(downloadButtonVisible) {
        if (!downloadButtonVisible) {
            return undefined;
        }
        return <a ref={this.blobDownloadLinkRef} className="rpbchessboard-blobDownloadLink" href="#" download="game.pgn" />;
    }

    getPopupTitle(game, nodeId) {
        if (nodeId === 'start') {
            return i18n.PGN_INITIAL_POSITION;
        }
        else {
            const node = game.findById(nodeId);
            const notation = node.fullMoveNumber() + (node.moveColor() === 'w' ? '.' : '\u2026') + node.notation();
            return formatMove(this.getPieceSymbols(), notation);
        }
    }

    getPieceSymbols() {
        const pieceSymbols = parsePieceSymbols(this.props.pieceSymbols);
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
            const result = pgnRead(pgn, this.props.gameIndex);
            return { valid: true, game: result };
        }
        catch (e) {
            if (e instanceof exception.InvalidPGN) {
                return { valid: false, message: e.message, pgn: e.pgn, errorIndex: e.index, lineNumber: e.lineNumber };
            }
            else {
                throw e;
            }
        }
    }

    getNodeId(game) {
        let node = this.state.selection ? game.findById(this.state.selection) : undefined;

        // If the selection points at a variation, pick the parent node instead.
        if (node instanceof Variation && node.parentNode() !== undefined) {
            node = node.parentNode();
        }

        // The final validation depends on whether the navigation board mode.
        if (isNavigationBoardWithinPageMode(this.props.navigationBoard)) {
            return node === undefined ? 'start' : node.id();
        }
        else if (this.props.navigationBoard === 'frame') {
            return node === undefined ? undefined : node.id();
        }
        else {
            return undefined;
        }
    }

    handleMoveSelected(nodeId, evtOrigin) {
        if (nodeId) {
            this.setState({ selection: nodeId });
        }
        else if (this.props.navigationBoard === 'frame') {
            this.setState({ selection: false });
        }
        if (this.props.navigationBoard === 'frame' && evtOrigin !== 'external') { // 'external' correspond to an owner switch in the popup.
            if (nodeId) {
                const { anchor, boardOptions, setTitle } = showPopupFrame(this);
                this.setState({ popupAnchor: anchor, popupBoardOptions: boardOptions, setPopupTitle: setTitle });
            }
            else {
                hidePopupFrame(this);
            }
        }
    }

    handleNavClicked(nodeId) {
        this.movetextRef.current.focus();
        this.setState({ selection: nodeId });
    }

    handleIsPlayingClicked(isPlaying) {
        this.movetextRef.current.focus();
        this.setState({ isPlaying: isPlaying });
    }

    handleFlipClicked() {
        this.movetextRef.current.focus();
        this.setState({ withAdditionalFlip: !this.state.withAdditionalFlip });
    }

    handleDownloadClicked() {
        if (this.props.url) {
            window.location.href = this.props.url;
        }
        else {
            const data = new Blob([ this.props.pgn ], { type: 'text/plain' });

            // Allocate a new URL for the current blob.
            this.releaseDynamicURL();
            this.dynamicURL = window.URL.createObjectURL(data);

            // Trigger the download.
            const blobDownloadLink = this.blobDownloadLinkRef.current;
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
        flipped: PropTypes.bool,
        squareSize: PropTypes.number,
        coordinateVisible: PropTypes.bool,
        turnVisible: PropTypes.bool,
        smallScreenLimits: PropTypes.arrayOf(PropTypes.shape({
            width: PropTypes.number.isRequired,
            squareSize: PropTypes.number,
            coordinateVisible: PropTypes.bool,
        })),
        colorset: PropTypes.string,
        pieceset: PropTypes.string,
        animated: PropTypes.bool,
        moveArrowVisible: PropTypes.bool,
        flipButtonVisible: PropTypes.bool,
        downloadButtonVisible: PropTypes.bool,
    }),
    diagramOptions: PropTypes.shape({
        flipped: PropTypes.bool,
        squareSize: PropTypes.number,
        coordinateVisible: PropTypes.bool,
        turnVisible: PropTypes.bool,
        smallScreenLimits: PropTypes.arrayOf(PropTypes.shape({
            width: PropTypes.number.isRequired,
            squareSize: PropTypes.number,
            coordinateVisible: PropTypes.bool,
        })),
        colorset: PropTypes.string,
        pieceset: PropTypes.string,
    }),
};


Chessgame.defaultProps = {
    pgn: '',
    gameIndex: 0,
    initialSelection: false,
    pieceSymbols: 'native',
    navigationBoard: 'none',
    navigationBoardOptions: {},
    diagramOptions: {},
};


async function fetchPGN(url) {
    const response = await fetch(url);
    return response.ok ? response.text() : Promise.reject(format(i18n.PGN_DOWNLOAD_ERROR_MESSAGE, url, response.status));
}


function isNavigationBoardWithinPageMode(value) {
    return value === 'above' || value === 'below' || value === 'floatLeft' || value === 'floatRight' || value === 'scrollLeft' || value === 'scrollRight';
}
