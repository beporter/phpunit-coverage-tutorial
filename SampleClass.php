<?php

/**
 * A sample PHP class with some public interface methods.
 */
class SampleClass {
	/**
	 * Return an array of Fibonacci values for the given starting values.
	 *
	 * @access	public
	 * @param	integer	$n	Starting value. Must be a non-negative integer.
	 * @return	array		An array of all Fibonacci values from $n.
	 */
	public function fib($n) {
		if ($n == 0) {
			return array(0);
		}
		$f = array(0, 1);
		for ($x = 2; $x <= $n; $x++) {
			$f[] = $f[$x-1] + $f[$x-2];
		}
		return $f;
	}

	/**
	 * Takes an array of integers and formats it into a string for display.
	 *
	 * @access	public
	 * @param	int[]	$a	An array of integers.
	 * @return	string		A formatted string containing all values from `$a`.
	 */
	public function aryToStr($a) {
		return implode(' ', $a);
	}

	/**
	 * Produces a formatted string of Fibonacci values for $n.
	 *
	 * @access	public
	 * @param	integer	$n	Starting value. Must be a non-negative integer.
	 * @return	string		A formatted string containing all values from `$n`.
	 */
	public function printFibSequence($n) {
		return $this->aryToStr($this->fib($n));
	}
}
