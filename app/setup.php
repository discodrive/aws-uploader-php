<?php
add_action('admin_menu', 'apu_admin_menu');
add_filter('upload_dir', 'awsDirectory');

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$bucket = APU_BUCKET_NAME;
$s3 = new S3Client([
    'version'     => 'latest',
    'region'      => APU_BUCKET_REGION,
    'credentials' => [
        'key'    => APU_ACCESS_KEY,
        'secret' => ABU_SECRET_ACCESS_KEY
    ]
]);

try {
    $result = $s3->getBucketCors([
        'Bucket' => $bucket,
    ]);
    var_dump($result);
} catch (AwsException $e) {
    // Display error if fails
    error_log($e->getMessage());
}

function listAwsBuckets() {
    global $s3;

    $buckets = $s3->listBuckets();
    foreach ($buckets['Buckets'] as $bucket){
        echo $bucket['Name']."\n";
    }
}
