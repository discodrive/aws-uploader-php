<?php

if (! function_exists('apu_admin_menu')) {
    function apu_admin_menu() {
        // Add top level menu item
        add_menu_page('AWS Browser Upload', 'AWS Browser Upload', 'manage_options', 'aws-browser-upload', 'apu_upload');
    }
}

if (! function_exists('apu_upload')) {
    function apu_upload() {
        if (! current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        include APU_PATH . '/admin/options.php';
    }
}
