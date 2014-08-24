Configuration Reference
=======================

Configuration File Structure
----------------------------

~~~yaml
ruleset:
    import:
        - symfony-pl.yml
    rules:
        "Rule name":
            expression: item.extension == "text"
            include:
                - /path
                - /another/path
            exclude:
                - /path/subdir
            level: warning
            message: Custom message
            types: [ file, dir, link ]
            enabled: true
~~~

### Import Section ###


ProjectLint can import configuration files. Any rule will override a previously defined rule so import order is important. Rules from the main configuration file will be interpreted last so they will always override rules from imported files.

### Rules Section ###

The `rules` section contains an array of rules. A rule can contain the following items:

* `expression`: An expression that should evaluate to true. (see Expression Syntax section for more details). **Mandatory.**
* `include`: Either a single path or an array of paths to include. **Optional, defaults to "/", which means "project root".**
* `exclude`: Either a single path or an array of paths to exclude. **Optional, defaults to an empty array, which means that nothing will be excluded.**
* `level`: Level of the issue if expression evaluates to false. Available values are "error", "warning" and "info". **Optional, defaults to "error".**
* `message`: Custom message to display if expression evaluates to false. **Optional, defaults to "", which means no custom message.**
* `types`: Types of items what will be checked. Available values are "file", "dir" and "link".  **Optional, defaults to "[ file, dir, link ]", which means all available types.**
* `enabled`: Whether to enable or not the rule. This is mainly useful for disabling a rule from an imported rule set.  **Optional, defaults to "false".**

Expression Syntax
-----------------

ProjectLint expression exposes an object named `item` which represents the item currentmy being checked whether it's a file, a directory or a link.

### Properties ###

Properties on items can be accessed by using the `.` syntax, similar to JavaScript:

~~~
item.property
~~~

Available properties are:

* `absPath`: Item absolute path.
* `absDir`: Item directory absolute path..
* `atime`: Item last access time.
* `contents`: Item contents.
* `code`: If supported programming language. Null otherwise.
    * `classes`: List of classes.
    * `classesNumber`: Number of classes.
    * `functions`: List of functions.
    * `functionsNumber`: Number of functions.
    * `loc`: Number of lines of code.
    * `methods`: List of classes methods.
    * `methodsNumber`: Number of classes methods.
* `depth`: Item depth relative to project root.
* `dir`: Item directory relative to project root.
* `extension`: Item extension.
* `group`: Item group name.
* `groupId`: Item group id.
* `lines`: Item number of lines, if text format. Null otherwise.
* `mtime`: Item last contents modification time.
* `name`: Item name.
* `owner`: Item owner name.
* `ownerId`: Item owner id.
* `path`: Item path relative to the project root.
* `perms`: Item permissions (e.g. 0644).
* `size`: Item size in bytes.
* `target`: Item target path, if item is link.
* `type`: Type of the item. Available values are: `file`, ` dir` and `link`.

### Methods ###

Methods on items can be accessed by using the `.` syntax, similar to JavaScript:

~~~
item.method(argument)
~~~

Available methods are:

* `isArchive()`: Is item an archive?
* `isAudio()`: Is item an audio file?
* `isBinary()`: Is item in binary format?
* `isDir()`: Is item a directory?
* `isExecutable()`: Is item executable?
* `isFile()`: Is item a file?
* `isInsideProject()`: Is item inside the project? Mainly useful for link targets.
* `isImage()`: Is item an image?
* `isLink()`: Is item a link?
* `isReadable()`: Is item readable?
* `isText()`: Is item in text format?
* `isVideo()`: Is item a video?
* `isWritable()`: Is item writable?

### Literals ###

Expression syntax supports the following literals:

* strings: Single and double quotes (e.g. "foo")
* numbers: Regular notation (e.g. 42)
* arrays: Using JSON-like notation (e.g. [1, 2])
* hashes: Using JSON-like notation (e.g. { foo: 'bar' })
* booleans: true and false
* null: null

### Operators ###

Expression syntax supports the following operators.

#### Comparison Operators ####

* `==`: Equal
* `===`: Identical
* `!=`: Not equal
* `!==`: Not identical
* `<`: Less than
* `>`: Greater than
* `<=`: Less than or equal to
* `>=`: Greater than or equal to
* `matches`: Regex match

#### Arithmetic Operators ####

* `+`: Addition
* `-`: Subtraction
* `*`: Multiplication
* `/`: Division
* `%`: Modulus
* `**`: Power

#### Numeric Operators ####

* `..`: Range

#### Array Operators ####

* `in`: Contain
* `not in`: Does not contain

#### Logical Operators ####

* `not` or `!`
* `and` or `&&`
* `or` or `||`

#### String Operators ####

* `~`: Concatenation

#### Bitwise Operators ####

* `&`: And
* `|`: Or
* `^`: Xor

#### Ternary Operators ####

* `foo ? "yes" : "no"`: Evaluates to "yes" if `foo` is true and to "no" if not
* `foo ?: "no"`: Equal to foo ? foo : "no"
* `foo ? "yes"`: Equal to foo ? "yes" : ""

**Note:** ProjectLint uses the great [Expression Language](http://symfony.com/doc/current/components/expression_language/syntax.html) Symfony Component so any supported syntax should be usable in ProjectLint expressions.