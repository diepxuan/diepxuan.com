<?php
/**
 * Tests that existing payment.xml files are valid to schema individually and merged.
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Test\Integrity\Modular;

use Magento\Framework\Component\ComponentRegistrar;

class PaymentConfigFilesTest extends \Magento\TestFramework\TestCase\AbstractConfigFiles
{
    /**
     * Returns the reader class name that will be instantiated via ObjectManager
     *
     * @return string reader class name
     */
    protected function _getReaderClassName()
    {
        return \Magento\Payment\Model\Config\Reader::class;
    }

    /**
     * Returns a string that represents the path to the config file
     *
     * @return string
     */
    protected static function _getConfigFilePathGlob()
    {
        return 'etc/payment.xml';
    }

    /**
     * Returns an absolute path to the XSD file corresponding to the XML files specified in _getConfigFilePathGlob
     *
     * @return string
     */
    protected static function _getXsdPath()
    {
        return self::$componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Magento_Payment')
            . '/etc/payment_file.xsd';
    }
}
