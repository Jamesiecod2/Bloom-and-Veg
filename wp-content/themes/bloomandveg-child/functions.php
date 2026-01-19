<?php

declare(strict_types=1);

add_action('wp_enqueue_scripts', function (): void {
    // Ensure parent styles load first.
    wp_enqueue_style('shopkeeper-style', get_template_directory_uri() . '/style.css');

    // Child stylesheet.
    wp_enqueue_style(
        'bloomandveg-child-style',
        get_stylesheet_uri(),
        array('shopkeeper-style'),
        wp_get_theme()->get('Version')
    );
}, 20);
