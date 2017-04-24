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

