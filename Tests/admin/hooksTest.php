<?php

class hooksTest extends PHPUnit_Framework_TestCase {

	public function test_aws_directory_returns_expected_path() {
		define('APU_BUCKET_NAME', 'testbucket');
		$this->assertEquals('s3://testbucket/', awsDirectory());
	}

}