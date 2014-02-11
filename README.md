phpunit-coverage-tutorial
=========================

A small demonstration of using PHPUnit's [@covers annotation](http://phpunit.de/manual/current/en/appendixes.annotations.html#appendixes.annotations.covers) to control code coverage.

PHPUnit utilizes the [xdebug]() extension in order to analyze your code and determine which lines of your code are "covered" by your tests. By itself, this is useful for finding untested or poorly tested areas of your application. Issues can arise though if your app is structure in such a way where a test _accidentally_ covers some of your code. Sometimes this is okay or even preferable when testing protected methods **via** their public interfaces, but this can lead to a false sense of security where atomic units of your code are only covered because something else that is tested happens to call them.

So PHPUnit's `@covers` annotation exists to provide a way to restrict which parts of your code a given unit test is _meant_ to cover. Practically speaking this means that when PHPUnit generates coverage reports, it will use the `@covers` annotations to only count lines of code explicitly **@cover**ed by your test.



