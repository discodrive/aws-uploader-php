<?php
add_action('admin_menu', 'apu_admin_menu');
add_filter('upload_dir', 'awsDirectory');

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
