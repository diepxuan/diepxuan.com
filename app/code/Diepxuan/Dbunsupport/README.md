Allow un-support for Magento 2
==================
[![Packagist](https://img.shields.io/packagist/v/diepxuan/module-dbunsupport)](https://packagist.org/packages/diepxuan/module-dbunsupport)
[![Magento 2](https://img.shields.io/badge/Magento-%3E=2.4-blue.svg)](https://github.com/magento/magento2)
[![Downloads](https://img.shields.io/packagist/dt/diepxuan/module-dbunsupport)](https://packagist.org/packages/diepxuan/module-dbunsupport)
[![License](https://img.shields.io/packagist/l/diepxuan/module-dbunsupport)](https://packagist.org/packages/diepxuan/module-dbunsupport)


Magento 2 Allow Database Compatibility Override
--------------

This module overrides the database compatibility checks in Magento 2 to allow the use of MariaDB versions 10.5 to 10.9, which are not officially supported but are commonly used in development environments.


Installation
------------

The easiest way to install the extension is to use [Composer](https://getcomposer.org/)

Run the following commands:

- ```$ composer require diepxuan/module-dbunsupport```
- ```$ bin/magento module:enable Diepxuan_Dbunsupport```
- ```$ bin/magento setup:upgrade && bin/magento setup:static-content:deploy```
