<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Controller;

use SprykerShop\Shared\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MerchantSwitcherSelectorFormWidgetController extends AbstractController
{
    public const URL_PARAM_PRICE_MODE = 'merchant-reference';
    public const URL_PARAM_REFERRER_URL = 'referrer-url';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function switchMerchantAction(Request $request): RedirectResponse
    {
        $merchantReference = $request->get(self::URL_PARAM_PRICE_MODE);

        $cookie = Cookie::create(MerchantSwitcherWidgetConfig::MERCHANT_SELECTOR_COOKIE_IDENTIFIER, $merchantReference);

        $response = $this->createRedirectResponse($request);
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function createRedirectResponse(Request $request): RedirectResponse
    {
        return $this->redirectResponseExternal(
            urldecode($request->get(static::URL_PARAM_REFERRER_URL))
        );
    }
}
