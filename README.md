# Propel2

Propel2 is an open-source Object-Relational Mapping (ORM) for PHP 5.5 and up.

[![Build Status](https://travis-ci.org/propelorm/Propel2.svg?branch=master)](https://travis-ci.org/propelorm/Propel2)
[![Code Climate](https://codeclimate.com/github/propelorm/Propel2/badges/gpa.svg)](https://codeclimate.com/github/propelorm/Propel2)
<a href="https://codeclimate.com/github/propelorm/Propel2"><img src="https://codeclimate.com/github/propelorm/Propel2/badges/coverage.svg" /></a>
[![Gitter](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/propelorm/Propel)

## Requirements

Propel2 uses the following Symfony2 Components:

* [Console](https://github.com/symfony/Console)
* [Yaml](https://github.com/symfony/Yaml)
* [Finder](https://github.com/symfony/Finder)
* [Validator](https://github.com/symfony/Validator)
* [Filesystem](https://github.com/symfony/Filesystem)

Propel2 also relies on [**Composer**](https://github.com/composer/composer) to manage dependencies but you
also can use [ClassLoader](https://github.com/symfony/ClassLoader) (see the `autoload.php.dist` file for instance).

Propel2 is only supported on PHP 5.5 and up.


## Installation

Read the [Propel documentation](http://propelorm.org/documentation/01-installation.html).

## codeigniter fast propel setup

This fork is dedicated to obtein a better and faster integration of propel reverse database use in codeigniter.
Every model generated extend from CI_Model, every use of namespace is ignored or removed, autoload of models generated into
codeigniter structure directory.
Run propel from a 'models' subdirectory.



## License

See the `LICENSE` file.
