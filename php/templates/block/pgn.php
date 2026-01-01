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

<?php
    wp_enqueue_script( 'rpbchessboard-npm' );
    wp_enqueue_style( 'rpbchessboard-npm' );
?>

<p class="rpbchessboard-spacerBefore"></p>

<div class="rpbchessboard-chessgame">
    <noscript>
        <div class="rpbchessboard-javascriptWarning">
            <?php esc_html_e( 'You must activate JavaScript to enhance chess game visualization.', 'rpb-chessboard' ); ?>
        </div>
    </noscript>
    <div id="<?php echo esc_attr( $model->getUniqueID() ); ?>"></div>
    <script type="text/javascript">
        (function() {
            function renderThisPGN() {
                RPBChessboard.renderPGN(<?php echo wp_json_encode( $model->getUniqueID() ); ?>, <?php echo wp_json_encode( $model->getWidgetArgs() ); ?>);
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', renderThisPGN);
            }
            else {
                renderThisPGN();
            }
        })();
    </script>
</div>

<p class="rpbchessboard-spacerAfter"></p>
