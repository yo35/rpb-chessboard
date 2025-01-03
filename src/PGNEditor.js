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
import './PGNEditor.css';

import PropTypes from 'prop-types';
import React from 'react';
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { withSelect } from '@wordpress/data';
import { Button, PanelBody, PanelRow, RadioControl, TextControl, ToggleControl, __experimentalNumberControl as NumberControl,
    __experimentalRadioGroup as RadioGroup, __experimentalRadio as Radio } from '@wordpress/components';

import { Chessboard, ArrowMarkerIcon } from 'kokopu-react';
import { parsePieceSymbols, flattenPieceSymbols } from './util';
import ChessboardOptionEditor from './ChessboardOptionEditor';

const i18n = RPBChessboard.i18n;
const mainColorset = Chessboard.colorsets().original;


/**
 * Icon of the PGN editor.
 */
export function PGNEditorIcon() {
    const squares = [];
    for (let x = 0; x < 4; ++x) {
        for (let y = 1 - x % 2; y < 4; y += 2) {
            squares.push(<rect x={x * 4} y={y * 4 + 4} width={4} height={4} />);
        }
    }
    for (let i = 0; i < 5; ++i) {
        squares.push(<rect x={i < 1 || i >= 4 ? 0 : 18} y={i * 5 + 1} width={i < 1 || i >= 4 ? 24 : 6} height={2} />);
    }
    return <svg viewBox="0 0 24 24">{squares}</svg>;
}


/**
 * Component to display the URL of an media file.
 */
const AttachmentURLComponent = withSelect((select, props) => {
    const attachment = select('core').getMedia(props.attachmentId);
    return {
        url: attachment ? attachment.source_url : undefined,
    };
})(props => <input className="rpbchessboard-fixMarginBottom rpbchessboard-fixPadding" type="text" readOnly value={props.url ?? ''} />);


/**
 * PGN editor
 */
class PGNEditor extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            sourceType: this.props.attributes.attachmentId >= 0 ? 'url' : 'inline',
            localPgn: this.props.attributes.pgn,
            localAttachementId: this.props.attributes.attachmentId,
            localGameIndex: this.props.attributes.gameIndex,
        };
    }

    handleAttributeChanged(attribute, value) {
        const newAttributes = { ...this.props.attributes };
        newAttributes[attribute] = value;
        this.props.setAttributes(newAttributes);
    }

    handleInitialSelectionCodeChanged(code) {
        const newInitialSelection = code === 'custom' ? '1w' : code;
        this.props.setAttributes({ ...this.props.attributes, initialSelection: newInitialSelection });
    }

    handlePieceSymbolCodeChanged(code, elements) {
        const pieceSymbols = code === 'custom' ? flattenPieceSymbols(elements) : code;
        this.props.setAttributes({ ...this.props.attributes, pieceSymbols: pieceSymbols });
    }

    handlePieceSymbolChanged(piece, value, elements) {
        const newElements = { ...elements };
        newElements[piece] = value;
        this.props.setAttributes({ ...this.props.attributes, pieceSymbols: flattenPieceSymbols(newElements) });
    }

    handleFlipClicked() {
        const flipped = !this.props.attributes.flipped;
        this.props.setAttributes({ ...this.props.attributes, flipped: flipped });
    }

    handleSourceTypeChanged(sourceType) {
        const newAttributes = { ...this.props.attributes };
        newAttributes.pgn = '';
        newAttributes.attachmentId = -1;
        newAttributes.gameIndex = 0;

        switch (sourceType) {

            case 'inline':
                newAttributes.pgn = this.state.localPgn;
                break;

            case 'url':
                newAttributes.attachmentId = this.state.localAttachementId;
                newAttributes.gameIndex = this.state.localGameIndex;
                break;
        }
        this.setState({ sourceType: sourceType });
        this.props.setAttributes(newAttributes);
    }

    handleInlineSourceChanged(pgn) {
        this.setState({ localPgn: pgn });
        this.handleAttributeChanged('pgn', pgn);
    }

    handleUrlSourceChanged(attachmentId) {
        this.setState({ localAttachementId: attachmentId });
        this.handleAttributeChanged('attachmentId', attachmentId);
    }

    handleGameIndexChanged(gameIndex) {
        this.setState({ localGameIndex: gameIndex });
        this.handleAttributeChanged('gameIndex', gameIndex);
    }

    handleUploadClicked() {
        if (!this.mediaFrame) {

            this.mediaFrame = wp.media({
                title: i18n.PGN_EDITOR_MEDIA_FRAME_TITLE,
                button: { text: i18n.PGN_EDITOR_MEDIA_FRAME_BUTTON_LABEL },
                multiple: false,
            });

            this.mediaFrame.on('select', () => {
                const attachment = this.mediaFrame.state().get('selection').first().toJSON();
                this.handleUrlSourceChanged(attachment.id);
            });
        }
        this.mediaFrame.open();
    }


    /**
     * Rendering entry point.
     */
    render() {
        return (
            <div {...this.props.blockProps}>
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
                {this.renderGameOptionPanel()}
                {this.renderPieceSymbolsPanel()}
                {this.renderNavigationBoardPanel()}
                {this.renderDiagramOptionPanel()}
            </InspectorControls>
        );
    }


    /**
     * Game options: flipping, game index selection, etc...
     */
    renderGameOptionPanel() {
        return (
            <PanelBody title={i18n.PGN_EDITOR_PANEL_GAME_OPTIONS}>
                <ToggleControl label={i18n.PGN_EDITOR_CONTROL_FLIP} checked={this.props.attributes.flipped} onChange={() => this.handleFlipClicked()} />
                {this.renderInitialSelectionSelector()}
            </PanelBody>
        );
    }


    /**
     * Selector for the move that is initially selected.
     */
    renderInitialSelectionSelector() {
        const navigationBoard = this.props.attributes.navigationBoard === '' ?
            RPBChessboard.defaultSettings.navigationBoard :
            this.props.attributes.navigationBoard;
        if (navigationBoard === 'none' || navigationBoard === 'frame') {
            return undefined;
        }
        const code = this.props.attributes.initialSelection === 'start' || this.props.attributes.initialSelection === 'end' ?
            this.props.attributes.initialSelection :
            'custom';
        const customSelector = code === 'custom' ?
            (
                <TextControl
                    value={this.props.attributes.initialSelection} onChange={value => this.handleAttributeChanged('initialSelection', value)}
                    help={i18n.PGN_EDITOR_HELP_INITIAL_SELECTION}
                />
            ) :
            undefined;
        return (
            <>
                <RadioControl
                    label={i18n.PGN_EDITOR_CONTROL_INITIAL_SELECTION} selected={code} onChange={value => this.handleInitialSelectionCodeChanged(value)}
                    options={[
                        { label: i18n.PGN_EDITOR_OPTION_START, value: 'start' },
                        { label: i18n.PGN_EDITOR_OPTION_END, value: 'end' },
                        { label: i18n.PGN_EDITOR_OPTION_CUSTOM_MOVE, value: 'custom' },
                    ]}
                />
                {customSelector}
            </>
        );
    }


    /**
     * Piece-symbol customization panel.
     */
    renderPieceSymbolsPanel() {
        const isLocalizationAvailable = [ ...'KQRBNP' ].some(p => i18n.PIECE_SYMBOLS[p] !== p);
        const { code, elements, futureElements } = this.getPieceSymbols(isLocalizationAvailable);

        const options = [];
        options.push({ label: i18n.PGN_EDITOR_USE_DEFAULT, value: '' });
        options.push({ label: i18n.PGN_EDITOR_OPTION_NATIVE, value: 'native' });
        if (isLocalizationAvailable) {
            options.push({ label: i18n.PGN_EDITOR_OPTION_LOCALIZED, value: 'localized' });
        }
        options.push({ label: i18n.PGN_EDITOR_OPTION_FIGURINES, value: 'figurines' });
        options.push({ label: i18n.PGN_EDITOR_OPTION_CUSTOM, value: 'custom' });

        const disabled = code !== 'custom';
        return (
            <PanelBody title={i18n.PGN_EDITOR_PANEL_PIECE_SYMBOLS} initialOpen={false}>
                <PanelRow>
                    <RadioControl selected={code} onChange={value => this.handlePieceSymbolCodeChanged(value, futureElements)} options={options} />
                </PanelRow>
                <PanelRow>
                    <TextControl
                        className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.K} disabled={disabled}
                        onChange={value => this.handlePieceSymbolChanged('K', value, futureElements)}
                    />
                    <TextControl
                        className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.Q} disabled={disabled}
                        onChange={value => this.handlePieceSymbolChanged('Q', value, futureElements)}
                    />
                    <TextControl
                        className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.R} disabled={disabled}
                        onChange={value => this.handlePieceSymbolChanged('R', value, futureElements)}
                    />
                    <TextControl
                        className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.B} disabled={disabled}
                        onChange={value => this.handlePieceSymbolChanged('B', value, futureElements)}
                    />
                    <TextControl
                        className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.N} disabled={disabled}
                        onChange={value => this.handlePieceSymbolChanged('N', value, futureElements)}
                    />
                    <TextControl
                        className="rpbchessboard-pieceSymbolJsField rpbchessboard-fixMarginBottom" value={elements.P} disabled={disabled}
                        onChange={value => this.handlePieceSymbolChanged('P', value, futureElements)}
                    />
                </PanelRow>
            </PanelBody>
        );
    }


    /**
     * Navigation board position & options customization panel.
     */
    renderNavigationBoardPanel() {
        return (
            <PanelBody title={i18n.PGN_EDITOR_PANEL_NAVIGATION_BOARD} initialOpen={false}>
                <RadioControl
                    selected={this.props.attributes.navigationBoard} onChange={value => this.handleAttributeChanged('navigationBoard', value)}
                    options={[
                        { label: i18n.PGN_EDITOR_USE_DEFAULT, value: '' },
                        { label: i18n.PGN_EDITOR_OPTION_NONE, value: 'none' },
                        { label: i18n.PGN_EDITOR_OPTION_FRAME, value: 'frame' },
                        { label: i18n.PGN_EDITOR_OPTION_ABOVE, value: 'above' },
                        { label: i18n.PGN_EDITOR_OPTION_BELOW, value: 'below' },
                        { label: i18n.PGN_EDITOR_OPTION_FLOAT_LEFT, value: 'floatLeft' },
                        { label: i18n.PGN_EDITOR_OPTION_FLOAT_RIGHT, value: 'floatRight' },
                        { label: i18n.PGN_EDITOR_OPTION_SCROLL_LEFT, value: 'scrollLeft' },
                        { label: i18n.PGN_EDITOR_OPTION_SCROLL_RIGHT, value: 'scrollRight' },
                    ]}
                />
                {this.renderNavigationBoardOptionFields()}
            </PanelBody>
        );
    }


    /**
     * Fields for square-size / coordinate-visibility / colorset / pieceset customization for the navigation board, if available.
     */
    renderNavigationBoardOptionFields() {
        const navigationBoard = this.props.attributes.navigationBoard === '' ?
            RPBChessboard.defaultSettings.navigationBoard :
            this.props.attributes.navigationBoard;
        if (navigationBoard === 'none' || navigationBoard === 'frame') {
            return undefined;
        }

        const playButton = (
            <RadioControl
                label={i18n.PGN_EDITOR_CONTROL_PLAY_BUTTON} selected={this.props.attributes.withPlayButton}
                onChange={value => this.handleAttributeChanged('withPlayButton', value)}
                options={[
                    { label: i18n.PGN_EDITOR_USE_DEFAULT, value: '' },
                    { label: i18n.PGN_EDITOR_OPTION_HIDDEN, value: 'false' },
                    { label: i18n.PGN_EDITOR_OPTION_VISIBLE, value: 'true' },
                ]}
            />
        );
        const flipButton = (
            <RadioControl
                label={i18n.PGN_EDITOR_CONTROL_FLIP_BUTTON} selected={this.props.attributes.withFlipButton}
                onChange={value => this.handleAttributeChanged('withFlipButton', value)}
                options={[
                    { label: i18n.PGN_EDITOR_USE_DEFAULT, value: '' },
                    { label: i18n.PGN_EDITOR_OPTION_HIDDEN, value: 'false' },
                    { label: i18n.PGN_EDITOR_OPTION_VISIBLE, value: 'true' },
                ]}
            />
        );
        const downloadButton = (
            <RadioControl
                label={i18n.PGN_EDITOR_CONTROL_DOWNLOAD_BUTTON} selected={this.props.attributes.withDownloadButton}
                onChange={value => this.handleAttributeChanged('withDownloadButton', value)}
                options={[
                    { label: i18n.PGN_EDITOR_USE_DEFAULT, value: '' },
                    { label: i18n.PGN_EDITOR_OPTION_HIDDEN, value: 'false' },
                    { label: i18n.PGN_EDITOR_OPTION_VISIBLE, value: 'true' },
                ]}
            />
        );
        return (
            <>
                <ChessboardOptionEditor
                    defaultSquareSize={RPBChessboard.defaultSettings.nboSquareSize}
                    squareSize={this.props.attributes.nboSquareSize}
                    coordinateVisible={this.props.attributes.nboCoordinateVisible}
                    turnVisible={this.props.attributes.nboTurnVisible}
                    colorset={this.props.attributes.nboColorset}
                    pieceset={this.props.attributes.nboPieceset}
                    onSquareSizeChanged={value => this.handleAttributeChanged('nboSquareSize', value)}
                    onCoordinateVisibleChanged={value => this.handleAttributeChanged('nboCoordinateVisible', value)}
                    onTurnVisibleChanged={value => this.handleAttributeChanged('nboTurnVisible', value)}
                    onColorsetChanged={value => this.handleAttributeChanged('nboColorset', value)}
                    onPiecesetChanged={value => this.handleAttributeChanged('nboPieceset', value)}
                />
                <RadioControl
                    label={i18n.PGN_EDITOR_CONTROL_ANIMATED} selected={this.props.attributes.nboAnimated}
                    onChange={value => this.handleAttributeChanged('nboAnimated', value)}
                    options={[
                        { label: i18n.PGN_EDITOR_USE_DEFAULT, value: '' },
                        { label: i18n.PGN_EDITOR_OPTION_DISABLED, value: 'false' },
                        { label: i18n.PGN_EDITOR_OPTION_ENABLED, value: 'true' },
                    ]}
                />
                <RadioControl
                    label={i18n.PGN_EDITOR_CONTROL_MOVE_ARROW} selected={this.props.attributes.nboMoveArrowVisible}
                    onChange={value => this.handleAttributeChanged('nboMoveArrowVisible', value)}
                    options={[
                        { label: i18n.PGN_EDITOR_USE_DEFAULT, value: '' },
                        { label: i18n.PGN_EDITOR_OPTION_HIDDEN, value: 'false' },
                        { label: i18n.PGN_EDITOR_OPTION_VISIBLE, value: 'true' },
                    ]}
                />
                {this.renderMoveArrowColorFields()}
                {playButton}
                {flipButton}
                {downloadButton}
            </>
        );
    }


    /**
     * Fields for move arrow color customization.
     */
    renderMoveArrowColorFields() {
        const moveArrowVisible = this.props.attributes.nboMoveArrowVisible === '' ?
            RPBChessboard.defaultSettings.moveArrowVisible :
            this.props.attributes.nboMoveArrowVisible === 'true';
        if (!moveArrowVisible) {
            return undefined;
        }

        const isDefaultMoveArrowColor = this.props.attributes.nboMoveArrowColor === '';
        const useDefaultControl = (
            <ToggleControl
                label={i18n.PGN_EDITOR_USE_DEFAULT_MOVE_ARROW_COLOR} checked={isDefaultMoveArrowColor}
                onChange={() => this.handleAttributeChanged('nboMoveArrowColor', isDefaultMoveArrowColor ? RPBChessboard.defaultSettings.moveArrowColor : '')}
            />
        );
        if (isDefaultMoveArrowColor) {
            return useDefaultControl;
        }

        function ArrowColorButton({ color }) {
            return <Radio value={color}><ArrowMarkerIcon color={mainColorset['c' + color]} size={24} /></Radio>;
        }
        return (
            <>
                {useDefaultControl}
                <RadioGroup
                    className="rpbchessboard-restoreMarginBottom" checked={this.props.attributes.nboMoveArrowColor}
                    onChange={newValue => this.handleAttributeChanged('nboMoveArrowColor', newValue)}
                >
                    <ArrowColorButton color="b" />
                    <ArrowColorButton color="g" />
                    <ArrowColorButton color="r" />
                    <ArrowColorButton color="y" />
                </RadioGroup>
            </>
        );
    }


    /**
     * Diagram options customization panel.
     */
    renderDiagramOptionPanel() {
        return (
            <PanelBody title={i18n.PGN_EDITOR_PANEL_DIAGRAM_OPTIONS} initialOpen={false}>
                <ChessboardOptionEditor
                    defaultSquareSize={RPBChessboard.defaultSettings.idoSquareSize}
                    squareSize={this.props.attributes.idoSquareSize}
                    coordinateVisible={this.props.attributes.idoCoordinateVisible}
                    turnVisible={this.props.attributes.idoTurnVisible}
                    colorset={this.props.attributes.idoColorset}
                    pieceset={this.props.attributes.idoPieceset}
                    onSquareSizeChanged={value => this.handleAttributeChanged('idoSquareSize', value)}
                    onCoordinateVisibleChanged={value => this.handleAttributeChanged('idoCoordinateVisible', value)}
                    onTurnVisibleChanged={value => this.handleAttributeChanged('idoTurnVisible', value)}
                    onColorsetChanged={value => this.handleAttributeChanged('idoColorset', value)}
                    onPiecesetChanged={value => this.handleAttributeChanged('idoPieceset', value)}
                />
            </PanelBody>
        );
    }


    /**
     * Render the text editor.
     */
    renderBlockContent() {
        return (
            <div className="components-placeholder rpbchessboard-fixPlaceholder">
                {this.renderSourceTypeSelector()}
                {this.renderTextAreaIfSourceIsInline()}
                {this.renderMediaSelectorIfSourceIsURL()}
            </div>
        );
    }

    renderSourceTypeSelector() {
        return (
            <RadioGroup
                className="rpbchessboard-pgnSourceTypeSelector" checked={this.state.sourceType} onChange={newValue => this.handleSourceTypeChanged(newValue)}
            >
                <Radio value="inline">PGN text</Radio>
                <Radio value="url">PGN file</Radio>
            </RadioGroup>
        );
    }

    renderTextAreaIfSourceIsInline() {
        if (this.state.sourceType !== 'inline') {
            return undefined;
        }
        return (
            <textarea
                className="block-editor-plain-text blocks-shortcode__textarea rpbchessboard-fixMarginBottom rpbchessboard-pgnTextarea"
                value={this.props.attributes.pgn}
                onChange={evt => this.handleInlineSourceChanged(evt.target.value)}
            />
        );
    }

    renderMediaSelectorIfSourceIsURL() {
        if (this.state.sourceType !== 'url') {
            return undefined;
        }
        const attachmentId = this.props.attributes.attachmentId;
        return (
            <>
                <div className="rpbchessboard-pgnFileSelector">
                    <Button variant="primary" text={i18n.PGN_EDITOR_UPLOAD_BUTTON_LABEL} onClick={() => this.handleUploadClicked()} />
                    {attachmentId >= 0 ? <AttachmentURLComponent attachmentId={attachmentId} /> : undefined}
                </div>
                <NumberControl
                    label={i18n.PGN_EDITOR_CONTROL_GAME_INDEX} labelPosition="side" min={0} value={this.props.attributes.gameIndex}
                    onChange={value => this.handleGameIndexChanged(Number(value))}
                />
            </>
        );
    }

    getPieceSymbols(isLocalizationAvailable) {
        const isDefault = this.props.attributes.pieceSymbols === '';
        const code = isDefault ? RPBChessboard.defaultSettings.pieceSymbols : this.props.attributes.pieceSymbols;
        const customPieceSymbols = parsePieceSymbols(code);
        if (customPieceSymbols) {
            return { code: isDefault ? '' : 'custom', elements: customPieceSymbols, futureElements: customPieceSymbols };
        }
        else if (code === 'figurines') {
            return {
                code: isDefault ? '' : 'figurines',
                elements: { K: '-', Q: '-', R: '-', B: '-', N: '-', P: '-' },
                futureElements: { K: 'K', Q: 'Q', R: 'R', B: 'B', N: 'N', P: 'P' },
            };
        }
        else if (code === 'localized' && isLocalizationAvailable) {
            const elements = {
                K: i18n.PIECE_SYMBOLS.K,
                Q: i18n.PIECE_SYMBOLS.Q,
                R: i18n.PIECE_SYMBOLS.R,
                B: i18n.PIECE_SYMBOLS.B,
                N: i18n.PIECE_SYMBOLS.N,
                P: i18n.PIECE_SYMBOLS.P,
            };
            return { code: isDefault ? '' : 'localized', elements: elements, futureElements: elements };
        }
        else {
            const elements = { K: 'K', Q: 'Q', R: 'R', B: 'B', N: 'N', P: 'P' };
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
export function registerPGNBlock() {
    registerBlockType('rpb-chessboard/pgn', {
        apiVersion: 2,
        title: i18n.PGN_EDITOR_TITLE,
        icon: <PGNEditorIcon />,
        category: 'media',
        attributes: {
            pgn: {
                type: 'string',
                default: '',
            },
            attachmentId: {
                type: 'number',
                default: -1,
            },
            gameIndex: {
                type: 'number',
                default: 0,
            },
            flipped: {
                type: 'boolean',
                default: false,
            },
            initialSelection: {
                type: 'string',
                default: 'start',
            },
            pieceSymbols: {
                type: 'string',
                default: '',
            },
            navigationBoard: {
                type: 'string',
                default: '',
            },
            nboSquareSize: {
                type: 'number',
                default: 0,
            },
            nboCoordinateVisible: {
                type: 'string',
                default: '',
            },
            nboTurnVisible: {
                type: 'string',
                default: '',
            },
            nboColorset: {
                type: 'string',
                default: '',
            },
            nboPieceset: {
                type: 'string',
                default: '',
            },
            nboAnimated: {
                type: 'string',
                default: '',
            },
            nboMoveArrowVisible: {
                type: 'string',
                default: '',
            },
            nboMoveArrowColor: {
                type: 'string',
                default: '',
            },
            idoSquareSize: {
                type: 'number',
                default: 0,
            },
            idoCoordinateVisible: {
                type: 'string',
                default: '',
            },
            idoTurnVisible: {
                type: 'string',
                default: '',
            },
            idoColorset: {
                type: 'string',
                default: '',
            },
            idoPieceset: {
                type: 'string',
                default: '',
            },
            withPlayButton: {
                type: 'string',
                default: '',
            },
            withFlipButton: {
                type: 'string',
                default: '',
            },
            withDownloadButton: {
                type: 'string',
                default: '',
            },
        },
        edit: ({ attributes, setAttributes }) => {
            const blockProps = useBlockProps();
            return <PGNEditor blockProps={blockProps} attributes={attributes} setAttributes={setAttributes} />;
        },
    });
}
