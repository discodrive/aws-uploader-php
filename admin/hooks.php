<?php 

add_action('admin_menu', 'apu_admin_menu');
add_filter('upload_dir', 'awsDirectory');
add_filter('wp_handle_upload_prefilter', 'addToAws');

/**
 * Create the new upload path (used with upload_dir filter)
 * @return null
 */
function awsDirectory() {
	$path = 'https://s3-' . APU_BUCKET_REGION . '.amazonaws.com/' . APU_BUCKET_NAME . '/';

    return $path;
}

/**
 * Intercept the upload process and upload the file to s3 (used with wp_handle_upload_prefilter)
 * @return null
 */
function addToAws($file) {
    global $s3;

    $key = $file['name'];
    $path = $_FILES['file'];

    try {
        $result = $s3->putObject([
            'Bucket'     => APU_BUCKET_NAME,
            'Key'        => $key,
            'SourceFile' => $path
        ]);
    } catch (S3Exception $e) {
        echo $e->getMessage() . '\n';
    }
}
