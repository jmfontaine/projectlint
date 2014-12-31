ProjectLint - Enforce project layout rules
==========================================

ProjectLint helps you declare and enforce project layout rules, ensuring your project stays consistent at all times.

[![Build Status](https://api.travis-ci.org/projectlint/projectlint.svg?branch=master)](https://travis-ci.org/projectlint/projectlint)

**This project is still in beta, there may be bugs and missing features.**


Requirements / Compatibility
----------------------------

ProjectLint requires PHP version 5.4.0 or greater. However using the latest version of PHP is highly recommended.

It also supports the latest version of [HHVM](http://hhvm.com/).

Installation
------------

See [documentation](doc/Installation.md) for installation instructions.

Usage
-----

1. Create a `projectlint.yml` file in the root directory of your project. See [documentation](doc/Configuration.md) for detailled format information.
2. Run ProjectLint from your project directory:

~~~bash
$ php projectlint.phar
~~~
    
**Note:** *The way to run ProjectLint depends on the way you installed it. Please see [installation documentation](doc/Installation.md) for details.*

Documentation
-------------

The documentation for ProjectLint is available in the [`doc`](doc/Index.md) directory.

Issues
------

Bug reports and feature requests can be submitted on the [Github Issue Tracker](https://github.com/projectlint/projectlint/issues).

Contributing
------------

See [CONTRIBUTING.md](CONTRIBUTING.md) for information.

Author
------

Jean-Marc Fontaine

* Email: <jm@jmfontaine.net>
* Twitter: [@jmfontaine](http://twitter.com/jmfontaine)
* Blog: [jmfontaine.net](http://jmfontaine.net/)

See also the list of [contributors](https://github.com/projectlint/projectlint/contributors) who participated in this project.

License
-------

ProjectLint is licensed under the BSD 3-Clause License. See the [LICENSE](LICENSE) file for details.
