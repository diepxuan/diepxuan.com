<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-29 23:43:59
 */

namespace Diepxuan\MultiDomain\Plugin\Framework\App;

use Diepxuan\MultiDomain\Model\StoreSwitch;
use Magento\Framework\App\FrontController as OriginFrontController;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManager;
use Magento\Store\Model\StoreManagerInterface;

class FrontController
{
    /**
     * @var StoreSwitch
     */
    protected $storeSwitch;

    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * FrontController constructor.
     */
    public function __construct(
        StoreSwitch $storeSwitch,
        StoreManagerInterface $storeManager,
    ) {
        $this->storeSwitch  = $storeSwitch;
        $this->storeManager = $storeManager;
    }

    public function aroundDispatch(
        OriginFrontController $subject,
        \Closure $proceed,
        RequestInterface $request
    ) {
        $this->storeManager->setCurrentStore($this->storeSwitch->getStoreId());

        return $proceed($request);
    }
}
