<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-29 18:37:38
 */

namespace Diepxuan\MultiDomain\Plugin\Framework\App;

use Diepxuan\MultiDomain\Model\StoreSwitch;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class FrontController
{
    /**
     * @var StoreSwitch
     */
    protected $storeSwitch;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * FrontController constructor.
     */
    public function __construct(
        StoreSwitch $storeSwitch,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        $this->storeSwitch  = $storeSwitch;
        $this->storeManager = $storeManager;
        $this->logger       = $logger;
    }

    public function aroundDispatch(
        \Magento\Framework\App\FrontController $subject,
        \Closure $proceed,
        RequestInterface $request
    ) {
        if ($this->notNeededProcess()) {
            $this->storeManager->setCurrentStore($this->storeSwitch->getStoreId());
        }

        return $proceed($request);
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @return bool
     */
    protected function isNeededProcess()
    {
        return !$this->storeSwitch->isInitialized();
    }

    /**
     * @return bool
     */
    protected function notNeededProcess()
    {
        return true;
    }
}
