# PHPUnit @covers Tutorial

A small demonstration of using PHPUnit's [@covers annotation](http://phpunit.de/manual/current/en/appendixes.annotations.html#appendixes.annotations.covers) to control code coverage.

[![Build Status](https://travis-ci.org/beporter/phpunit-coverage-tutorial.png?branch=master)](https://travis-ci.org/beporter/phpunit-coverage-tutorial)
[![Coverage Status](http://beporter.github.io/phpunit-coverage-tutorial/img/coverage.png)](http://beporter.github.io/phpunit-coverage-tutorial/coverage/)

PHPUnit utilizes the [xdebug](http://xdebug.org/) extension in order to analyze your code and determine which lines of your code are "covered" by your tests. By itself, this is useful for finding untested or poorly tested areas of your application. Issues can arise though if your app is structure in such a way where a test _accidentally_ covers some of your code. Sometimes this is okay or even preferable when testing protected methods **via** their public interfaces, but this can lead to a false sense of security where atomic units of your code are only covered because something else that is tested happens to call them.

So PHPUnit's `@covers` annotation exists to provide a way to restrict which parts of your code a given unit test is _meant_ to cover. Practically speaking this means that when PHPUnit generates coverage reports, it will use the `@covers` annotations to only count lines of code explicitly **@cover**ed by your test.


## Setup

1. You should have a function installation of PHP v5.3+ on your system.

1. [Install the xdebug extension](http://xdebug.org/docs/install).

1. [Install PHPUnit](http://phpunit.de/manual/3.8/en/installation.html) 3.7+. (This repo also includes a composer file, so you should also be able to run `composer install` if you have it available.)

1. [Download](https://github.com/beporter/phpunit-coverage-tutorial/archive/master.zip) or clone this project: `git clone https://github.com/beporter/phpunit-coverage-tutorial.git`

1. All of the examples take place from the command line PHPUnit test runner, so open a terminal and navigate to the project folder.


## Tutorial

### Running existing tests and checking coverage

1. The first step is to get familiar with the code and the tests.
	* Open [`SampleClass.php`](SampleClass.php). You'll see it is built to generate [Fibonacci](http://en.wikipedia.org/wiki/Fibonacci_number) value arrays, and can format them into strings. 
	* In [`SampleClassTest.php`](SampleClassTest.php) you'll find tests for all three methods (`fib()`, `aryToStr()` and `printFibSequence()`) but note that the first and last tests are set to be skipped via `markTestSkipped()` and `markTestIncomplete()`. _(We'll come back to this later.)_

1. Next we'll run the tests as-is and generate a code coverage report for the project.
	* In your terminal, run `./phpunit-runner.sh`.
	* This will execute the tests in `SampleClassTest.php` and produce a code coverage report at `./coverage/index.html`.
	* _(You can examine the `phpunit-runner.sh` script to see how it is executing the tests and generating the HTML report if you want.)_

1. Before moving on, let's take a quick look at the output of the command line test runner.
	* You'll see a bunch of `S`'s (skipped tests), two `.`'s (passed tests) and a trailing `I` (incomplete test).
	* **This is normal** because as mentioned above the first and last tests has been intentionally disabled to start.

1. Open the coverage report index `coverage/index.html` in your browser. _(On a Mac, type `open coverage/index.html` in your Terminal.)_
	* In your browser, click on the `SampleClass.php` link to see detailed coverage for that class.
	* _(You can keep this window open from now on, and just refresh the page after running `./phpunit-runner.sh`.)_
	* _([Here is an example](http://beporter.github.io/phpunit-coverage-tutorial/coverage/SampleClass.php.html) of the initial coverage report in case you are just poking around this project and not actually following around at home.)_
	* The coverage seems pretty good in spite of those skipped tests! We're only missing one line of code in `fib()`. Except there's an issue here...
	* If you hover your mouse over any of the green "covered" lines of code, you'll see the names of the tests that executed that line.
	* In this case, all of our lines are covered by `testPrintFibSequence()`. **Uh oh**, that means our code coverage of `fib()` and `aryToStr()` is based on incidental calls from `printFibSequence()`.
	* What's more, if you hover over the one line in `aryToStr()` you'll see it is covered by two tests: `testAryToStr` and `testPrintFibSequence`. This means your tests are executing your code redundantly (which is why tests doubles are usually preferable), but in our case it also means one of our tests is overreaching its intended scope and what we're concerned with at the moment is being able to analyze coverage accurately.

### Fixing the coverage report

1. The first thing we want to do is isolate our active test so that it only "covers" the method we intend it to.
	* _(Another [almost always better] way to accomplish this is by using [test doubles](http://phpunit.de/manual/3.7/en/test-doubles.html) but this example is about `@covers`, so bear with me.)_
	* In `SampleClassTest.php`, find the comment `TUTORIAL#1` which should be around [`L106`](SampleClassTest.php#L106).
	* Immediately below that line is a PHPUnit `@covers` annotation that has been disabled.
	* Remove the `-disabled` from the annotation (so the whole clause reads `@covers SampleClass::printFibSequence`) and save the file.

1. Now run the tests again (`./phpunit-runner.sh`) and refresh the code coverage report in your Terminal.
	* Back in your browser, the percentage of code covered should **drop**.
	* Now our `testPrintFibSequence()` test method is **only** covering the `printFibSequence()` method, and the incidental calls to the other class methods have been ignored.
	* This gives us a more accurate picture of what we're really testing.
	* Hovering your mouse over `aryToStr()`'s single line now reports only one test covering it.

### Covering the remaining method

In this tutorial the hard work of writing the tests has been done for you, so all you need to do is enable them.

 1. In `SampleClassTest.php`, find the line contain `TUTORIAL#2` which should be around [`L78`](SampleClassTest.php#L78) at the top of `testFib()`.

1. Delete this entire line (which will cause this test to no longer be skipped) and save the file.

1. Run your tests again (`./phpunit-runner.sh`), switch back to your browser and refresh the report.
	* On the command line, our tests are almost green now!
	* In our coverage report, we've covered `fib()` in its entirety thanks to `testib()`.

### BONUS: Data providers

* If you take a look at the now-active `testFib()` method, you'll notice that it has method arguments defined, and doesn't do any setup-- It only calls the `assertEquals()` assertion with the provided arguments.

* The arguments come from a [data provider](http://phpunit.de/manual/3.7/en/writing-tests-for-phpunit.html#writing-tests-for-phpunit.data-providers), which is another method in the test class that returns an array of "data sets" that should be fed to the test method.

* The `testFib()` method's doc block contains a `@dataProvider provideTestFibArgs` annotation that tells PHPUnit which method to get the data sets from.

* Each element from the array return by `provideTestFibArgs()` contains a set of values that will be provided to `testFib()` as a set of arguments.

* Data providers are an excellent way to control redundancy in your test methods and easily test highly algorithmic methods that vary only in input and output.

* It's also a lot easier to add new specific test samples as you encounter (and fix) problematic input/output pairs.

### BONUS: Using a test double instead of @covers

* The last test method, `SampleClassTest::testPrintFibSequenceTestDouble()` (marked by a `TUTORIAL#3` comment around [`L128`](SampleClassTest.php#L128)) can be enabled by deleting the `markTestIncomplete()` line and is an alternate way of testing the `printFibSequence()`.

* Once you've done this, it's safe to delete the original `testPrintFibSequence()` method entirely.

* This second version replaces the "real" copies of the `fib()` and `aryToStr()` methods with "test doubles" that do what we tell them to instead of what the actual code as-written does.

* When we call the (real) `printFibSequence()` method in this test double, our replaced versions of the "incidental" methods execute instead of the real ones, allowing us to never actually leave the `printFibSequence()` method during test execution.

* This approach is quite a bit more verbose, but doesn't require the `@covers` line in its doc block because it never actually calls the real `fib()` and `aryToStr()` methods.

* This also makes our test completely independent from changes made to `fib()` and `aryToStr()`, which is an extremely good thing.

* `@covers` shouldn't be used in place of using test doubles; only as an intelligent supplement to control the coverage **reports**.

### Final thoughts

* The `@covers` annotation can be really excellent for limiting what your tests are intended to be "touching".

* They are especially handy when you can't easily control a method's use of external calls. (Static calls like CakePHP's `Router::url()` in particular are horrible for unit testing.)

* The resulting code coverage is much "truer" because you don't get _any_ incidental method calls as an unwanted bonus in your coverage. This makes you work for the coverage more honestly.

* The downside is that the `@covers` annotations are **really** easy to miss, and when refactoring code or tests, you can end up with a test that `@covers` a method name that doesn't exist anymore (something [I actually did accidentally](https://github.com/beporter/phpunit-coverage-tutorial/pull/1) while setting this tutorial up), which means that test contributes _nothing_ to your coverage even if it is properly executing and verifying some code _somewhere_ correctly.


## Questions, Comments, Feedback, Contributions

Please [post an issue](https://github.com/beporter/phpunit-coverage-tutorial/issues/new). This was intended as a one-off lesson, so while I appreciate feedback and contributions, I don't intended to spend a lot of time maintaining or improving this project.

## License

[CC-BY-NC-SA-3.0](http://spdx.org/licenses/CC-BY-NC-SA-3.0)

## Copyright

&copy; 2014 Brian Porter



## Technical Details

* The `composer.json` file (in this case) is really only needed to pull in tools used for testing (phpunit and [woodhouse](https://github.com/IcecaveStudios/woodhouse).)
	* It's also used locally for "one-time" set up the rest of these configs, so you'll have to run `composer install` at least once to do the rest of this.
* You must have a script that will execute your full test suite, in our case that's `phpunit-runner.sh.
	* It's important that the phpunit script be set up to generate HTML coverage and a text coverage file for use by woodhouse later.
	* These generated files should be included in the `.gitignore` so they do not get accidentally committed to the repo.
* The `.travis.yml` config specifies the `phpunit-runner.sh` as the "script" to run during travis executions.
* The travis config specifies woodhouse.sh as the "after-run" script to publish code coverage back to the `gh-pages` branch of the Github repo.
* Woodhouse needs a Github auth token in order to do the publishing.
* The auth token can be created manually in your Github repo's settings, or [using the woodhouse command line tool](https://github.com/IcecaveStudios/woodhouse#creating-a-github-token).
* That auth token [needs to be encrypted](http://docs.travis-ci.com/user/build-configuration/#Secure-environment-variables) as an environment variable that is available during travis test runs using `travis encrypt ENV_VAR_NAME="env var value"`.  (See also: [installing the travis command line tool](https://github.com/travis-ci/travis#installation).)
* The encrypted value needs to be saved into your `.travis.yml` file under `env: global: - secure:`.
* The counterpart to this is that when you execute `woodhouse` (in this project, that happens in `woodhouse.sh`) you must tell woodhouse where to obtain the key using `--auth-env-token ENV_VAR_NAME` (where "ENV_VAR_NAME" is the same thing you encrypted earlier.
	* Woodhouse also depends on a text coverage file generated earlier by phpunit so it can grab the proper code coverage badge, which will also be published back to gh-pages.
* You should [lint your .travis.yml file](http://docs.travis-ci.com/user/travis-lint/).
* You should [lint your composer.json file](https://getcomposer.org/doc/03-cli.md#validate).
* Finally, you must [enable travis for your repo](http://docs.travis-ci.com/user/getting-started/#Step-one%3A-Sign-in).

What this all accomplishes:

* When you push a commit, travis is notified and starts a test run.
* Travis uses a virtual machine to clone your code, init any git submodules, install any composer dependencies, and then executes a script which should run your entire test suite.
* The exit code of that script determines if the build passes (0) or fails (>0).
* Any other necessary steps are also run before/after (including woodhouse:)
	* woodhouse takes any local "artifacts" you designate and commits them back to the named repo into the `gh-pages` branch.
	* These artifacts become available at http://_username_.github.io/_repository_/, which you can link to in your README to display a build status or code coverage badge, for example.
