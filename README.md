PHP 7 postscript segfault
=========================

This repo reproduces [PHP bug #74124][1].

To reproduce the bug:

1. Clone this repo
2. Install [Composer][2]
3. Install PHP 7.0 or above
4. Install the ps extension: `sudo pecl install ps`
5. Run `composer install` from the project root directory
6. Run `vendor/bin/phpunit src/PostscriptDocumentTest.php`

[1]: https://bugs.php.net/bug.php?id=74124
[2]: https://getcomposer.org/download/
