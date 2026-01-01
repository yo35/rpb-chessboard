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
    wp_enqueue_style( 'rpbchessboard-admin' );
?>

<div id="rpbchessboard-adminPage" class="wrap">
    <h1>RPB Chessboard</h1>

    <div id="rpbchessboard-adminPageMenu">
        <?php foreach ( $model->getSubPages() as $subPage ) : ?>
            <?php if ( $subPage === $model->getCurrentSubPage() ) : ?>
                <span id="rpbchessboard-activePageButton">
            <?php else : ?>
                <a href="<?php echo esc_url( $model->getSubPageLink( $subPage ) ); ?>" target="<?php echo $model->isExternalSubPage( $subPage ) ? '_blank' : '_self'; ?>">
            <?php endif; ?>
            <?php if ( $model->hasSubPageIcon( $subPage ) ) : ?>
                <span class="rpbchessboard-menuIcon rpbchessboard-menuIcon-<?php echo esc_attr( $model->getSubPageIcon( $subPage ) ); ?>"></span><span class="rpbchessboard-menuLabel">
                    <?php echo esc_html( $model->getSubPageLabel( $subPage ) ); ?>
                </span>
            <?php else : ?>
                <span class="rpbchessboard-menuLabel"><?php echo esc_html( $model->getSubPageLabel( $subPage ) ); ?></span>
            <?php endif; ?>
            <?php if ( $subPage === $model->getCurrentSubPage() ) : ?>
                </span>
            <?php else : ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div id="rpbchessboard-adminPageContent" class="rpbchessboard-jQuery-enableSmoothness">
        <?php RPBChessboardHelperLoader::printTemplate( 'admin-page/' . $model->getTemplateName(), $model ); ?>
    </div>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            Array.from(document.getElementsByClassName('rpbchessboard-menuIcon-fen')).forEach(RPBChessboard.renderAdminFENIcon);
            Array.from(document.getElementsByClassName('rpbchessboard-menuIcon-pgn')).forEach(RPBChessboard.renderAdminPGNIcon);
        });
    </script>

</div>
