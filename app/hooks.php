<?php 

use Aws\Exception\AwsException;

add_action('admin_menu', 'apu_admin_menu');
// add_action( 'wp_handle_upload_prefilter', 'addToAws');
add_filter('upload_dir', 'filterUploadDir');

// /**
//  * Intercept the upload process and upload the file to s3 (used with wp_handle_upload_prefilter)
//  * @return null
//  */
// function addToAws($file) {
// 	global $s3;

// 	if (isset($file)) {

// 		$name 	  = $file['name'];
// 		$tmp_name = $file['tmp_name'];

// 		$extension =  explode('.', $name);
// 		$extension = strtolower(end($extension));

// 		// Set temp details
// 		$key = md5(uniqid());
// 		$tmp_file_name = "{key}.{extension}";
// 		$tmp_file_path = get_temp_dir() . "/{tmp_file_name}";

// 		// Move the file
// 		move_uploaded_file($tmp_name, $tmp_file_path);

// 		try {

// 			$s3->putObject([
// 				'Bucket' => APU_BUCKET_NAME,
// 				'Key'	 => "uploads/{$name}", // Where we store the file on s3
// 				'Body'	 => fopen($tmp_file_path, 'rb'), // Resource (the actual file) and the access details (read or write)
// 				'ACL'	 => 'public-read' // Access Control Level
// 			]);

// 			// Remove the temp file from the server
// 			unlink($tmp_file_path);

// 		} catch(S3Exception $e) {
// 			die("There was an error uploading the file");
// 		}

// 		// Get the file from S3 and insert it into the WordPress media library with a reference
// 		// Do not upload the image to WordPress

// 		$attachment = awsDirectory() . '/uploads/' . $name;
// 		insertAttachment($attachment);
// 	}
// }

/**
 * Change the upload directory
 * @return Array $dirs
 */
function filterUploadDir($dirs) {
	$dirs['path']    = str_replace( WP_CONTENT_DIR, 's3://' . APU_BUCKET_NAME, $dirs['path'] );
	$dirs['basedir'] = str_replace( WP_CONTENT_DIR, 's3://' . APU_BUCKET_NAME, $dirs['basedir'] );
	$dirs['url']     = str_replace( 's3://' . APU_BUCKET_NAME, awsDirectory(), $dirs['path'] );
	$dirs['baseurl'] = str_replace( 's3://' . APU_BUCKET_NAME, awsDirectory(), $dirs['basedir'] );

	return $dirs;
}

/**
 * Insert an attachment from S3 address.
 * @param  String $url 
 * @return Int    Attachment ID
 */
function insertAttachment($url, $post_id = null) 
{
	if(!class_exists('WP_Http')) {
		include_once(ABSPATH . WPINC . '/class-http.php');
	}

	$http = new WP_Http();
	$response = $http->request($url);
	if($response['response']['code'] != 200) {
		return false;
	}

	$upload = wp_upload_bits(basename($url), null, $response['body']);
	if(!empty($upload['error'])) {
		return false;
	}

	$file_path = $upload['file'];
	$file_name = basename($file_path);
	$file_type = wp_check_filetype($file_name, null);
	$attachment_title = sanitize_file_name(pathinfo($file_name, PATHINFO_FILENAME ) );
	$wp_upload_dir = wp_upload_dir();

	$post_info = array(
		'guid'				=> $wp_upload_dir['url'] . '/' . $file_name, 
		'post_mime_type'	=> $file_type['type'],
		'post_title'		=> $attachment_title,
		'post_content'		=> '',
		'post_status'		=> 'inherit',
	);

	// Create the attachment
	$attach_id = wp_insert_attachment( $post_info, $file_path, $post_id );

	// Include image.php
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Define attachment metadata
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

	// Assign metadata to attachment
	wp_update_attachment_metadata( $attach_id,  $attach_data );

	return $attach_id;
}
