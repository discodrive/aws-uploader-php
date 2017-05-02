<?php

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Create a new s3 instance
$s3 = new S3Client([
    'version'     => 'latest',
    'region'      => APU_BUCKET_REGION,
    'credentials' => [
        'key'    => APU_ACCESS_KEY,
        'secret' => APU_SECRET_ACCESS_KEY
    ]
]);

try {
    $result = $s3->getBucketCors([
        'Bucket' => APU_BUCKET_NAME,
    ]);
} catch (AwsException $e) {
    // Display error if fails
    error_log($e->getMessage());
}

// Register the stream wrapper from an S3Client object
$s3->registerStreamWrapper();
