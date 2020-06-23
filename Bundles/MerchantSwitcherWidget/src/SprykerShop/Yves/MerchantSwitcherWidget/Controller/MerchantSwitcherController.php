<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetFactory getFactory()
 * @method \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getConfig()
 */
class MerchantSwitcherController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function switchMerchantAction(Request $request): Response
    {
        $form = $this->getFactory()->getMerchantSwitcherSelectorForm()->handleRequest($request);

//        var_dump($form->isSubmitted());var_dump($request->request);
//
//        die;
//
//        $csrfToken = $request->query->get('csrf-token');


        $merchantReference = $request->query->get('merchant-reference');

        $this->getFactory()->createMerchantSwitcher()->switchMerchantInQuote($merchantReference);
        $this->getFactory()->createSelectedMerchantCookie()->setMerchantReference($merchantReference);

        return new JsonResponse();
    }
}
