<?php 

function awsDirectory($param) {
	$directory = '/testing';

	$param['path'] = $param['basedir'] . $directory;
	$param['url'] = $param['baseurl'] . $directory;

    error_log("path={$param['path']}");
    error_log("url={$param['url']}");
    error_log("subdir={$param['subdir']}");
    error_log("basedir={$param['basedir']}");
    error_log("baseurl={$param['baseurl']}");
    error_log("error={$param['error']}"); 

    return $param;
}
