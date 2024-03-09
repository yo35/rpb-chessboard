<?php
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
?>

<div id="rpbchessboard-newsNotice" class="notice notice-info <?php echo $model->isDismissible() ? 'is-dismissible' : ''; ?>">
    <?php
        // See file `/php/templates/newsnoticecontent.php`
        RPBChessboardHelperLoader::printTemplate( 'news-notice-content', null );
    ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $( document ).on( 'click', '#rpbchessboard-newsNotice .notice-dismiss', function () {
            $.ajax( ajaxurl, {
                type: 'POST',
                data: {
                    action: 'rpbchessboard-dismissNewsNotice',
                }
            });
        });
    });
</script>
