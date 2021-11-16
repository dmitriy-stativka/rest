<?php
add_shortcode( 'button_shortcode', 'button_shortcode_func' );

function button_shortcode_func( $atts ) {
    $atts = shortcode_atts( array(
        'href'  => 'http://example.com',
        'text'  => 'Button Text',
    ), $atts );

    return "
        <div class='custom-button'>
            <a href=" . $atts["href"] .">". $atts["text"] ."</a>
        </div>
    ";
}
