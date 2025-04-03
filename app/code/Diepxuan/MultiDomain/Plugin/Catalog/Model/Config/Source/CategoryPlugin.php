<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-04-03 16:24:31
 */

namespace Diepxuan\MultiDomain\Plugin\Catalog\Model\Config\Source;

use Magento\Catalog\Model\Config\Source\Category;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class CategoryPlugin
{
    /**
     * Category collection factory.
     *
     * @var CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * Construct.
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory
    ) {
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Chỉnh sửa danh sách category được trả về từ toOptionArray().
     *
     * @return array
     */
    public function afterToOptionArray(Category $subject, array $options)
    {
        /** @var Collection $collection */
        $collection = $this->_categoryCollectionFactory->create();

        $collection->addAttributeToSelect('name')
            ->addFieldToFilter('path', ['neq' => '1'])
            // ->addLevelFilter(2)
            ->addFieldToFilter('level', ['eq' => 2])
            ->load()
        ;
        foreach ($collection as $category) {
            if (2 !== $this->getLevel($category->getPath())) {
                continue;
            }
            $options[] = [
                'label' => $category->getName(),
                'value' => $category->getId(),
            ];
        }

        return $options;
    }

    private function getLevel($path)
    {
        return substr_count($path, '/');
    }
}
