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

Once those dependencies are satisfied, clone the project from GitHub and install its dependencies:

```sh
# Clone the project to a local directory
$ git clone git@github.com:stevegrunwell/pupper.git

# Move into the project directory
$ cd pupper

# Install Composer dependencies
$ composer install
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

## Credits

Iconography for the application comes from [The Noun Project](https://thenounproject.com/), and is used under a Creative-Commons License:

* [Dog by Sara Quintana](https://thenounproject.com/term/dog/62011)
