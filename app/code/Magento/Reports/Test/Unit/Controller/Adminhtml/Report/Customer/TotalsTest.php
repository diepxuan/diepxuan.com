<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Reports\Test\Unit\Controller\Adminhtml\Report\Customer;

use Magento\Framework\DataObject;
use Magento\Framework\Phrase;
use Magento\Framework\View\Page\Title;
use Magento\Reports\Controller\Adminhtml\Report\Customer\Totals;
use Magento\Reports\Test\Unit\Controller\Adminhtml\Report\AbstractControllerTestCase;

class TotalsTest extends AbstractControllerTestCase
{
    /**
     * @var Totals
     */
    protected $totals;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->totals = new Totals(
            $this->contextMock,
            $this->fileFactoryMock
        );
    }

    /**
     * @return void
     */
    public function testExecute(): void
    {
        $titleMock = $this->getMockBuilder(Title::class)
            ->disableOriginalConstructor()
            ->getMock();
        $titleMock
            ->expects($this->once())
            ->method('prepend')
            ->with(new Phrase('Order Total Report'));

        $this->viewMock
            ->expects($this->any())
            ->method('getPage')
            ->willReturn(
                new DataObject(
                    ['config' => new DataObject(
                        ['title' => $titleMock]
                    )]
                )
            );

        $this->menuBlockMock
            ->expects($this->once())
            ->method('setActive')
            ->with('Magento_Reports::report_customers_totals');
        $this->breadcrumbsBlockMock
            ->method('addLink')
            ->willReturnCallback(
                function ($arg1, $arg2) {
                    if ($arg1 == new Phrase('Reports') && $arg2 == new Phrase('Reports')) {
                        return null;
                    } elseif ($arg1 == new Phrase('Customers') && $arg2 == new Phrase('Customers')) {
                        return null;
                    } elseif ($arg1 == new Phrase('Customers by Orders Total') &&
                        $arg2 == new Phrase('Customers by Orders Total')) {
                        return null;
                    }
                }
            );
        $this->totals->execute();
    }
}
