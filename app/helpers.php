<?php

/**
 * Create the new upload path (used with upload_dir filter)
 * @return String $path
 */
function awsDirectory() {
    global $s3;
    $path = 'https://s3.' . APU_BUCKET_REGION . '.amazonaws.com/' . APU_BUCKET_NAME;
    return $path;
}

//  Add all buckets in the AWS account to an array
function getAwsBuckets() {
    global $s3;

    $buckets = $s3->listBuckets();
    $list = [];
    foreach ($buckets['Buckets'] as $bucket){
        $list[] = $bucket['Name'];
    }

    return $list;
}

// Display the contents of the S3 bucket
function getBucketContents()
{
    global $s3;

    $dir = "s3://" . APU_BUCKET_NAME . "/";

    if (is_dir($dir) && ($dh = opendir($dir))) {
        while (($file = readdir($dh)) !== false) {
            echo "Filename: {$file} || Filetype: " . filetype($dir . $file) . "<br/>";
        }
        closedir($dh);
    }
}
