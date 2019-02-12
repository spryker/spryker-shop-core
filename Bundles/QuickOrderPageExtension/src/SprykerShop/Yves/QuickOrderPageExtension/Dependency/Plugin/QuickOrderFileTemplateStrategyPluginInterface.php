<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

interface QuickOrderFileTemplateStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if this plugin is applicable to work with provided file type.
     *
     * @api
     *
     * @param string $fileExtension
     *
     * @return bool
     */
    public function isApplicable(string $fileExtension): bool;

    /**
     * Specification:
     * - Returns file extension.
     *
     * @api
     *
     * @return string
     */
    public function getFileExtension(): string;

    /**
     * Specification:
     * - Returns example template structure.
     *
     * @api
     *
     * @return string
     */
    public function generateTemplate(): string;

    /**
     * Specification:
     * - Returns template mime type.
     *
     * @api
     *
     * @return string
     */
    public function getTemplateMimeType(): string;
}
