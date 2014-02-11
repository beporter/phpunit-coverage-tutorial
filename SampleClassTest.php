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
	 * We use it to create a new instance of our SUT (subject under test.)
	 *
	 * @return void
	 */
	public function setUp() {
		$this->SampleClass = new SampleClass();
	}

	/**
	 * tearDown() is run after each test method in this class.
	 *
	 * After each test, destroy the SUT instance completely.
	 *
	 * @return void
	 */
	public function tearDown() {
		unset($this->SampleClass);
	}

	/**
	 * Provide input, output and error messages to testFib().
	 *
	 * Data provider methods are a great way to make tests super short and
	 * maintainable. Any time you want to test the same method, but differ
	 * only the input and expected output, you should think about a data
	 * provider.
	 *
	 * @return array	Triplets of: `[input, expected, phpunit error message]`
	 */
	public function provideTestFibArgs() {
		return array(
			array(0, array(0), 'fib(0) -> 0'),
			array(1, array(0, 1), 'fib(1) -> 0 1'),
			array(2, array(0, 1, 1), 'fib(2) -> 0 1 1'),
			array(3, array(0, 1, 1, 2), 'fib(3) -> 0 1 1 2'),
			array(4, array(0, 1, 1, 2, 3), 'fib(4) -> 0 1 1 2 3'),
			array(5, array(0, 1, 1, 2, 3, 5), 'fib(5) -> 0 1 1 2 3 5'),
			array(6, array(0, 1, 1, 2, 3, 5, 8), 'fib(6) -> 0 1 1 2 3 5 8'),
		);
	}

	/**
	 * Test that our fib() method produces the expected arrays.
	 *
	 * Repeatedly run this test with each array returned from a phpunit
	 * dataProvider (above).
	 *
	 * Including a `covers` annotation here is unnecessary since fib() doesn't
	 * call any other methods, but isn't a bad idea to protect the test against
	 * future refactoring. (In other words, we want this test to only ever
	 * explicitly cover this one method, and not any of the resouces that
	 * method might use itself.)
	 *
	 * @covers SampleClass::fib
	 * @dataProvider provideTestFibArgs
	 * @return void
	 */
	public function testFib($n, $expected, $msg = '') {
		$this->markTestSkipped('testFib starts disabled for the tutorial.');  // TUTORIAL#2
		$this->assertEquals($expected, $this->SampleClass->fib($n), $msg);
	}

	/**
	 * Test that our aryToStr() method joins arrays as expected.
	 *
	 * @covers SampleClass::aryToStr
	 * @return void
	 */
	public function testAryToStr() {
		$this->assertEquals(
			'hello',
			$this->SampleClass->aryToStr(array('hello')),
			'Single word should return without spaces.'
		);
		$this->assertEquals(
			'hello world',
			$this->SampleClass->aryToStr(array('hello', 'world')),
			'More than one word should be joined with spaces.'
		);
	}

	/**
	 * Test our printFibSequence() method to ensure it prints a correct
	 * string version of the fib sequence.
	 *
	 * TUTORIAL#1
	 * @covers-disabled SampleClass::printFibSequence
	 * @return void
	 */
	public function testPrintFibSequence() {
		$this->assertEquals(
			'0 1 1 2 3 5 8',
			$this->SampleClass->printFibSequence(6),
			'Should result in a string.'
		);
	}

	/**
	 * Test printFibSequence() by mocking the other methods it depends on.
	 * The goal is for execution to **never leave** the method we are testing
	 * by providing "fake" versions of any other methods our SUT relies on.
	 * (This is where dependency injection and writing your tests in a smart
	 * way beomes a big deal.)
	 *
	 * @covers SampleClass::printFibSequence
	 * @return void
	 */
	public function testPrintFibSequenceTestDouble() {
		$this->markTestIncomplete('testPrintFibSequenceTestDouble starts disabled for the tutorial.');  // TUTORIAL#3

		// Create a "fake" SampleClass instance that contains a real
		// `printFibSequence()` and fake `fib()`/`aryToStr()` methods.
		$sampleClass = $this->getMock('SampleClass', array('fib', 'aryToStr'));

		// Tell PHPUnit what to do when the fake `fib()` is called.
		$sampleClass->expects($this->once())
			->method('fib')
			->with(42)  // We want the value this method is passed to match what we pass to printFibSequence().
			->will($this->returnValue('canary'));

		// Tell PHPUnit what to do when the fake `aryToStr()` is called.
		$sampleClass->expects($this->once())
			->method('aryToStr')
			->with('canary')  // We want the value this method is passed to match what we caused fib() to provide to printFibSequence().
			->will($this->returnValue('tweetie bird'));  // The actual return value doesn't matter as long as it comes out where we expect it to.

		// Run the test on our real printFibSequence(),
		// which will internally use our fake fib() and aryToStr() methods.
		$this->assertEquals(
			'tweetie bird',
			$sampleClass->printFibSequence(42),  // Note that we're using our mocked class for this test and not $this->SampleClass!
			'Should result in a string version.'
		);
	}
}
