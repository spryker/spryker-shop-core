<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin;

interface QuickOrderFormAdditionalDataColumnProviderPluginInterface
{
    /**
     * Specification:
     * - Returns column title to be used at Quick Order Page.
     *
     * @api
     *
     * @return string
     */
    public function getColumnTitle(): string;

    /**
     * Specification:
     * - Returns string to be used as a field name and ID at Quick Order Page.
     *
     * @api
     *
     * @return string
     */
    public function getFieldName(): string;
}
