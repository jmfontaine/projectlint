ProjectLint - Enforce project layout rules
==========================================

ProjectLint helps you declare and enforce project layout rules, ensuring your project stays consistent at all times.

[![Build Status](https://api.travis-ci.org/jmfontaine/projectlint.svg?branch=develop)](https://travis-ci.org/jmfontaine/projectlint)

**This project is still in beta, there may be bugs and missing features.**

Installation
------------

You can install ProjectLint by using [Composer](https://getcomposer.org/). For example:

```bash
composer global require 'jmfontaine/projectlint:*'
```

This will install the `projectlint` executable to your `$COMPOSER_HOME/vendor/bin` folder (typically `$HOME/.composer/vendor/bin`).  It is recommended to add this directory to your `PATH` environment variable.

Usage
-----

1. Create a `projectlint.yml` file in the root directory of your project. Look at [ProjectLint own file](projectlint.yml) for some inspiration.
2. Run ProjectLint: `bin/projectlint`.

Requirements / Compatibility
----------------------------

ProjectLint is compatible with versions 5.4, 5.5, 5.6 of PHP and the latest version of [HHVM](http://hhvm.com/).

Contributing
------------

Here are a few rules to follow in order to ease code reviews, and discussions before maintainers accept and merge your work.

* You **MUST** follow the [PSR-2](http://www.php-fig.org/psr/2/) Coding Standard.
* You **MUST** run the test suite.
* You **MUST** write (or update) unit tests.
* You **SHOULD** write documentation.

Please, write [commit messages that make sense](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html), and [rebase your branch](http://git-scm.com/book/en/Git-Branching-Rebasing) before submitting your Pull Request.

One may ask you to [squash your commits](http://gitready.com/advanced/2009/02/10/squashing-commits-with-rebase.html) too. This is used to "clean" your Pull Request before merging it (we don't want commits such as `fix tests`, `fix 2`, `fix 3`, etc.).

Also, when creating your Pull Request on GitHub, you **MUST** write a description which gives the context and/or explains why you are creating it.

Thank you!

Author
------

Jean-Marc Fontaine

* Email: <jm@jmfontaine.net>
* Twitter: [@jmfontaine](http://twitter.com/jmfontaine)
* Blog: [jmfontaine.net](http://jmfontaine.net/)

See also the list of [contributors](https://github.com/jmfontaine/projectlint/contributors) who participated in this project.

License
-------

ProjectLint is licensed under the BSD 3-Clause License. See the [LICENSE](LICENSE) file for details.

Acknowledgments
---------------

* The "Contributing" section of this README is heavily inspired by [JoliCi README](https://github.com/jolicode/JoliCi).
