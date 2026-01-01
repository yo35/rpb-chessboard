<?php
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
?>

<p>
    <?php
        printf(
            esc_html__(
                'This page defines the default settings applicable to the components inserted with the %1$s block.',
                'rpb-chessboard'
            ),
            '<em>' . esc_html__( 'Chess game', 'rpb-chessboard' ) . '</em>'
        );
    ?>
    <?php
        esc_html_e( 'It is possible to override these settings on each individual block, using the options in the block right side panel.', 'rpb-chessboard' );
    ?>
</p>

<?php
    RPBChessboardHelperLoader::printTemplate( 'admin-page/chess-game-settings/piece-symbols', $model );
    RPBChessboardHelperLoader::printTemplate( 'admin-page/chess-game-settings/navigation-board', $model );
    RPBChessboardHelperLoader::printTemplate( 'admin-page/chess-game-settings/navigation-board-aspect', $model );
    RPBChessboardHelperLoader::printTemplate( 'admin-page/chess-game-settings/diagrams', $model );
?>
