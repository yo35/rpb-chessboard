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


const archiver = require('archiver');
const fs = require('fs');
const path = require('path');
const process = require('process');

const output = path.resolve(__dirname, '../rpb-chessboard.zip');

async function run() {

    fs.mkdirSync(path.dirname(output), { recursive: true });
    const archive = archiver('zip');
    archive.pipe(fs.createWriteStream(output));

    // Plugin files
    archive.directory(path.resolve(__dirname, '../build'), 'rpb-chessboard/build');
    archive.directory(path.resolve(__dirname, '../css'), 'rpb-chessboard/css');
    archive.directory(path.resolve(__dirname, '../images'), 'rpb-chessboard/images');
    archive.directory(path.resolve(__dirname, '../languages'), 'rpb-chessboard/languages');
    archive.directory(path.resolve(__dirname, '../php'), 'rpb-chessboard/php');
    archive.directory(path.resolve(__dirname, '../third-party-libs'), 'rpb-chessboard/third-party-libs');
    archive.file(path.resolve(__dirname, '../LICENSE'), { name: 'rpb-chessboard/LICENSE' });
    archive.file(path.resolve(__dirname, '../rpb-chessboard.php'), { name: 'rpb-chessboard/rpb-chessboard.php' });
    archive.file(path.resolve(__dirname, '../wordpress.readme.txt'), { name: 'rpb-chessboard/readme.txt' });

    // Plugin assets
    archive.directory(path.resolve(__dirname, '../assets'), 'rpb-chessboard-assets');

    return archive.finalize();
}

run().catch(err => { console.error(err); process.exitCode = 1; });
