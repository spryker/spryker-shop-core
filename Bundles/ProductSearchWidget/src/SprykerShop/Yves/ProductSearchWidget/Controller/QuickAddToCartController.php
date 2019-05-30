<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductSearchWidget\ProductSearchWidgetFactory getFactory()
 */
class QuickAddToCartController extends AbstractController
{
    protected const DEFAULT_MINIMUM_QUANTITY = 1.0;
    protected const DEFAULT_QUANTITY_INTERVAL = 1.0;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function renderProductQuickAddFormAction(Request $request): View
    {
        $viewData = $this->executeProductAdditionalData($request);

        return $this->view(
            $viewData,
            [],
            '@ProductSearchWidget/views/render-product-quick-add-form/render-product-quick-add-form.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeProductAdditionalData(Request $request): array
    {
        $sku = $request->query->get('sku');

        $form = $this->getFactory()
            ->getProductQuickAddForm();
        $formData = [
            'form' => $form->createView(),
        ];

        if ($sku === null) {
            $formData['isDisabled'] = true;

            return $formData;
        }

        $form->setData(['sku' => $sku]);

        $productConcreteTransfer = $this->getFactory()
            ->createProductConcreteResolver()
            ->findProductConcreteBySku($sku);

        if ($productConcreteTransfer === null) {
            $messages = $this->getFactory()
                ->createMessageBuilder()
                ->buildErrorMessagesForProductAdditionalData($sku);

            $formData['messages'] = $messages;
            $formData['isDisabled'] = true;

            return $formData;
        }

        return [
            'minQuantity' => $productConcreteTransfer->getMinQuantity() ?? static::DEFAULT_MINIMUM_QUANTITY,
            'maxQuantity' => $productConcreteTransfer->getMaxQuantity(),
            'form' => $form->createView(),
            'quantityInterval' => $productConcreteTransfer->getQuantityInterval() ?? static::DEFAULT_QUANTITY_INTERVAL,
            'isDisabled' => false,
        ];
    }
}
