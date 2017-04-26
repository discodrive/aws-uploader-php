<?php 

use Aws\Exception\AwsException;

add_action('admin_menu', 'apu_admin_menu');
// add_filter('upload_dir', 'awsDirectory');
add_action( 'wp_handle_upload_prefilter', 'addToAws');

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
function addToAws($file) {
	global $s3;

	if (isset($file)) {

		$name 	  = $file['name'];
		$tmp_name = $file['tmp_name'];

		$extension =  explode('.', $name);
		$extension = strtolower(end($extension));

		// Set temp details
		$key = md5(uniqid());
		$tmp_file_name = "{key}.{extension}";
		$tmp_file_path = get_temp_dir() . "/{tmp_file_name}";

		// Move the file
		move_uploaded_file($tmp_name, $tmp_file_path);

		try {

			$s3->putObject([
				'Bucket' => APU_BUCKET_NAME,
				'Key'	 => "uploads/{$name}", // Where we store the file on s3
				'Body'	 => fopen($tmp_file_path, 'rb'), // Resource (the actual file) and the access details (read or write)
				'ACL'	 => 'public-read' // Access Control Level
			]);

			// Remove the temp file from the server
			unlink($tmp_file_path);

		} catch(S3Exception $e) {
			die("There was an error uploading the file");
		}
	}
}
