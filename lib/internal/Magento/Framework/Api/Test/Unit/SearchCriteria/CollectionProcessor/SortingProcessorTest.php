<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Framework\Api\Test\Unit\SearchCriteria\CollectionProcessor;

use Magento\Framework\Api\SearchCriteria\CollectionProcessor\SortingProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Data\Collection;
use Magento\Framework\Data\Collection\AbstractDb;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SortingProcessorTest extends TestCase
{
    /**
     * Return model
     *
     * @param array $fieldMapping
     * @param array $defaultOrders
     * @return SortingProcessor
     */
    private function getModel(array $fieldMapping, array $defaultOrders)
    {
        return new SortingProcessor($fieldMapping, $defaultOrders);
    }

    public function testProcess()
    {
        $orderOneField = 'orderOneField';
        $orderOneFieldMapped = 'orderOneFieldMapped';
        $orderOneDirection = SortOrder::SORT_ASC;

        $orderTwoField = 'orderTwoField';
        $orderTwoDirection = SortOrder::SORT_DESC;

        $orderThreeField = 'orderTwoField';
        $orderThreeDirection = '!!@!@';

        $fieldMapping = [$orderOneField => $orderOneFieldMapped];

        $defaultOrders = ['orderTwoField' => 'DESC'];

        $model = $this->getModel($fieldMapping, $defaultOrders);

        /** @var SortOrder|MockObject $sortOrderOneMock */
        $sortOrderOneMock = $this->getMockBuilder(SortOrder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sortOrderOneMock->expects($this->once())
            ->method('getField')
            ->willReturn($orderOneField);
        $sortOrderOneMock->expects($this->once())
            ->method('getDirection')
            ->willReturn($orderOneDirection);

        /** @var SortOrder|MockObject $sortOrderTwoMock */
        $sortOrderTwoMock = $this->getMockBuilder(SortOrder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sortOrderTwoMock->expects($this->once())
            ->method('getField')
            ->willReturn($orderTwoField);
        $sortOrderTwoMock->expects($this->once())
            ->method('getDirection')
            ->willReturn($orderTwoDirection);

        /** @var SortOrder|MockObject $sortOrderThreeMock */
        $sortOrderThreeMock = $this->getMockBuilder(SortOrder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sortOrderThreeMock->expects($this->once())
            ->method('getField')
            ->willReturn($orderThreeField);
        $sortOrderThreeMock->expects($this->once())
            ->method('getDirection')
            ->willReturn($orderThreeDirection);

        /** @var SortOrder|MockObject $sortOrderThreeMock */
        $sortOrderFourMock = $this->getMockBuilder(SortOrder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $sortOrderFourMock->expects($this->once())
            ->method('getField')
            ->willReturn(null);
        $sortOrderFourMock->expects($this->never())
            ->method('getDirection');

        /** @var SearchCriteriaInterface|MockObject $searchCriteriaMock */
        $searchCriteriaMock = $this->getMockBuilder(SearchCriteriaInterface::class)
            ->getMock();

        $searchCriteriaMock->expects($this->exactly(2))
            ->method('getSortOrders')
            ->willReturn([$sortOrderOneMock, $sortOrderTwoMock, $sortOrderThreeMock, $sortOrderFourMock]);

        /** @var AbstractDb|MockObject $collectionMock */
        $collectionMock = $this->getMockBuilder(AbstractDb::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collectionMock->expects($this->exactly(3))
            ->method('addOrder')
            ->willReturnCallback(
                function (
                    $arg1,
                    $arg2
                ) use (
                    $orderOneFieldMapped,
                    $orderOneDirection,
                    $orderTwoField,
                    $orderTwoDirection,
                    $orderThreeField,
                    $collectionMock
                ) {
                    if ($arg1 == $orderOneFieldMapped && $arg2 == $orderOneDirection) {
                        return $collectionMock;
                    } elseif ($arg1 == $orderTwoField && $arg2 == $orderTwoDirection) {
                        return $collectionMock;
                    } elseif ($arg1 == $orderThreeField && $arg2 == Collection::SORT_ORDER_DESC) {
                        return $collectionMock;
                    }
                }
            );

        $model->process($searchCriteriaMock, $collectionMock);
    }

    public function testProcessWithDefaults()
    {
        $defaultOneField = 'defaultOneField';
        $defaultOneFieldMapped = 'defaultOneFieldMapped';
        $defaultOneDirection = SortOrder::SORT_ASC;

        $defaultTwoField = 'defaultTwoField';
        $defaultTwoDirection = SortOrder::SORT_DESC;

        $defaultThreeField = 'defaultThreeField';
        $defaultThreeDirection = '$#%^';

        $fieldMapping = [$defaultOneField => $defaultOneFieldMapped];

        $defaultOrders = [
            $defaultOneField => $defaultOneDirection,
            $defaultTwoField => $defaultTwoDirection,
            $defaultThreeField => $defaultThreeDirection,
        ];

        $model = $this->getModel($fieldMapping, $defaultOrders);

        /** @var SearchCriteriaInterface|MockObject $searchCriteriaMock */
        $searchCriteriaMock = $this->getMockBuilder(SearchCriteriaInterface::class)
            ->getMock();

        $searchCriteriaMock->expects($this->once())
            ->method('getSortOrders')
            ->willReturn([]);

        /** @var AbstractDb|MockObject $collectionMock */
        $collectionMock = $this->getMockBuilder(AbstractDb::class)
            ->disableOriginalConstructor()
            ->getMock();

        $collectionMock->expects($this->exactly(3))
            ->method('addOrder')
            ->willReturnCallback(function (
                $arg1,
                $arg2
            ) use (
                $collectionMock,
                $defaultOneFieldMapped,
                $defaultOneDirection,
                $defaultTwoField,
                $defaultTwoDirection,
                $defaultThreeField
            ) {
                if ($arg1 == $defaultOneFieldMapped && $arg2 == $defaultOneDirection) {
                    return $collectionMock;
                } elseif ($arg1 == $defaultTwoField && $arg2 == $defaultTwoDirection) {
                    return $collectionMock;
                } elseif ($arg1 == $defaultThreeField && $arg2 == Collection::SORT_ORDER_DESC) {
                    return $collectionMock;
                }
            });

        $model->process($searchCriteriaMock, $collectionMock);
    }
}
