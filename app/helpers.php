<?php

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
