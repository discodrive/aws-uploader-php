<?php 

add_action('admin_menu', 'apu_admin_menu');
// add_filter('upload_dir', 'awsDirectory');
add_action( 'wp_handle_sideload_prefilter', 'addToAws');

/**
 * Create the new upload path (used with upload_dir filter)
 * @return null
 */
function awsDirectory() {
    global $s3;

	$path = "s3://" . APU_BUCKET_NAME . "/";

    return $path;
}

/**
 * Intercept the upload process and upload the file to s3 (used with wp_handle_upload_prefilter)
 * @return null
 */
function addToAws(array $file) {
    $upload_dir = wp_upload_dir();
    $new_path   = $upload_dir['basedir'] . '/tmp/' . basename( $file['tmp_name'] );

    copy( $file['tmp_name'], $new_path );
    unlink( $file['tmp_name'] );
    $file['tmp_name'] = $new_path;

    return $file;
}
