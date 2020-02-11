<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Cookie;

use ArrayObject;
use SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class MerchantCookie implements MerchantCookieInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \ArrayObject|\Symfony\Component\HttpFoundation\Cookie[]
     */
    protected $cookies;

    /**
     * @var \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig
     */
    protected $merchantSwitcherWidgetConfig;

    /**
     * @param \ArrayObject|\Symfony\Component\HttpFoundation\Cookie[] $cookies
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig $merchantSwitcherWidgetConfig
     */
    public function __construct(
        ArrayObject $cookies,
        Request $request,
        MerchantSwitcherWidgetConfig $merchantSwitcherWidgetConfig
    ) {
        $this->cookies = $cookies;
        $this->request = $request;
        $this->merchantSwitcherWidgetConfig = $merchantSwitcherWidgetConfig;
    }

    /**
     * @return string
     */
    public function getMerchantSelectorCookieIdentifier(): string
    {
        return $this->request->cookies->get($this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieIdentifier(), '');
    }

    /**
     * @param string $selectedMerchantReference
     *
     * @return void
     */
    public function setMerchantSelectorCookieIdentifier(string $selectedMerchantReference): void
    {
        $this->cookies->append(Cookie::create(
            $this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieIdentifier(),
            $selectedMerchantReference,
            time() + $this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieTimeExpiration()
        ));
    }

    /**
     * @return void
     */
    public function removeMerchantSelectorCookieIdentifier(): void
    {
        $this->cookies->append(Cookie::create(
            $this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieIdentifier(),
            '',
            time() - $this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieTimeExpiration()
        ));
    }
}
