Magento 2 module
==================
[![Magento 2](https://img.shields.io/badge/Magento-%3E=2.4-blue.svg)](https://github.com/magento/magento2)
[![Packagist](https://img.shields.io/packagist/v/diepxuan/module-eavcleaner)](https://packagist.org/packages/diepxuan/module-eavcleaner)
[![Downloads](https://img.shields.io/packagist/dt/diepxuan/module-eavcleaner)](https://packagist.org/packages/diepxuan/module-eavcleaner)
[![License](https://img.shields.io/packagist/l/diepxuan/module-eavcleaner)](https://packagist.org/packages/diepxuan/module-eavcleaner)

EAV Cleaner Console
-------------------
* Provide EAV cleanup.

Commands
--------

* `eav:attributes:restoredefault` Remove the storeview product attribute values.

Installation
------------

The easiest way to install the extension is to use [Composer](https://getcomposer.org/)

Run the following commands:

- ```$ composer require diepxuan/module-eavcleaner```
- ```$ bin/magento module:enable Diepxuan_EAVCleaner```
- ```$ bin/magento setup:upgrade && bin/magento setup:static-content:deploy```