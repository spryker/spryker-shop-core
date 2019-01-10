<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

interface QuickOrderFileTemplatePluginInterface
{
    /**
     * @api
     *
     * @param string $fileExtension
     *
     * @return bool
     */
    public function isApplicable(string $fileExtension): bool;

    /**
     * @api
     *
     * @return string
     */
    public function getFileExtension(): string;

    /**
     * @api
     *
     * @return string
     */
    public function generateTemplate(): string;

    /**
     * @api
     *
     * @return string
     */
    public function getTemplateMimeType(): string;
}
