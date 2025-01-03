/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2025  Yoann Le Montagner <yo35 -at- melix.net>       *
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
import './index.css';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { Chessboard, NavigationBoard, ArrowMarkerIcon, i18n as kokopuReactI18n } from 'kokopu-react';

import Chessgame from './Chessgame';
import { DOWNLOAD_PATH } from './downloadPath';
import { FENEditorIcon, registerFENBlock } from './FENEditor';
import { PGNEditorIcon, registerPGNBlock } from './PGNEditor';


function initializePlugin() {

    // Internationalization
    kokopuReactI18n.INVALID_FEN_ERROR_TITLE = RPBChessboard.i18n.FEN_PARSING_ERROR_TITLE;
    kokopuReactI18n.ANNOTATED_BY = RPBChessboard.i18n.PGN_ANNOTATED_BY;
    kokopuReactI18n.LINE = RPBChessboard.i18n.PGN_LINE_REF;
    kokopuReactI18n.PIECE_SYMBOLS = {
        K: RPBChessboard.i18n.PIECE_SYMBOLS.K,
        Q: RPBChessboard.i18n.PIECE_SYMBOLS.Q,
        R: RPBChessboard.i18n.PIECE_SYMBOLS.R,
        B: RPBChessboard.i18n.PIECE_SYMBOLS.B,
        N: RPBChessboard.i18n.PIECE_SYMBOLS.N,
        P: RPBChessboard.i18n.PIECE_SYMBOLS.P,
    };
    kokopuReactI18n.TOOLTIP_GO_FIRST = RPBChessboard.i18n.PGN_TOOLTIP_GO_FIRST;
    kokopuReactI18n.TOOLTIP_GO_PREVIOUS = RPBChessboard.i18n.PGN_TOOLTIP_GO_PREVIOUS;
    kokopuReactI18n.TOOLTIP_GO_NEXT = RPBChessboard.i18n.PGN_TOOLTIP_GO_NEXT;
    kokopuReactI18n.TOOLTIP_GO_LAST = RPBChessboard.i18n.PGN_TOOLTIP_GO_LAST;
    kokopuReactI18n.TOOLTIP_PLAY_STOP = RPBChessboard.i18n.PGN_TOOLTIP_PLAY_STOP;
    kokopuReactI18n.TOOLTIP_FLIP = RPBChessboard.i18n.PGN_TOOLTIP_FLIP;

    // Square size bounds
    RPBChessboard.availableSquareSize = {
        min: Chessboard.minSquareSize(),
        max: Chessboard.maxSquareSize(),
    };

    // Theming
    Object.assign(Chessboard.colorsets(), RPBChessboard.customColorsets);
    Object.assign(Chessboard.piecesets(), RPBChessboard.customPiecesets);
    RPBChessboard.colorsetData = Chessboard.colorsets();
    RPBChessboard.piecesetData = Chessboard.piecesets();

    // Special colorsets and piecesets for theming edition
    RPBChessboard.editColorset = Chessboard.colorsets()._edit_ = {};
    RPBChessboard.editPieceset = Chessboard.piecesets()._edit_ = {};

    // Block registration
    // WARNING: only at the end, as these operations may fail in case of improperly configured website
    // (in particular in case of deferred/async JS loading).
    registerFENBlock();
    registerPGNBlock();
}


// Icon rendering (to be used in the admin pages)
RPBChessboard.renderAdminFENIcon = function (targetElement) { createRoot(targetElement).render(<FENEditorIcon />); };
RPBChessboard.renderAdminPGNIcon = function (targetElement) { createRoot(targetElement).render(<PGNEditorIcon />); };
RPBChessboard.renderAdminArrowMarkerIcon = function (targetElement, widgetArgs) {
    const widget = <ArrowMarkerIcon {...widgetArgs} />;
    createRoot(targetElement).render(widget);
};


// Chessboard rendering function (to be used in the admin pages)
RPBChessboard.renderAdminChessboard = function (targetElement, widgetArgs) {
    const widget = <Chessboard {...widgetArgs} />;
    createRoot(targetElement).render(widget);
};


// NavigationChessboard rendering function (to be used in the admin pages)
RPBChessboard.renderAdminNavigationBoard = function (targetElement, widgetArgs) {
    const additionalButtons = widgetArgs.downloadButtonVisible ? [ { iconPath: DOWNLOAD_PATH, tooltip: RPBChessboard.i18n.PGN_TOOLTIP_DOWNLOAD } ] : [];
    const widget = <NavigationBoard {...widgetArgs} additionalButtons={additionalButtons} />;
    createRoot(targetElement).render(widget);
};


// Chessboard rendering function
RPBChessboard.renderFEN = function (targetElementId, widgetArgs) {
    const widget = (
        <Chessboard
            position={widgetArgs.position}
            squareMarkers={widgetArgs.squareMarkers}
            arrowMarkers={widgetArgs.arrowMarkers}
            textMarkers={widgetArgs.textMarkers}
            flipped={widgetArgs.flipped}
            squareSize={widgetArgs.squareSize}
            coordinateVisible={widgetArgs.coordinateVisible}
            turnVisible={widgetArgs.turnVisible}
            colorset={widgetArgs.colorset}
            pieceset={widgetArgs.pieceset}
            smallScreenLimits={RPBChessboard.smallScreenLimits}
        />
    );
    createRoot(document.getElementById(targetElementId)).render(widget);
};


// Chessgame rendering function
RPBChessboard.renderPGN = function (targetElementId, widgetArgs) {
    const diagramOptions = {
        flipped: widgetArgs.flipped,
        squareSize: widgetArgs.idoSquareSize,
        coordinateVisible: widgetArgs.idoCoordinateVisible,
        turnVisible: widgetArgs.idoTurnVisible,
        colorset: widgetArgs.idoColorset,
        pieceset: widgetArgs.idoPieceset,
        smallScreenLimits: RPBChessboard.smallScreenLimits,
    };
    const navigationBoardOptions = {
        flipped: widgetArgs.flipped,
        squareSize: widgetArgs.nboSquareSize,
        coordinateVisible: widgetArgs.nboCoordinateVisible,
        turnVisible: widgetArgs.nboTurnVisible,
        colorset: widgetArgs.nboColorset,
        pieceset: widgetArgs.nboPieceset,
        animated: widgetArgs.nboAnimated,
        moveArrowVisible: widgetArgs.nboMoveArrowVisible,
        moveArrowColor: widgetArgs.nboMoveArrowColor,
        smallScreenLimits: RPBChessboard.smallScreenLimits,
        playButtonVisible: widgetArgs.withPlayButton,
        flipButtonVisible: widgetArgs.withFlipButton,
        downloadButtonVisible: widgetArgs.withDownloadButton,
    };
    const widget = (
        <Chessgame
            url={widgetArgs.url}
            pgn={widgetArgs.pgn}
            gameIndex={widgetArgs.gameIndex}
            initialSelection={widgetArgs.initialSelection}
            pieceSymbols={widgetArgs.pieceSymbols}
            diagramOptions={diagramOptions}
            navigationBoardOptions={navigationBoardOptions}
            navigationBoard={widgetArgs.navigationBoard}
        />
    );
    createRoot(document.getElementById(targetElementId)).render(widget);
};


try {
    initializePlugin();
}
catch (e) {
    /* eslint-disable no-console */
    console.error('Error while initializing RPB Chessboard: the plugin may not work properly.', e);
    /* eslint-enable */
}
