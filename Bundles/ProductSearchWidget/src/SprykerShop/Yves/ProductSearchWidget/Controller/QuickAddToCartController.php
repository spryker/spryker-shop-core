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
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function productAdditionalDataAction(Request $request): View
    {
        $viewData = $this->executeProductAdditionalData($request);

        return $this->view(
            $viewData,
            [],
            '@ProductSearchWidget/views/quick-add-to-cart-async/quick-add-to-cart-async.twig'
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

        if ($sku === null) {
            return [
                'form' => $form->createView(),
                'isDisabled' => true
            ];
        }

        $productConcreteTransfer = $this->getFactory()
            ->createProductConcreteResolver()
            ->findProductConcreteBySku($sku);

        if ($productConcreteTransfer === null) {
            $messages = $this->getFactory()
                ->createMessageBuilder()
                ->buildErrorMessagesForProductAdditionalData($sku);

            return [
                'form' => $form->createView(),
                'messages' => $messages,
                'isDisabled' => true,
            ];
        }

        return $this->getFactory()
            ->createProductAdditionalDataVIewCollector()
            ->collectViewProductAdditionalData($productConcreteTransfer, $form);
    }
}
