<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\LayeredNavigation\Block\Navigation\Search;

use Magento\Catalog\Model\Layer\Filter\AbstractFilter;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\LayeredNavigation\Block\Navigation\Category\DecimalFilterTest as CategoryDecimalFilterTest;

/**
 * Provides tests for custom price filter in navigation block on search page.
 *
 * @magentoAppArea frontend
 * @magentoAppIsolation enabled
 * @magentoDbIsolation disabled
 */
class DecimalFilterTest extends CategoryDecimalFilterTest
{
    /**
     * @magentoDataFixture Magento/Catalog/_files/product_decimal_attribute.php
     * @magentoDataFixture Magento/Catalog/_files/category_with_different_price_products.php
     * @dataProvider getFiltersWithCustomAttributeDataProvider
     * @param array $products
     * @param array $attributeData
     * @param array $expectation
     * @return void
     */
    public function testGetFiltersWithCustomAttribute(
        array $products,
        array $attributeData,
        array $expectation
    ): void {
        $this->getSearchFiltersAndAssert($products, $attributeData, $expectation);
    }

    /**
     * @return array
     */
    public static function getFiltersWithCustomAttributeDataProvider(): array
    {
        $dataProvider = parent::getFiltersWithCustomAttributeDataProvider();

        $dataProvider = array_replace_recursive(
            $dataProvider,
            [
                'not_used_in_navigation' => [
                    'attributeData' => ['is_filterable' => 0, 'is_filterable_in_search' => 0],
                ],

                /* @TODO: Should be uncommented in MC-16650 */

                /*'used_in_navigation_with_results' => [
                    'attribute_data' => [
                        'is_filterable' => AbstractFilter::ATTRIBUTE_OPTIONS_ONLY_WITH_RESULTS,
                        'is_filterable_in_search' => 1,
                    ],
                ],*/
            ]
        );

        return $dataProvider;
    }

    /**
     * @inheritdoc
     */
    protected function getLayerType(): string
    {
        return Resolver::CATALOG_LAYER_SEARCH;
    }
}
