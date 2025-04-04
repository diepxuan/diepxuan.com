Magento 2 module
==================
[![Magento 2](https://img.shields.io/badge/Magento-%3E=2.4-blue.svg)](https://github.com/magento/magento2)
[![Packagist](https://img.shields.io/packagist/v/diepxuan/module-multidomain)](https://packagist.org/packages/diepxuan/module-multidomain)
[![Downloads](https://img.shields.io/packagist/dt/diepxuan/module-multidomain)](https://packagist.org/packages/diepxuan/module-multidomain)
[![License](https://img.shields.io/packagist/l/diepxuan/module-multidomain)](https://packagist.org/packages/diepxuan/module-multidomain)

Multi Domain
------------
* Automatically detects and switches to the target website or store view based on the domain
* Allows selecting a level 2 category as the Root Category of the Store

Installation
------------

The easiest way to install the extension is to use [Composer](https://getcomposer.org/)

Run the following commands:

- ```$ composer require diepxuan/module-multidomain```
- ```$ bin/magento module:enable Diepxuan_MultiDomain```
- ```$ bin/magento setup:upgrade && bin/magento setup:static-content:deploy```
