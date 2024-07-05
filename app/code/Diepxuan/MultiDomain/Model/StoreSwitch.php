<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-07-05 17:53:55
 */

namespace Diepxuan\MultiDomain\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Api\StoreResolverInterface;
use Magento\Store\Model\BaseUrlChecker;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface;

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
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * @var bool
     */
    protected $isInitialized = false;

    /**
     * @var bool
     */
    protected $storeId = false;

    /**
     * @var StoreResolverInterface
     */
    protected $storeResolver;

    /**
     * StoreSwitch constructor.
     */
    public function __construct(
        Context $context,
        Registry $registry,
        BaseUrlChecker $baseUrlChecker,
        RequestInterface $request,
        StoreRepositoryInterface $storeRepository,
        StoreManagerInterface $storeManager,
        StoreResolverInterface $storeResolver
    ) {
        parent::__construct($context, $registry);

        $this->baseUrlChecker  = $baseUrlChecker;
        $this->request         = $request;
        $this->storeRepository = $storeRepository;
        $this->storeManager    = $storeManager;
        $this->storeResolver   = $storeResolver;
    }

    /**
     * Get store id.
     *
     * @return bool|int
     */
    public function getStoreId()
    {
        if ($this->storeId) {
            return $this->storeId;
        }
        $isSecure = $this->request->isSecure();
        foreach ($this->storeRepository->getList() as $store) {
            $baseUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_WEB, $isSecure);
            if ($this->_baseUrlChecker($baseUrl)) {
                $this->storeId = $store->getId();

                return $this->storeId;
            }
        }

        return $this->storeId;
    }

    public function execute(): void
    {
        if ($this->getStoreId() && $this->getStoreId() !== $this->storeResolver->getCurrentStoreId()) {
            $this->storeResolver->getCurrentStoreId();
            $this->storeManager->setCurrentStore($this->getStoreId());
        }
    }

    /**
     * Gets base URL checker.
     *
     * @return BaseUrlChecker
     */
    public function getBaseUrlChecker()
    {
        return $this->baseUrlChecker;
    }

    /**
     * Follow Magento\Store\Model\BaseUrlChecker.
     *
     * @param mixed $baseUrl Valid current request with store base url
     *
     * @return bool
     */
    private function _baseUrlChecker($baseUrl)
    {
        // return $this->baseUrlChecker->execute(parse_url($baseUrl), $request);
        extract(parse_url($baseUrl));
        $request = $this->request;
        if (!isset($scheme) || !isset($host) || !isset($path)) {
            return false;
        }
        $isValidSchema = $scheme === $request->getScheme();
        $isValidHost   = $host === $request->getHttpHost();
        $isValidHost   = $isValidHost || $host === "www.{$request->getHttpHost()}";
        $isValidPath   = str_contains($request->getRequestUri() ?: '/', (string) $path);

        return $isValidSchema
            && $isValidHost
            && $isValidPath
            || $this->baseUrlChecker->execute(parse_url($baseUrl), $request);
    }
}
