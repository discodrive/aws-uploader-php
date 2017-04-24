<?php 

add_filter('upload_dir', 'awsDirectory');

function awsDirectory($params) {
	$directory = '/test';

	$params['path'] = $params['path'] . $directory;
	$params['url'] = $params['url'] . $directory;
}