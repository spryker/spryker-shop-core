<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\SalesReturnPage;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\SalesReturnPageExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleReturnCreateFormHandlerPlugin extends AbstractPlugin implements ReturnCreateFormHandlerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds submitted product bundle items to ReturnCreateRequestTransfer.
     *
     * @api
     *
     * @param array $returnItemsList
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCreateRequestTransfer
     */
    public function handleFormData(array $returnItemsList, ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnCreateRequestTransfer
    {
        return $this->getFactory()
            ->createReturnCreateFormHandler()
            ->handleFormData($returnItemsList, $returnCreateRequestTransfer);
    }
}
