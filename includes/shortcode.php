<?php

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Shortcode logic for displaying the Crypto QR Code element.
 * 
 * @since 1.0.1
 * @package Crypto QR Code WP
 */
function crypto_qr_code_wp_shortcode_logic( $atts ) {
	// Parse shortcode attributes.
	$a = shortcode_atts( array(
        'heading' => esc_html__( 'Donate', 'crypto-qr-code-wp' ),
		'label'   => '',
		'address' => ''
    ), $atts );

    if( empty( $a['address'] ) || empty( $a['label'] ) ) {
        return;
    }
    
    // Set variables.
    $content = NULL;
    settype( $content, 'string' );

    $heading = $a['heading'];
    $label   = $a['label'];
    $address = $a['address'];

    // Generate QR address.
    $qr_svgCode_file = CRYPTO_QR_CODE_WP_IMG_DIR . $address . '.svg';
    $qr_svgCode_url = CRYPTO_QR_CODE_WP_URL . 'uploads/crypto-qr-codes/' . $address . '.svg';
    if( ! file_exists( $qr_svgCode_file ) ) {
        $qr_svgCode_generate = QRcode::svg( $address, $qr_svgCode_file );
    }

    $random_num = substr( md5( mt_rand( 0, 999999999 ) ), 0, 9 ); 

    // Build template.
    $content .= "<span class=\"cqcw-block\">
                    <label class=\"cqcw-block__label\">{$label}:</label>
                    <a href=\"#{$label}_{$address}_{$random_num}\" class=\"cqcw-block__button\">{$address}</a>
                    <em id=\"{$label}_{$address}_{$random_num}\" class=\"cqcw-block__dialog\">
                        <strong class=\"cqcw-block__dialog-heading\">{$heading} {$label}</strong>
                        <img src=\"{$qr_svgCode_url}\" alt=\"{$address}\" />
                        <strong class=\"cqcw-block__dialog-content\">{$address}</strong>
                        <button type=\"button\" class=\"cqcw-block__button-close\">x</button>
                    </em>
                 </span>";

	$content = apply_filters( 'crypto_qr_code_wp_shortcode_logic', $content );
    return apply_filters( 'crypto_qr_code_wp_shortcode', $content );
}
add_shortcode( 'cqcw_generator', 'crypto_qr_code_wp_shortcode_logic' );