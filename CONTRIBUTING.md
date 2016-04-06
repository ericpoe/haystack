# Contributing to Haystack

Thank you for showing interest in helping make this project better for all.

Essentially, this project is looking at making manipulating strings and arrays more about manipulating strings and arrays and less about trying to remember which function is for strings and which for methods and which requires haystack first or needle first.

## Code Style
In short, [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) for code style and [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md) for namespaces.

## Code of Conduct
Until I need to be more explicit, don't be a jerk.

## Submitting Issues & Features
* You can create an issue [here](https://github.com/ericpoe/haystack/issues/new), but
  before doing that please read the notes below on debugging and submitting issues,
  and include as many details as possible with your report.
* Ensure that you are using the latest pre-release version of Haystack
* Include the version of Haystack you are using, the version of PHP you are using, and the OS.
* Include any error messages you are receiving and under which conditions if this is a bug-fix.
* Include which Haystack objects this feature or bug affects.
* Try to make fixing this issue or adding this feature easier for the next person:
  * For the application:
    * If you submit a code-update, also submit appropriate PHPUnit tests
    * If you don't submit a code-update, submit appropriate PHPUnit tests to show what you want improved
    * If you cannot supply PHPUnit tests, please be as descriptive as possible regarding the problem or feature you are wanting added.

## Pull Requests
* Include screenshots and animated GIFs in your pull request whenever possible.
* Follow [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) and [PSR-4](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md).
* Document your classes/methods via [DocBlock](http://www.phpdoc.org/docs/latest/guides/docblocks.html)
* Follow the whitespace/newline styles suggested in the [.editorconfig](.editorconfig) file (see: [editorconfig.org](http://editorconfig.org/) for information about this file).

## Git Commit Messages
* Use the present tense ("Add feature" not "Added feature")
* Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
* Limit the first line to 72 characters or less
* Reference issues and pull requests liberally