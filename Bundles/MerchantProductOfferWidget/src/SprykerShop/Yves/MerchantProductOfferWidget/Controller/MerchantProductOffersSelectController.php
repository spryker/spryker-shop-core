<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MerchantProductOfferWidget\MerchantProductOfferWidgetFactory getFactory()
 */
class MerchantProductOffersSelectController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\MerchantProductOfferWidget\Form\MerchantProductOffersSelectForm::PRODUCT_OFFER_REFERENCE_CHOICES
     *
     * @var string
     */
    protected const PRODUCT_OFFER_REFERENCE_CHOICES = 'product_offer_reference_choices';

    /**
     * @var string
     */
    protected const URL_PARAM_SKU = 'sku';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request): View
    {
        $sku = (string)$request->query->get(static::URL_PARAM_SKU);

        $merchantChoices = $this->getFactory()
            ->createMerchantProductOffersSelectFormDataProvider()
            ->getOptions($sku);

        if (!$merchantChoices) {
            return $this->view(
                [],
                [],
                '@MerchantProductOfferWidget/views/merchant-product-offers-select-form/merchant-product-offers-select-form.twig',
            );
        }

        $data = $this->getFactory()
            ->createMerchantProductOffersSelectFormDataProvider()
            ->getData();

        $form = $this->getFactory()
            ->createMerchantProductOffersSelectForm($data, [static::PRODUCT_OFFER_REFERENCE_CHOICES => $merchantChoices]);

        return $this->view(
            ['form' => $form->createView()],
            [],
            '@MerchantProductOfferWidget/views/merchant-product-offers-select-form/merchant-product-offers-select-form.twig',
        );
    }
}
