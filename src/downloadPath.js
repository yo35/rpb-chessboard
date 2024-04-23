/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2024  Yoann Le Montagner <yo35 -at- melix.net>       *
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

const THICKNESS = 2;
const ARROW_SIZE = 5;
const ARROW_LENGTH = 16;
const DOWNLOAD_WIDTH = 16;


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
    const xA = x - direction * (ARROW_SIZE + THICKNESS / Math.sqrt(2) / 2);
    const xB = x - direction * (ARROW_SIZE - THICKNESS / Math.sqrt(2) / 2);
    const xC = x - direction * THICKNESS / 2;
    const xF = x + direction * THICKNESS / 2;
    const xG = x + direction * (ARROW_SIZE - THICKNESS / Math.sqrt(2) / 2);
    const xH = x + direction * (ARROW_SIZE + THICKNESS / Math.sqrt(2) / 2);
    const y0 = y - direction * THICKNESS * Math.sqrt(2) / 2;
    const yA = y0 - direction * (ARROW_SIZE - THICKNESS / Math.sqrt(2) / 2);
    const yB = y0 - direction * (ARROW_SIZE + THICKNESS / Math.sqrt(2) / 2);
    const yC = yB + (xC - xB);
    const yD = y - direction * ARROW_LENGTH;
    return `M ${x} ${y} L ${xA} ${yA} L ${xB} ${yB} L ${xC} ${yC} V ${yD} H ${xF} V ${yC} L ${xG} ${yB} L ${xH} ${yA} Z`;
}


function downloadPath(x, y) {
    const bottom = `M ${x - DOWNLOAD_WIDTH / 2} ${y} H ${x + DOWNLOAD_WIDTH / 2} V ${y + THICKNESS} H ${x - DOWNLOAD_WIDTH / 2} Z`;
    return arrowPath(x, y, 1) + ' ' + bottom;
}


export const DOWNLOAD_PATH = downloadPath(16, 22);
