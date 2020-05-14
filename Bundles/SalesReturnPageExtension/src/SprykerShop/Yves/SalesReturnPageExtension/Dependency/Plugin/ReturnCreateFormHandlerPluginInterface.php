<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;

/**
 * Specification:
 * - Executed by {@link \SprykerShop\Yves\SalesReturnPage\Form\Handler\ReturnHandler::createReturn()}.
 * - Provides extension capabilities for {@link \SprykerShop\Yves\SalesReturnPage\Form\ReturnCreateForm}.
 * - Implement this plugin interface to customize form handling process.
 */
interface ReturnCreateFormHandlerPluginInterface
{
    /**
     * Specification:
     * - Handles ReturnCreateForm data.
     *
     * @api
     *
     * @param array $returnItemsList
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handleFormData(array $returnItemsList, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer;
}
