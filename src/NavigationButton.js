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
import './NavigationButton.css';

import PropTypes from 'prop-types';
import React from 'react';

const THICKNESS = 2;
const CHEVRON_SIZE = 7;
const ARROW_SIZE = 5;
const ARROW_LENGTH = 16;
const DOWNLOAD_WIDTH = 16;


/**
 *      * C
 *     / \
 *  B *   \
 *     \   \
 *      \   \
 *     A *   * D
 *      /   /
 *     /   /
 *  F *   /
 *     \ /
 *      * E
 *
 * (x,y) = middle of [AD]
 */
function chevronPath(x, y, direction) {
	let xA = x - direction * THICKNESS * Math.sqrt(2) / 2;
	let xB = x - direction * (CHEVRON_SIZE + THICKNESS / Math.sqrt(2) / 2);
	let xC = x - direction * (CHEVRON_SIZE - THICKNESS / Math.sqrt(2) / 2);
	let xD = x + direction * THICKNESS * Math.sqrt(2) / 2;
	let yB = y - direction * (CHEVRON_SIZE - THICKNESS / Math.sqrt(2) / 2);
	let yC = y - direction * (CHEVRON_SIZE + THICKNESS / Math.sqrt(2) / 2);
	let yE = y + direction * (CHEVRON_SIZE + THICKNESS / Math.sqrt(2) / 2);
	let yF = y + direction * (CHEVRON_SIZE - THICKNESS / Math.sqrt(2) / 2);
	return `M ${xA} ${y} L ${xB} ${yB} L ${xC} ${yC} L ${xD} ${y} L ${xC} ${yE} L ${xB} ${yF} Z`;
}


/**
 *      D +---+ E
 *        |   |
 *        |   |
 *        |   |
 *   B *  |   |  * G
 *    / \ |   | / \
 * A *   \|   |/   * H
 *    \   *   *   /
 *     \  C   F  /
 *      \   O   /
 *       \  *  /
 *        \   /
 *         \ /
 *          *
 *        (x,y)
 */
function arrowPath(x, y, direction) {
	let xA = x - direction * (ARROW_SIZE + THICKNESS / Math.sqrt(2) / 2);
	let xB = x - direction * (ARROW_SIZE - THICKNESS / Math.sqrt(2) / 2);
	let xC = x - direction * THICKNESS / 2;
	let xF = x + direction * THICKNESS / 2;
	let xG = x + direction * (ARROW_SIZE - THICKNESS / Math.sqrt(2) / 2);
	let xH = x + direction * (ARROW_SIZE + THICKNESS / Math.sqrt(2) / 2);
	let y0 = y - direction * THICKNESS * Math.sqrt(2) / 2;
	let yA = y0 - direction * (ARROW_SIZE - THICKNESS / Math.sqrt(2) / 2);
	let yB = y0 - direction * (ARROW_SIZE + THICKNESS / Math.sqrt(2) / 2);
	let yC = yB + (xC - xB);
	let yD = y - direction * ARROW_LENGTH;
	return `M ${x} ${y} L ${xA} ${yA} L ${xB} ${yB} L ${xC} ${yC} V ${yD} H ${xF} V ${yC} L ${xG} ${yB} L ${xH} ${yA} Z`;
}


function downloadPath(x, y) {
	let bottom = `M ${x - DOWNLOAD_WIDTH / 2} ${y} H ${x + DOWNLOAD_WIDTH / 2} V ${y + THICKNESS} H ${x - DOWNLOAD_WIDTH / 2} Z`;
	return arrowPath(x, y, 1) + ' ' + bottom;
}


function innerPath(type) {
	switch(type) {
		case 'first':
			return chevronPath(14, 16, -1) + ' ' + chevronPath(10, 16, -1);
		case 'previous':
			return chevronPath(12, 16, -1);
		case 'next':
			return chevronPath(20, 16, 1);
		case 'last':
			return chevronPath(18, 16, 1) + ' ' + chevronPath(22, 16, 1);
		case 'flip':
			return arrowPath(11, 25, 1) + ' ' + arrowPath(21, 7, -1);
		case 'download':
			return downloadPath(16, 22);
		default:
			return '';
	}
}


/**
 * Button for the navigation toolbar of the `Chessgame` component.
 */
export default function NavigationButton(props) {
	let path = `M 16 0 A 16 16 0 0 0 16 32 A 16 16 0 0 0 16 0 Z ${innerPath(props.type)}`;
	return (
		<div className="rpbchessboard-navigationButton" title={props.tooltip} onClick={props.onClick}>
			<svg viewBox="0 0 32 32" width={props.size} height={props.size}>
				<path d={path} fill="currentcolor" />
			</svg>
		</div>
	);
}

NavigationButton.propTypes = {
	size: PropTypes.number.isRequired,
	type: PropTypes.string.isRequired,
	tooltip: PropTypes.string.isRequired,
	onClick: PropTypes.func.isRequired,
};
