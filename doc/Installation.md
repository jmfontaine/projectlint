ProjectLint Installation
========================

Using Composer (Local to project)
---------------------------------

ProjectLint can be installed as a dependency to your project using [Composer](https://getcomposer.org/):

```bash
composer require 'jmfontaine/projectlint:@stable'
```

Using Composer (Global)
-----------------------

ProjectLint can be installed by using [Composer](https://getcomposer.org/):

```bash
composer global require 'jmfontaine/projectlint:@stable'
```

This will install the `projectlint` executable to your `$COMPOSER_HOME/vendor/bin` folder (typically `$HOME/.composer/vendor/bin`).  It is recommended to add this directory to your `PATH` environment variable.