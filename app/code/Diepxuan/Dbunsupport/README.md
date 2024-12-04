Allow un-support for Magento 2
==================
[![Latest version](https://img.shields.io/badge/latest-0.0.1-green.svg)](https://github.com/diepxuan/module-sitemap)
[![Packagist](https://img.shields.io/badge/packagist-0.0.1-green.svg)](https://packagist.org/packages/diepxuan/module-sitemap)
[![Magento 2](https://img.shields.io/badge/Magento-%3E=2.4-blue.svg)](https://github.com/magento/magento2/tree/2.4.4)
[![PHP >= 7.4](https://img.shields.io/badge/PHP-%3E=7.4-blue.svg)](https://packagist.org/packages/diepxuan/module-sitemap)

Allow Magento 2 un-support
--------------

Override Module to allow MariaDB 10.5 to 10.9 support for development


Installation
------------

The easiest way to install the extension is to use [Composer](https://getcomposer.org/)

Run the following commands:

- ```$ composer require diepxuan/module-dbunsupport```
- ```$ bin/magento module:enable Diepxuan_Dbunsupport```
- ```$ bin/magento setup:upgrade && bin/magento setup:static-content:deploy```
