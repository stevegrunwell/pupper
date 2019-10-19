# Pupper

Pupper is a Twitter clone, built for the purpose of demonstrating basic Laravel functionality (as well as automated testing).

## Installation

The project comes pre-packaged with a [Laravel Homestead instance](https://laravel.com/docs/master/homestead), which contains everything needed to run the project.

### Host machine requirements and installation

* [Composer](https://getcomposer.org/download/) — PHP package manager, used for installing dependencies
* [Vagrant](https://www.vagrantup.com/downloads.html) — Virtual Machine manager
* At least **one** of the following virtualization platforms:
    - [VirtualBox](https://www.virtualbox.org/wiki/Downloads)
    - [VMWare](https://www.vmware.com/)
    - [Parallels](https://www.parallels.com/products/desktop/)
    - [Hyper-V](https://docs.microsoft.com/en-us/virtualization/hyper-v-on-windows/quick-start/enable-hyper-v)
* [Vagrant Host Manager plugin](https://github.com/devopsgroup-io/vagrant-hostmanager) (required in some environments)

Once those dependencies are satisfied, clone the project from GitHub and install its dependencies:

```sh
# Clone the project to a local directory
$ git clone git@github.com:stevegrunwell/pupper.git

# Move into the project directory
$ cd pupper

# Install Composer dependencies
$ composer install --ignore-platform-reqs
```

Don't worry about the version of PHP you're running locally, the `composer.json` file tells Composer to install as though we're using PHP 7.3 (which will be pre-configured in the Homestead virtual machine).

### Creating the Homestead VM

Once the project has been cloned and its dependencies installed, it's time to start up our Laravel Homestead virtual machine!

This process has been heavily-scripted for you, so getting up and running with the project should be as simple as:

```sh
# Create the necessary Homestead files
$ composer homestead

# Provision the virtual machine
$ vagrant up
```

If everything worked properly, Pupper should be accessible via <https://pupper.test>.

## Running tests

The application test suite is written using [PHPUnit](https://phpunit.de/), installed via Composer. The test suite may be run at any time by running the following from within the VM:

```sh
$ ./vendor/bin/phpunit
```

You may also use the following Composer script from within the Homestead VM to run all tests (PHPUnit, coding standards, static code analysis, etc.):

```sh
$ composer test
```

### Coding standards

Pupper is written according to the [PSR-2 coding standard](https://www.php-fig.org/psr/psr-2/) and ships with configurations for [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [PHP Coding Standards Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) for verifying adherence to these standards.

You may run the coding standards checks at any time by running the following Composer script from within the VM:

```sh
$ composer coding-standards
```

### Static code analysis

The Pupper repository also includes a [PHPStan](https://github.com/phpstan/phpstan) configuration for performing static code analysis:

```sh
$ composer static-analysis
```


### Code coverage reports

A custom Composer script has been registered to generate HTML code coverage reports, which help highlight areas that may be under-tested.

To generate the reports, run the following:

```sh
$ composer test-coverage
```

The reports will be available at <https://pupper.test/test-coverage>.

## Credits

Iconography for the application comes from [The Noun Project](https://thenounproject.com/), and is used under a Creative-Commons License:

* [Dog by Sara Quintana](https://thenounproject.com/term/dog/62011)
