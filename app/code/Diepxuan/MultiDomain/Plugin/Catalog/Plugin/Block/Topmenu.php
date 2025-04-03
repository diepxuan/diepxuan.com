<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2025-04-03 22:58:37
 */

namespace Diepxuan\MultiDomain\Plugin\Catalog\Plugin\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\StateDependentCollectionFactory;
use Magento\Framework\Data\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Plugin that enhances the top menu block by building and managing the category tree
 * for menu rendering in a storefront.
 */
class Topmenu extends \Magento\Catalog\Plugin\Block\Topmenu
{
    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var StateDependentCollectionFactory
     */
    private $collectionFactory;

    /**
     * Initialize dependencies.
     */
    public function __construct(
        \Magento\Catalog\Helper\Category $catalogCategory,
        StateDependentCollectionFactory $categoryCollectionFactory,
        StoreManagerInterface $storeManager,
        CategoryRepositoryInterface $categoryRepository
    ) {
        parent::__construct($catalogCategory, $categoryCollectionFactory, $storeManager);
        $this->collectionFactory  = $categoryCollectionFactory;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategoryById($categoryId)
    {
        try {
            return $this->categoryRepository->get($categoryId);
            // Trả về object category
        } catch (NoSuchEntityException $e) {
            return null; // Category không tồn tại
        }
    }

    /**
     * Get Category Tree.
     *
     * @param int $storeId
     * @param int $rootId
     *
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     *
     * @throws LocalizedException
     */
    protected function getCategoryTree($storeId, $rootId)
    {
        $path = $this->getCategoryById($rootId)->getPath();

        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addAttributeToSelect('name');
        $collection->addFieldToFilter('path', ['like' => "{$path}/%"]); // load only from store root
        $collection->addAttributeToFilter('include_in_menu', 1);
        $collection->addIsActiveFilter();
        $collection->addNavigationMaxDepthFilter();
        $collection->addUrlRewriteToResult();
        $collection->addOrder('level', Collection::SORT_ORDER_ASC);
        $collection->addOrder('position', Collection::SORT_ORDER_ASC);
        $collection->addOrder('parent_id', Collection::SORT_ORDER_ASC);
        $collection->addOrder('entity_id', Collection::SORT_ORDER_ASC);

        return $collection;
    }
}
