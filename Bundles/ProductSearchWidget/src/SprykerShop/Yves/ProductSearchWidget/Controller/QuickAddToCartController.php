<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Controller;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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

        $productConcreteTransfer = $this->getFactory()
            ->createProductConcreteResolver()
            ->findProductConcreteBySku($sku);

        $messages = [];

        if ($productConcreteTransfer === null) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue('Product is not exist');
            $messageTransfer->setType('warning');
            $messages[] = $messageTransfer;

            return [
                'form' => $form->createView(),
                'messages' => $messages,
                'isDisabled' => true,
            ];
        }

        return $this->collectViewProductAdditionalData($productConcreteTransfer, $form);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return array
     */
    protected function collectViewProductAdditionalData(ProductConcreteTransfer $productConcreteTransfer, FormInterface $form): array
    {
        $productQuantityStorageTransfer = $this->getFactory()
            ->getProductQuantityStorageClient()
            ->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());
        $availabilityRequestTransfer = new ProductConcreteAvailabilityRequestTransfer();
        $availabilityRequestTransfer->setSku($productConcreteTransfer->getSku());
        $productConcreteAvailabilityTransfer = $this->getFactory()
            ->getAvailabilityClient()
            ->findProductConcreteAvailability($availabilityRequestTransfer);

        $quantityRestrictionReader = $this->getFactory()
            ->createQuantityRestrictionReader();

        $minQuantity = $quantityRestrictionReader->getMinQuantity($productQuantityStorageTransfer);
        $maxQuantity = $quantityRestrictionReader->getMaxQuantity($productQuantityStorageTransfer, $productConcreteAvailabilityTransfer);
        $quantityInterval = $quantityRestrictionReader->getQuantityInterval($productQuantityStorageTransfer);

        $isDisabled = $minQuantity > 0 ? false : true;

        return [
            'minQuantity' => $minQuantity,
            'maxQuantity' => $maxQuantity,
            'quantityInterval' => $quantityInterval,
            'form' => $form->createView(),
            'messages' => [],
            'isDisabled' => $isDisabled,
        ];
    }
}
