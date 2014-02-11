# PHPUnit @covers Tutorial

A small demonstration of using PHPUnit's [@covers annotation](http://phpunit.de/manual/current/en/appendixes.annotations.html#appendixes.annotations.covers) to control code coverage.

PHPUnit utilizes the [xdebug](http://xdebug.org/) extension in order to analyze your code and determine which lines of your code are "covered" by your tests. By itself, this is useful for finding untested or poorly tested areas of your application. Issues can arise though if your app is structure in such a way where a test _accidentally_ covers some of your code. Sometimes this is okay or even preferable when testing protected methods **via** their public interfaces, but this can lead to a false sense of security where atomic units of your code are only covered because something else that is tested happens to call them.

So PHPUnit's `@covers` annotation exists to provide a way to restrict which parts of your code a given unit test is _meant_ to cover. Practically speaking this means that when PHPUnit generates coverage reports, it will use the `@covers` annotations to only count lines of code explicitly **@cover**ed by your test.


## Setup

1. [Install the xdebug extension](http://xdebug.org/docs/install).

1. [Install PHPUnit](http://phpunit.de/manual/3.8/en/installation.html). (This repo also includes a composer file, so you should also be able to run `composer install` if you have it available.)

1. [Download](https://github.com/beporter/phpunit-coverage-tutorial/archive/master.zip) or clone this project: `git clone https://github.com/beporter/phpunit-coverage-tutorial.git`

1. All of the example take place from the command line PHPUnit test runner, so open a terminal and navigate to the project folder.


## Tutorial

