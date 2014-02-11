#!/bin/bash
# Convenience wrapper for executing phpunit with necessary args to
# generate code coverage.

phpunit --colors --coverage-html coverage SampleClassTest.php
