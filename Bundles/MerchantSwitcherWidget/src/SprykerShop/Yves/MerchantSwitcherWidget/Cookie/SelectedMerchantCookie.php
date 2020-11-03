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
use Symfony\Component\HttpFoundation\RequestStack;

class SelectedMerchantCookie implements SelectedMerchantCookieInterface
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

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
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig $merchantSwitcherWidgetConfig
     */
    public function __construct(
        ArrayObject $cookies,
        RequestStack $requestStack,
        MerchantSwitcherWidgetConfig $merchantSwitcherWidgetConfig
    ) {
        $this->cookies = $cookies;
        $this->requestStack = $requestStack;
        $this->merchantSwitcherWidgetConfig = $merchantSwitcherWidgetConfig;
    }

    /**
     * @return string
     */
    public function getMerchantReference(): string
    {
        if(!$request = $this->requestStack->getCurrentRequest()) {
            return '';
        }

        return (string) $request->cookies->get($this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieIdentifier(), '');
    }

    /**
     * @param string|null $selectedMerchantReference
     *
     * @return void
     */
    public function setMerchantReference(?string $selectedMerchantReference): void
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
    public function removeMerchantReference(): void
    {
        $this->cookies->append(Cookie::create(
            $this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieIdentifier(),
            '',
            time() - $this->merchantSwitcherWidgetConfig->getMerchantSelectorCookieTimeExpiration()
        ));
    }
}
