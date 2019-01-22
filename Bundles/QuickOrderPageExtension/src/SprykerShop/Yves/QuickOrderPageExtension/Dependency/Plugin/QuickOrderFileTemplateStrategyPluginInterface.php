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
     * - Check if file applicable or not.
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
     * - Return file extension.
     *
     * @api
     *
     * @return string
     */
    public function getFileExtension(): string;

    /**
     * Specification:
     * - Return example template structure.
     *
     * @api
     *
     * @return string
     */
    public function generateTemplate(): string;

    /**
     * Specification:
     * - Return template mime type.
     *
     * @api
     *
     * @return string
     */
    public function getTemplateMimeType(): string;
}
