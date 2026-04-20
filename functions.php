<?php
// Cero scripts/estilos de WordPress por defecto
add_action('wp_enqueue_scripts', function () {
    // Google Fonts
    wp_enqueue_style(
        'rc-fonts',
        'https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700;800&family=IBM+Plex+Sans:wght@300;400;500;600&display=swap',
        [],
        null
    );
    // Estilos propios
    wp_enqueue_style(
        'rc-main',
        get_template_directory_uri() . '/assets/styles.css',
        ['rc-fonts'],
        '1.0.0'
    );
}, 10);

// Eliminar estilos de WordPress que interfieren
add_action('wp_enqueue_scripts', function () {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('classic-theme-styles');
    wp_dequeue_style('global-styles');
}, 100);

// Eliminar emoji scripts (innecesarios)
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// wpautop inserta <p> fantasma entre elementos de grid/flex — desactivar
remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

// Sin barra de admin en el front
add_filter('show_admin_bar', '__return_false');

// Soporte básico de WordPress
add_theme_support('title-tag');
add_theme_support('html5', ['script', 'style']);
