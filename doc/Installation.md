ProjectLint Installation
========================

Requirements
------------

ProjectLint requires PHP version 5.4.0 or greater. However using the latest version of PHP is highly recommended.

It also supports the latest version of [HHVM](http://hhvm.com/).

PHAR file (Prefered)
--------------------

The easiest way to obtain ProjectLint is to [download the latest release](https://github.com/jmfontaine/projectlint/releases/latest) as a PHP Archive (PHAR) that contains everything required to run it, and to put it in your project directory. The `phar` extension is required for using PHAR files.

You can then run ProjectLint:

~~~bash
$ php projectlint.phar
~~~

**Note:** *If the `suhosin` extension is enabled, you need to allow execution of PHARs in your php.ini:*

~~~ini
suhosin.executor.include.whitelist = phar        
~~~

### Verifying PHAR file signature

All ProjectLint releases are signed by the release manager. PGP signatures are available on the [releases page](https://github.com/jmfontaine/projectlint/releases), next to the releases. They are named `projectlint.phar.asc`.

The first time you need to import the release manager GPG key:

~~~bash
$ gpg --keyserver pgp.mit.edu --recv-key 5B0E4458
~~~

You can then verify the signature by running:

~~~bash
$ gpg projectlint.phar.asc
gpg: Signature made Lun 18 aoû 19:59:24 2014 CEST using RSA key ID 5B0E4458
gpg: Good signature from "Jean-Marc Fontaine <jm@jmfontaine.net>"
gpg: WARNING: This key is not certified with a trusted signature!
gpg:          There is no indication that the signature belongs to the owner.
Primary key fingerprint: FE9A EAE4 E72D F076 C2C6  A60C E248 000A 5B0E 4458
~~~

At this point, the signature is good, but you don't trust this key. A good signature means that the file has not been tampered. However, due to the nature of public key cryptography, you need to additionally verify that key 5B0E4458 was created by the real Jean-Marc Fontaine.

Any attacker can create a public key and upload it to the public key servers. They can then create a malicious release signed by this fake key. Then, if you tried to verify the signature of this corrupt release, it would succeed because the key was not the "real" key. Therefore, you need to validate the authenticity of this key. Validating the authenticity of a public key, however, is outside the scope of this documentation.

**As a rule of thumb, if you only download ProjectLint PHAR files and PGP signatures from the [official GitHub project](https://github.com/jmfontaine/projectlint/), you can safely ignore this warning.**

Alternative methods
-------------------

### Composer (Local to project)

ProjectLint can be installed as a dependency to your project using [Composer](https://getcomposer.org/):

~~~bash
composer require 'jmfontaine/projectlint'
~~~

You can then run ProjectLint:

~~~bash
$ vendor/bin/projectlint
~~~

### Composer (Global)

ProjectLint can be installed by using [Composer](https://getcomposer.org/):

~~~bash
composer global require 'jmfontaine/projectlint'
~~~

This will install the `projectlint` executable to your `$HOME/.composer/vendor/bin` folder.

You can then run ProjectLint:

~~~bash
$ $HOME/.composer/vendor/bin/projectlint
~~~

It is recommended to add this directory to your `PATH` environment variable in order to ease running ProjectLint:

~~~bash
$ projectlint
~~~

