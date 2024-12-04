<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-12-04 23:36:01
 */

use Magento\Framework\Component\ComponentRegistrar;

// Standard Registration Module
ComponentRegistrar::register(
    ComponentRegistrar::MODULE,
    'Diepxuan_Dbunsupport',
    __DIR__
);
