<?php
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
?>

<h3><?php esc_html_e( 'Diagrams', 'rpb-chessboard' ); ?></h3>

<?php
    RPBChessboardHelperLoader::printTemplate(
        'admin-page/generic/board-aspect',
        $model,
        array(
            'key'                => 'ido',
            'withMoveAttributes' => false,
        )
    );
?>

<p class="description">
    <?php
        printf(
            esc_html__(
                'These settings affects the diagrams inserted with token %1$s in PGN comments. ' .
                'See %2$sdocumentation%3$s for an example of such diagram.',
                'rpb-chessboard'
            ),
            '<code>[#]</code>',
            '<a href="https://rpb-chessboard.yo35.org/documentation/pgn-syntax/#diagrams" target="_blank">',
            '</a>'
        );
    ?>
</p>
