# PHPUnit @covers Tutorial

A small demonstration of using PHPUnit's [@covers annotation](http://phpunit.de/manual/current/en/appendixes.annotations.html#appendixes.annotations.covers) to control code coverage.

PHPUnit utilizes the [xdebug](http://xdebug.org/) extension in order to analyze your code and determine which lines of your code are "covered" by your tests. By itself, this is useful for finding untested or poorly tested areas of your application. Issues can arise though if your app is structure in such a way where a test _accidentally_ covers some of your code. Sometimes this is okay or even preferable when testing protected methods **via** their public interfaces, but this can lead to a false sense of security where atomic units of your code are only covered because something else that is tested happens to call them.

So PHPUnit's `@covers` annotation exists to provide a way to restrict which parts of your code a given unit test is _meant_ to cover. Practically speaking this means that when PHPUnit generates coverage reports, it will use the `@covers` annotations to only count lines of code explicitly **@cover**ed by your test.


## Setup

1. You should have a function installation of PHP v5.3+ on your system.

1. [Install the xdebug extension](http://xdebug.org/docs/install).

1. [Install PHPUnit](http://phpunit.de/manual/3.8/en/installation.html) 3.7+. (This repo also includes a composer file, so you should also be able to run `composer install` if you have it available.)

1. [Download](https://github.com/beporter/phpunit-coverage-tutorial/archive/master.zip) or clone this project: `git clone https://github.com/beporter/phpunit-coverage-tutorial.git`

1. All of the example take place from the command line PHPUnit test runner, so open a terminal and navigate to the project folder.


## Tutorial

### Running existing tests and checking coverage

1. The first step is to get familiar with the code and the tests.
	* Open `SampleClass.php`. You'll see it is built to generate [Fibonacci](http://en.wikipedia.org/wiki/Fibonacci_number) value arrays, and can format them into strings. 
	* In `SampleClassTest.php` you'll find tests for all three methods (`fib()`, `aryToStr()` and `printFibSequence()`) but note that the first and last tests are set to be skipped via `markTestSkipped()` and `markTestIncomplete()`. _(We'll come back to this later.)_

1. Next we'll run the tests as-is and generate a code coverage report for the project.
	* In your terminal, run `./phpunit-runner.sh`.
	* This will execute the tests in `SampleClassTest.php` and produce a code coverage report at `./coverage/index.html`.
	* _(You can examine the `phpunit-runner.sh` script to see how it is executing the tests and generating the HTML report if you want.)_

1. Before moving on, let's take a quick look at the output of the command line test runner.
	* You'll see a bunch of `S`'s (skipped tests), two trailing `.`'s (passed tests) and a trailing `I` (incomplete test).
	* **This is normal** because as mentioned above the first and last tests has been intentionally disabled to start.

1. Open the coverage report index `coverage/index.html` in your browser. _(On a Mac, type `open coverage/index.html` in your Terminal.)_
	* In your browser, click on the `SampleClass.php` link to see detailed coverage for that class.
	* _(You can keep this window open from now on, and just refresh the page after running `./phpunit-runner.sh`.)_
	* The coverage seems pretty good in spite of those skipped tests! We're only missing one line of code in `fib()`. Except there's an issue here...
	* If you hover your mouse over any of the green "covered" lines of code, you'll see the names of the tests that executed that line.
	* In this case, all of our lines are covered by `testPrintFibSequence()`. **Uh oh**, that means our code coverage of `fib()` and `aryToStr()` is based on incidental calls from `printFibSequence()`.
	* What's more, if you hover over the one line in `aryToStr()` you'll see it is covered by two tests: `testAryToStr` and `testPrintFibSequence`. This means your tests are executing your code redundantly (which is why tests doubles are usually preferable), but in our case it also means one of our tests is overreaching its intended scope and what we're concerned with at the moment is being able to analyze coverage accurately.

### Fixing the coverage report

1. The first thing we want to do is isolate our only active test so that it only "covers" the method we intend it to.
	* _(Another [probably better] way to accomplish this is by using [test doubles](http://phpunit.de/manual/3.7/en/test-doubles.html) but this example is about `@covers`, so bear with me.)_
	* In `SampleClassTest.php`, find the comment `TUTORIAL#1` which should be around line `106`.
	* Immediately below that line is a PHPUnit `@covers` annotation that has been disabled.
	* Remove the `-disabled` from the annotation (so the whole clause reads `@covers SampleClass::printFibSequence`) and save the file.

1. Now run the tests and refresh your code coverage report in your Terminal again: `./phpunit-runner.sh`
	* Back in your browser, the percentage of code covered should **drop**.
	* Now our single test method is **only** covering the `printFibSequence()` method, and the incidental calls to the other class methods have been ignored.
	* This gives us a more accurate picture of what we're really testing.

### Covering the other methods

In this tutorial, the hard work of writing the tests has been done for you, so all you need to do is enable them.

 1. In `SampleClassTest.php`, find the line contain `TUTORIAL#2` which should be around `78` at the top of `testFib()`.

1. Delete this entire line (which will cause this test to no longer be skipped) and save the file.

1. Run your tests again (`./phpunit-runner.sh`), switch back to your browser and refresh the report.
	* Our tests are green now!
	* And in our coverage report, we've covered `fib()` in its entirety.
	* Hovering your mouse over `aryToStr()`'s single line now reports only one test covering it.

### BONUS: Data providers

* If you take a look at the `testFib()` method, you'll notice that it has method arguments defined, and doesn't do any setup. It only calls the `assertEquals()` assertion with the provided arguments.

* The arguments come from a [data provider](), which is another method in the test class that returns an array of "data sets" that should be fed to the test method.

* The `testFib()` method's doc block contains a `@dataProvider provideTestFibArgs` annotation that tells PHPUnit which method to get the data sets from.

* Each element from the array return by `provideTestFibArgs()` contains a set of values that will be provided to `testFib()` as a set of arguments.

* Data providers are an excellent way to control redundancy in your test methods and easily test highly algorithmic methods that vary only in input and output.

### BONUS: Using a test double instead of @covers

* The last test method, `SampleClassTest::testPrintFibSequenceTestDouble()` is an alternate way of testing the `printFibSequence()`.

* It replaces the "real" versions of the `fib()` and `aryToStr()` methods with "test doubles" that do what we tell them to instead of what the actual code as written does.

* When we call the (real) `printFibSequence()` method in this test double, our replaced versions of the "incidental" methods execute instead of the real ones, allowing us to never actually leave the `printFibSequence()` method during test execution.

* As you can see, this approach is quite a bit more verbose, but doesn't require the `@covers` line in its doc block because it never actually calls the real `fib()` and `aryToStr()` methods.

* This also makes our test completely independent from changes made to `fib()` and `aryToStr()`, which is an extremely good thing.

* `@covers` should be used to replace using test doubles, only as an intelligent supplement.


### Final thoughts

* The `@covers` annotation can be really excellent for limiting what your tests are intended to be "touching".

* They are especially handy when you can't easily control a method's use of external calls. (static calls like `Router::url()` in particular are horrible for unit testing.)

* The resulting code coverage is much "truer" because you don't get ANY incidental method calls as a bonus to your coverage. This makes you work for the coverage more honestly.

* The downside is that the `@covers` annotations are **really** easy to miss, and when refactoring code or tests, you can end up with a test that `@covers` a method name that doesn't exist anymore, which means that test contributes _nothing_ to your coverage even if it is actually executing and verifying some code somewhere correctly.
