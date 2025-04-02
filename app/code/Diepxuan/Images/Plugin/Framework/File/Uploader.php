<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-26 16:39:39
 */

namespace Diepxuan\Images\Plugin\Framework\File;

use Diepxuan\Images\Model\Extension;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\File\Uploader as OriginUploader;

class Uploader extends Action
{
    private $extension;

    public function __construct(
        Context $context,
        Extension $extension
    ) {
        $this->extension = $extension;
        parent::__construct($context);
    }

    /**
     * Set allowed extensions.
     *
     * @param string[] $extensions
     *
     * @return $this
     */
    public function beforeSetAllowedExtensions(OriginUploader $uploader, $extensions = [])
    {
        return array_merge(
            (array) $extensions,
            $this->extension->getAllowedExtensions()
        );
    }

    public function execute(): void {}
}
