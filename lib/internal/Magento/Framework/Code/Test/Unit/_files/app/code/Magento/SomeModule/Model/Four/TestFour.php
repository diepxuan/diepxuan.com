<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SomeModule\Model\Four;

require_once __DIR__ . '/../One/TestOne.php';
require_once __DIR__ . '/../ElementFactory.php';
require_once __DIR__ . '/../Proxy.php';
class TestFour extends \Magento\SomeModule\Model\One\TestOne
{
    /**
     * @var \Magento\SomeModule\Model\ElementFactory
     */
    protected $_factory;

    /**
     * Test constructor.
     * @param \Magento\SomeModule\Model\Proxy $proxy
     * @param \Magento\SomeModule\Model\ElementFactory $factory
     */
    public function __construct(
        \Magento\SomeModule\Model\Proxy $proxy,
        \Magento\SomeModule\Model\ElementFactory $factory
    ) {
        $this->_factory = $factory;
        parent::__construct($proxy, $factory);
    }
}
