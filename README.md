# Propel2

Otimized Codeigniter Propel2, is an open-source Object-Relational Mapping (ORM) for PHP 5.5 and up.


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

Run propel from a 'models' subdirectory. In your codeigniter installation config/autoload put:

$autoload['model'] = array('ci_propel_autoloader');

Then in your controller

$this -> load -> model('your_reverse_directory/generated-reversed-database/generated-classes/Table_name','TableName_model');
$this -> TableName_model->isNew();



## License

See the `LICENSE` file.
