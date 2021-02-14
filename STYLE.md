# Swiftly - Style Guide
## About

Swiftly and all it's component libraries use a style based losely on the popular
PSR-2 and PSR-12 standards. While our code makes generous use of most of the
patterns established in those specifications, we've also taken the liberty to
extend and amend them in places where it makes sense to do so to better suit our
own preferences and the needs of the framework.

The goals of our style guide are simple:

* Increase readability
* Allow code to be self documenting
* Reduce cognitive overhead required to understand code
* Easier onboarding for new contributors
* Make future maintenance easier

By applying these styles uniformly to all parts of the Swiftly codebase, we hope
to make working on the project that much nicer and shorten the time required to
start writing new code.

## Index

1. [Files](#files)
    1. [Naming](#naming)
    2. [Format](#format)
    3. [Lines](#lines)
    4. [Indenting](#indenting)
2. [Meta](#meta)
    1. [Tags](#tags)
    2. [Namespace](#namespace)
    3. [Use](#use)
        1. [Classes](#classes)
        2. [Functions](#functions)
        3. [Constants](#constants)

## Files
### Naming
### Format
### Lines
### Indenting

## Meta
### Tags

The opening `<?php` tag should be the first thing in any PHP file, immediately
followed by a newline.

The only possible exception to this rule MAY be in mixed PHP/markup files, but
we advise all files SHOULD have an opening file-level PHP docblock comment with
a short explanation of the file contents.

**Good**

```php
<?php
/**
 * A short description of this file
 *
 * @package ...
 * @author ...
 */
```

**Okay**

```php
<html>
    <head>...</head>
    <body>
        <?php
            // Do stuff!
        ?>
    </body>
</html>
```

**Bad**

```php

<?php $name = '';
echo $name;
```

### Namespace

Namespace declarations SHOULD be the next element in the file after the opening
tag and comment. Namespaces MUST follow _PascalCasing_ and MUST use the
following standard:

```php
namespace VendorName\Component\SubComponent;
```

Each namespace MUST start with the vendor name, which in our case is always just
`Swiftly`, followed by the name(s) of the component(s) to which this file
belongs. Component names can be nested as many times as neccessary and should
reflect the folder structure.

The declaration itself SHOULD sit 2 lines under the PHP opening tag or opening
comment (if it exists).

**Good**

```php
<?php
/**
 * Example file description
 *
 * @package ...
 * @author ...
 */

namespace Swiftly\Http\Server;
```

**Bad**

```php
<?php
namespace swiftly\http\Server;
```

### Use

Importing components from other namespaces is the preferred method of using
external classes, functions and constants. Use statements SHOULD sit 2 lines
under the namespace declaration.

#### Classes

Class imports should be the first `use` statements and take up single lines with
no line breaks inbetween.

**Good**

```php
<?php
/**
 * ...
 */

namespace Swiftly\Http\Server;

use Swiftly\Http\Headers;
use Swiftly\Http\Cookies;
```

If importing two or more components from the same namespace, you MAY opt to
combine the imports into a group declaration. Developers MUST NOT nest
namespaces within the group declaration as this makes the statement harder to
scan.

**Good**

```php
<?php
/**
 * ...
 */

namespace Swiftly\Http\Server;

use Swiftly\Http\{
    Headers,
    Cookies
};
```

**Bad**

```php
<?php
/**
 * ...
 */

namespace Swiftly\Http\Server;

use Swiftly\Http\{
    Client\TransportInterface,
    Client\Transport\CurlTransport, // Too much nesting!
    Server\JsonResponse,
    Headers
};
```

#### Functions

#### Constants
