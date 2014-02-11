<?php
/**
 * Unit tests for the SampleClass class.
 *
 * You must have PHPUnit installed and in PHP's `include_path` for the
 * following `require_once` to work. And for simplicity's sake, we're just
 * explicitly `require_once`ing the **local** class we are testing as well.
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once './SampleClass.php';

/**
 * SampleClass test case
 */
class SampleClassTest extends PHPUnit_Framework_TestCase {

	/**
	 * setUp() is run before each test method in this class.
	 *
	 * @return void
	 */
	public function setUp() {
	}

	/**
	 * tearDown() is run after each test method in this class.
	 *
	 * @return void
	 */
	public function tearDown() {
	}
}
