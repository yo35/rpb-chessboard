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

import PropTypes from 'prop-types';
import React from 'react';
import { ComboboxControl, RadioControl, RangeControl, ToggleControl } from '@wordpress/components';

const i18n = RPBChessboard.i18n;


/**
 * Combox-box to select the colorset or the pieceset.
 */
function SetCodeControl({ label, value, available, onChange }) {
    const options = [ { value: '', label: '<' + i18n.FEN_EDITOR_USE_DEFAULT + '>' } ];
    Object.keys(available).sort().forEach(key => options.push({ value: key, label: available[key] }));
    return <ComboboxControl label={label} value={value} options={options} onChange={onChange} />;
}

SetCodeControl.propTypes = {
    label: PropTypes.string.isRequired,
    value: PropTypes.string.isRequired,
    available: PropTypes.arrayOf(PropTypes.object).isRequired,
    onChange: PropTypes.func.isRequired,
};


/**
 * Components to customize the square size, the coordinate visibility, and the colorset/pieceset.
 */
export default function ChessboardOptionEditor(props) {

    // Square-size controls.
    const isDefaultSize = props.squareSize === 0;
    const squareSizeControl = isDefaultSize ?
        undefined :
        (
            <RangeControl
                label={i18n.FEN_EDITOR_CONTROL_SQUARE_SIZE} value={props.squareSize}
                min={RPBChessboard.availableSquareSize.min} max={RPBChessboard.availableSquareSize.max} step={1}
                onChange={props.onSquareSizeChanged}
            />
        );

    return (
        <>
            <ToggleControl
                label={i18n.FEN_EDITOR_CONTROL_USE_DEFAULT_SIZE} checked={isDefaultSize}
                onChange={() => props.onSquareSizeChanged(isDefaultSize ? props.defaultSquareSize : 0)}
            />
            {squareSizeControl}
            <RadioControl
                label={i18n.FEN_EDITOR_CONTROL_COORDINATES} selected={props.coordinateVisible} onChange={props.onCoordinateVisibleChanged}
                options={[
                    { label: i18n.FEN_EDITOR_USE_DEFAULT, value: '' },
                    { label: i18n.FEN_EDITOR_OPTION_HIDDEN, value: 'false' },
                    { label: i18n.FEN_EDITOR_OPTION_VISIBLE, value: 'true' },
                ]}
            />
            <RadioControl
                label={i18n.FEN_EDITOR_CONTROL_TURN_FLAG} selected={props.turnVisible} onChange={props.onTurnVisibleChanged}
                options={[
                    { label: i18n.FEN_EDITOR_USE_DEFAULT, value: '' },
                    { label: i18n.FEN_EDITOR_OPTION_HIDDEN, value: 'false' },
                    { label: i18n.FEN_EDITOR_OPTION_VISIBLE, value: 'true' },
                ]}
            />
            <SetCodeControl
                label={i18n.FEN_EDITOR_CONTROL_COLORSET} value={props.colorset} available={RPBChessboard.availableColorsets} onChange={props.onColorsetChanged}
            />
            <SetCodeControl
                label={i18n.FEN_EDITOR_CONTROL_PIECESET} value={props.pieceset} available={RPBChessboard.availablePiecesets} onChange={props.onPiecesetChanged}
            />
        </>
    );
}

ChessboardOptionEditor.propTypes = {
    defaultSquareSize: PropTypes.number.isRequired,
    squareSize: PropTypes.number.isRequired, // 0 means "use default"
    coordinateVisible: PropTypes.string.isRequired, // not a boolean since it can be '' (meaning "use default")
    turnVisible: PropTypes.string.isRequired, // not a boolean since it can be '' (meaning "use default")
    colorset: PropTypes.string.isRequired,
    pieceset: PropTypes.string.isRequired,
    onSquareSizeChanged: PropTypes.func.isRequired,
    onCoordinateVisibleChanged: PropTypes.func.isRequired,
    onTurnVisibleChanged: PropTypes.func.isRequired,
    onColorsetChanged: PropTypes.func.isRequired,
    onPiecesetChanged: PropTypes.func.isRequired,
};
