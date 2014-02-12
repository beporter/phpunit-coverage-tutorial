#!/usr/bin/env bash
# Convenience wrapper for executing phpunit with necessary args to
# generate code coverage.

phpunit \
 --colors \
 --coverage-text=coverage.txt \
 --coverage-html=coverage/ \
 SampleClassTest.php
