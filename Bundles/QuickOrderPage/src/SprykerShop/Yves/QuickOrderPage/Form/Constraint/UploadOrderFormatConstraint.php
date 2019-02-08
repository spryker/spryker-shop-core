<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use SprykerShop\Yves\QuickOrderPage\File\Parser\FileValidatorInterface;
use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UploadOrderFormatConstraint extends SymfonyConstraint
{
    public const OPTION_BUNDLE_CONFIG = 'config';
    public const OPTION_UPLOADED_FILE_VALIDATOR = 'uploadedFileValidator';

    protected const ERROR_MESSAGE_INVALID_MIME_TYPE = 'quick-order.upload-order.errors.upload-order-invalid-mime-type';
    protected const ERROR_MESSAGE_INVALID_AMOUNT_OF_ROWS = 'quick-order.upload-order.errors.upload-order-invalid-amount-of-rows';
    protected const ERROR_MESSAGE_INVALID_FORMAT = 'quick-order.upload-order.errors.upload-order-invalid-format';
    protected const ERROR_MESSAGE_NO_FILE = 'quick-order.upload-order.errors.upload-order-no-file';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\File\Parser\FileValidatorInterface
     */
    protected $uploadedFileValidator;

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\File\Parser\FileValidatorInterface
     */
    public function getUploadedFileValidator(): FileValidatorInterface
    {
        return $this->uploadedFileValidator;
    }

    /**
     * @return int
     */
    public function getUploadRowCountLimit(): int
    {
        return $this->config->getUploadRowCountLimit();
    }

    /**
     * @return string
     */
    public function getInvalidFormatMessage(): string
    {
        return static::ERROR_MESSAGE_INVALID_FORMAT;
    }

    /**
     * @return string
     */
    public function getInvalidMimeTypeMessage(): string
    {
        return static::ERROR_MESSAGE_INVALID_MIME_TYPE;
    }

    /**
     * @return string
     */
    public function getNoFileMessage(): string
    {
        return static::ERROR_MESSAGE_NO_FILE;
    }

    /**
     * @return string
     */
    public function getInvalidAmountOfRowsMessage(): string
    {
        return static::ERROR_MESSAGE_INVALID_AMOUNT_OF_ROWS;
    }
}
