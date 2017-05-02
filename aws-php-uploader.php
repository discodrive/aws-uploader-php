<?php 

/*
Plugin Name: AWS PHP Uploader
Plugin URI:  https://substrakt.com
Description: Upload from WordPress to an AWS S3 bucket
Version:     1.0
Author:      Substrakt
Author URI:  https://substrakt.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

define('APU_PATH', __DIR__);
define('APU_BUCKET_NAME', get_option('apuBucketName'));
define('APU_BUCKET_REGION', get_option('apuBucketRegion'));
define('APU_ACCESS_KEY', get_option('apuAccessKeyId'));
define('APU_SECRET_ACCESS_KEY', get_option('apuSecretAccessKey'));

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/admin/actions.php';
require __DIR__ . '/app/hooks.php';
require __DIR__ . '/app/setup.php';
require __DIR__ . '/app/helpers.php';
