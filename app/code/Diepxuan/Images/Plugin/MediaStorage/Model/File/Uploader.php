<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-26 16:39:47
 */

namespace Diepxuan\Images\Plugin\MediaStorage\Model\File;

use Diepxuan\Images\Model\Extension;
use Magento\MediaStorage\Model\File\Uploader as OriginUploader;

class Uploader
{
    private $extension;

    public function __construct(
        Extension $extension
    ) {
        $this->extension = $extension;
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

    /**
     * Check if specified extension is allowed.
     *
     * @return bool
     */
    public function afterCheckAllowedExtension(OriginUploader $uploader, bool $flag)
    {
        return $flag || \in_array(strtolower($uploader->getFileExtension()), $this->extension->getAllowedExtensions(), true);
    }
}
