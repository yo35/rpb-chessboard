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

<p>
    <input type="hidden" name="smallScreenCompatibility" value="0" />
    <input type="checkbox" id="rpbchessboard-smallScreenCompatibilityField" name="smallScreenCompatibility" value="1"
        <?php echo $model->getSmallScreenCompatibility() ? 'checked="yes"' : ''; ?>
    />
    <label for="rpbchessboard-smallScreenCompatibilityField">
        <?php esc_html_e( 'Enable support for small-screen devices', 'rpb-chessboard' ); ?>
    </label>
</p>

<p class="description">
    <?php
        esc_html_e(
            'Activating this option allows to customize how RPB Chessboard renders chess diagrams on small-screen devices (such as smartphones).',
            'rpb-chessboard'
        );
    ?>
</p>

<input type="hidden" name="smallScreenModes" value="<?php echo esc_attr( count( $model->getSmallScreenModes() ) ); ?>" />



<?php foreach ( $model->getSmallScreenModes() as $index => $screenMode ) : ?>

<h3 title="<?php
if ( 0 === $screenMode->minScreenWidth ) {
    printf(
        esc_attr__( 'These options apply to devices whose resolution is less than %1$s pixel width.', 'rpb-chessboard' ),
        esc_attr( $screenMode->maxScreenWidth )
    );
} else {
    printf(
        esc_attr__( 'These options apply to devices whose resolution lies between %1$s and %2$s pixel width.', 'rpb-chessboard' ),
        esc_attr( $screenMode->minScreenWidth + 1 ),
        esc_attr( $screenMode->maxScreenWidth )
    );
}
?>
">
    <?php printf( esc_html__( 'Screen width &le; %1$s pixels', 'rpb-chessboard' ), esc_html( $screenMode->maxScreenWidth ) ); ?>
</h3>

<input type="hidden" name="smallScreenMode<?php echo esc_attr( $index ); ?>-screenWidth" value="<?php echo esc_attr( $screenMode->maxScreenWidth ); ?>" />

<div>
    <p>
        <?php
            printf(
                esc_html__( 'Restrict square size to: %1$s pixels', 'rpb-chessboard' ),
                '<input type="text" id="rpbchessboard-smallScreenMode' . esc_attr( $index ) . '-squareSizeField" class="rpbchessboard-squareSizeField" ' .
                    'name="smallScreenMode' . esc_attr( $index ) . '-squareSize" size="3" ' .
                    'value="' . esc_attr( $screenMode->squareSize ) . '"/>'
            );
        ?>
        <span id="rpbchessboard-smallScreenMode<?php echo esc_attr( $index ); ?>-squareSizeSlider" class="rpbchessboard-squareSizeSlider rpbchessboard-inlineSlider"></span>
    </p>

    <p>
        <input type="hidden" name="smallScreenMode<?php echo esc_attr( $index ); ?>-hideCoordinates" value="0" />
        <input type="checkbox" name="smallScreenMode<?php echo esc_attr( $index ); ?>-hideCoordinates" class="rpbchessboard-hideCoordinatesField"
            id="rpbchessboard-smallScreenMode<?php echo esc_attr( $index ); ?>-hideCoordinatesField" value="1"
            <?php echo $screenMode->hideCoordinates ? 'checked="yes"' : ''; ?>
        />
        <label for="rpbchessboard-smallScreenMode<?php echo esc_attr( $index ); ?>-hideCoordinatesField">
            <?php esc_html_e( 'Always hide coordinates', 'rpb-chessboard' ); ?>
        </label>
    </p>

    <p>
        <input type="hidden" name="smallScreenMode<?php echo esc_attr( $index ); ?>-hideTurn" value="0" />
        <input type="checkbox" name="smallScreenMode<?php echo esc_attr( $index ); ?>-hideTurn" class="rpbchessboard-hideTurnField"
            id="rpbchessboard-smallScreenMode<?php echo esc_attr( $index ); ?>-hideTurnField" value="1"
            <?php echo $screenMode->hideTurn ? 'checked="yes"' : ''; ?>
        />
        <label for="rpbchessboard-smallScreenMode<?php echo esc_attr( $index ); ?>-hideTurnField">
            <?php esc_html_e( 'Always hide turn flag', 'rpb-chessboard' ); ?>
        </label>
    </p>
</div>

<?php endforeach; ?>



<script type="text/javascript">
    jQuery(document).ready(function($) {

        // Slider initialization
        function initializeSmallScreenSection(index) {
            var field = $('#rpbchessboard-smallScreenMode' + index + '-squareSizeField');
            field.prop('readonly', true);
            $('#rpbchessboard-smallScreenMode' + index + '-squareSizeSlider').slider({
                value: field.val(),
                min: RPBChessboard.availableSquareSize.min,
                max: RPBChessboard.availableSquareSize.max,
                slide: function(event, ui) { field.val(ui.value); }
            });
        }
        for(var k=0; k<<?php echo wp_json_encode( count( $model->getSmallScreenModes() ) ); ?>; ++k) {
            initializeSmallScreenSection(k);
        }

        // Enable/disable the widget in each small-screen-mode sections
        function updateWidgetActivationState() {
            var enabled = $('#rpbchessboard-smallScreenCompatibilityField').prop('checked');
            $('.rpbchessboard-squareSizeSlider').slider(enabled ? 'enable' : 'disable');
            $('.rpbchessboard-hideCoordinatesField').attr('disabled', !enabled);
        }
        $('#rpbchessboard-smallScreenCompatibilityField').change(updateWidgetActivationState);
        updateWidgetActivationState();

    });
</script>
