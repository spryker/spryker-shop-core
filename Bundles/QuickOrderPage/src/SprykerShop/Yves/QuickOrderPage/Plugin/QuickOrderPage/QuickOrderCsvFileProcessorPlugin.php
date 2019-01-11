<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Shared\QuickOrderPage\QuickOrderPageConfig;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorPluginInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class QuickOrderCsvFileProcessorPlugin extends AbstractPlugin implements QuickOrderFileProcessorPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isApplicable(UploadedFile $file): bool
    {
        return in_array($file->getClientMimeType(), $this->getAllowedMimeTypes());
    }

    /**
     * @return string[]
     */
    public function getAllowedMimeTypes(): array
    {
        return [QuickOrderPageConfig::CSV_FILE_MIME_TYPE];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function isValidFormat(UploadedFile $file): bool
    {
        return $this->getFactory()->createCsvFileValidator()->validateFormat($file);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param int $maxAllowedLines
     *
     * @return bool
     */
    public function isValidAmountOfRows(UploadedFile $file, int $maxAllowedLines): bool
    {
        $csvFileValidator = $this->getFactory()->createCsvFileValidator();

        return $csvFileValidator->validateAmountOfRows($file, $maxAllowedLines);
    }
}
