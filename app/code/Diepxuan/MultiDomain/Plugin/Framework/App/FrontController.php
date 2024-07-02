<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-30 22:01:37
 */

namespace Diepxuan\MultiDomain\Plugin\Framework\App;

use Diepxuan\MultiDomain\Model\StoreSwitch;
use Magento\Framework\App\FrontController as OriginFrontController;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

class FrontController
{
    /**
     * @var StoreSwitch
     */
    protected $storeSwitch;

    /**
     * FrontController constructor.
     */
    public function __construct(
        StoreSwitch $storeSwitch
    ) {
        $this->storeSwitch = $storeSwitch;
    }

    /**
     * Perform action and generate response.
     *
     * @param HttpRequest|RequestInterface $request
     *
     * @return ResponseInterface|ResultInterface
     *
     * @throws \LogicException
     * @throws LocalizedException
     */
    public function aroundDispatch(
        OriginFrontController $subject,
        \Closure $proceed,
        RequestInterface $request
    ) {
        $this->storeSwitch->execute();

        $request->setDispatched(false);

        return $proceed($request);
    }
}
