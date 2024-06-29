<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-29 23:48:46
 */

namespace Diepxuan\MultiDomain\Model;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Store\Model\BaseUrlChecker;

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
     * Get store id.
     *
     * @return bool|int
     */
    public function getStoreId()
    {
        $isSecure = $this->request->isSecure();
        foreach ($this->storeRepository->getList() as $store) {
            $baseUrl = $store->getBaseUrl(UrlInterface::URL_TYPE_WEB, $isSecure);
            if ($this->_baseUrlChecker($baseUrl)) {
                $this->storeId = $store->getId();

                break;
            }
        }

        return $this->storeId;
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
        extract(parse_url($baseUrl));
        $request       = $this->request;
        $requestUri    = $request->getRequestUri() ?: '/';
        $isValidSchema = !isset($scheme) || $scheme       === $request->getScheme();
        $isValidHost   = !isset($host)   || $host         === $request->getHttpHost();
        $isValidHost   = $isValidHost    || "www.{$host}" === $request->getHttpHost();
        $isValidPath   = !isset($path)   || str_contains($requestUri, (string) $path);

        return $isValidSchema
            && $isValidHost
            && $isValidPath
            || $this->baseUrlChecker->execute(parse_url($baseUrl), $request);
    }
}
