<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Form\Constraint;

use Symfony\Component\Validator\Constraint as SymfonyConstraint;

class UploadOrderFormatConstraint extends SymfonyConstraint
{
    public const OPTION_BUNDLE_CONFIG = 'config';
    public const OPTION_FILE_PROCESSOR_PLUGINS = 'plugins';

    protected const MESSAGE_INVALID_MIME_TYPE = 'quick-order.upload-order.errors.upload-order-invalid-mime-type';
    protected const MESSAGE_INVALID_AMOUNT_OF_ROWS = 'quick-order.upload-order.errors.upload-order-invalid-amount-of-rows';
    protected const MESSAGE_INVALID_FORMAT = 'quick-order.upload-order.errors.upload-order-invalid-format';

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    protected $config;

    /**
     * @var \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorPluginInterface[]
     */
    protected $plugins;

    /**
     * @return string[]
     */
    public function getAllowedMimeTypes(): array
    {
        $mimeTypes = [];
        foreach ($this->plugins as $plugin) {
            $mimeTypes = array_merge($mimeTypes, $plugin->getAllowedMimeTypes());
        }

        return $mimeTypes;
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderFileProcessorPluginInterface[]
     */
    public function getFileProcessorPlugins(): array
    {
        return $this->plugins;
    }

    /**
     * @return int
     */
    public function getUploadOrderMaxAllowedLines(): int
    {
        return $this->config->getUploadOrderMaxAllowedLines();
    }

    /**
     * @return string
     */
    public function getInvalidFormatMessage(): string
    {
        return static::MESSAGE_INVALID_FORMAT;
    }

    /**
     * @return string
     */
    public function getInvalidMimeTypeMessage(): string
    {
        return static::MESSAGE_INVALID_MIME_TYPE;
    }

    /**
     * @return string
     */
    public function getInvalidAmountOfRowsMessage(): string
    {
        return static::MESSAGE_INVALID_AMOUNT_OF_ROWS;
    }
}
