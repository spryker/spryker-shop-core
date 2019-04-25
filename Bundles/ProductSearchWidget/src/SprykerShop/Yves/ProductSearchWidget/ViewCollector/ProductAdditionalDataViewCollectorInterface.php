<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\ViewCollector;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Symfony\Component\Form\FormInterface;

interface ProductAdditionalDataViewCollectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array
     */
    public function collectViewProductAdditionalData(ProductConcreteTransfer $productConcreteTransfer, FormInterface $form): array;
}
