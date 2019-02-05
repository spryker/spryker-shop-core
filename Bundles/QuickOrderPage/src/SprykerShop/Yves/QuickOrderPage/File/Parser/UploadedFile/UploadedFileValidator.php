<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\File\Parser\UploadedFile;

use SprykerShop\Yves\QuickOrderPage\File\Parser\FileValidatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileValidator implements FileValidatorInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileValidatorStrategyPluginInterface[]
     */
    protected $quickOrderFileValidatorPlugins;

    /**
     * @param \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileValidatorStrategyPluginInterface[] $quickOrderFileValidatorPlugins
     */
    public function __construct(array $quickOrderFileValidatorPlugins)
    {
        $this->quickOrderFileValidatorPlugins = $quickOrderFileValidatorPlugins;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isValidMimeType(UploadedFile $file): bool
    {
        foreach ($this->quickOrderFileValidatorPlugins as $quickOrderFileValidatorPlugin) {
            if ($quickOrderFileValidatorPlugin->isApplicable($file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isValidFormat(UploadedFile $file): bool
    {
        foreach ($this->quickOrderFileValidatorPlugins as $quickOrderFileValidatorPlugin) {
            if ($quickOrderFileValidatorPlugin->isApplicable($file)) {
                return $quickOrderFileValidatorPlugin->isValidFormat($file);
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $maxAllowedRows
     *
     * @return bool
     */
    public function isValidAmountOfRows(UploadedFile $file, int $maxAllowedRows): bool
    {
        foreach ($this->quickOrderFileValidatorPlugins as $quickOrderFileValidatorPlugin) {
            if ($quickOrderFileValidatorPlugin->isApplicable($file)) {
                return $quickOrderFileValidatorPlugin->isValidAmountOfRows($file, $maxAllowedRows);
            }
        }

        return false;
    }
}
