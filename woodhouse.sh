#!/usr/bin/env bash
# Wraps up all of the woodhouse arguments in an easy-to-run script.
# Used as the after-run script in `.travis.yml`.

vendor/bin/woodhouse \
 publish beporter/phpunit-coverage-tutorial \
 coverage/:coverage/ \
 --coverage-image img/coverage.png \
 --image-theme buckler/buckler \
 --coverage-phpunit coverage.txt \
 --auth-token-env WOODHOUSE_TOKEN
