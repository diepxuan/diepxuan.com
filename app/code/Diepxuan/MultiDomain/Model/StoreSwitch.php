<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-29 18:31:58
 */

namespace Diepxuan\MultiDomain\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\BaseUrlChecker;
use Psr\Log\LoggerInterface;

/**
 * Class StoreSwitch.
 */
class StoreSwitch extends AbstractModel
{
    /**
     * @var BaseUrlChecker
     */
    protected $baseUrlChecker;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * @var bool
     */
    protected $storeId = false;

    /**
     * StoreSwitch constructor.
     */
    public function __construct(
        Context $context,
        Registry $registry,
        BaseUrlChecker $baseUrlChecker,
        RequestInterface $request,
        StoreRepositoryInterface $storeRepository
    ) {
        parent::__construct($context, $registry);

        $this->baseUrlChecker  = $baseUrlChecker;
        $this->request         = $request;
        $this->storeRepository = $storeRepository;
    }

    /**
     * @return bool|int
     */
    public function getStoreId()
    {
        if ($this->isInitialized()) {
            return $this->storeId;
        }

        $isSecure = $this->request->isSecure();

        foreach ($this->storeRepository->getList() as $store) {
            $baseUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_WEB, $isSecure);
            if ($this->baseUrlChecker($baseUrl)) {
                $this->isInitialized = true;
                $this->storeId       = $store->getId();
                $this->getLogger()->critical($store->getName());

                break;
            }
        }

        return $this->storeId;
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->isInitialized;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->_logger;
    }

    /**
     * @param mixed $baseUrl
     *
     * @return bool
     */
    protected function baseUrlChecker($baseUrl)
    {
        return $this->baseUrlChecker->execute(parse_url($baseUrl), $this->request);
    }
}
