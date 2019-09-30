<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class ConfigurableBundleWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_CART_CONFIGURED_BUNDLE_REMOVE = 'cart/configured-bundle/remove';
    protected const ROUTE_CART_CONFIGURED_BUNDLE_CHANGE_QUANTITY = 'cart/configured-bundle/change';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCartConfiguredBundleRemoveRoute()
            ->addCartConfiguredBundleChangeQuantityRoute();
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleWidget\Controller\CartController::removeConfiguredBundleAction()
     *
     * @return $this
     */
    protected function addCartConfiguredBundleRemoveRoute()
    {
        $this->createController('/{cart}/configured-bundle/remove/{groupKey}', static::ROUTE_CART_CONFIGURED_BUNDLE_REMOVE, 'ConfigurableBundleWidget', 'Cart', 'removeConfiguredBundle')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->value('groupKey', '');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\ConfigurableBundleWidget\Controller\CartController::changeConfiguredBundleQuantityAction()
     *
     * @return $this
     */
    protected function addCartConfiguredBundleChangeQuantityRoute()
    {
        $this->createPostController('/{cart}/configured-bundle/change/{groupKey}', static::ROUTE_CART_CONFIGURED_BUNDLE_CHANGE_QUANTITY, 'ConfigurableBundleWidget', 'Cart', 'changeConfiguredBundleQuantity')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->value('groupKey', '')
            ->convert('quantity', [$this, 'getQuantityFromRequest']);

        return $this;
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getQuantityFromRequest($unusedParameter, Request $request)
    {
        return $request->request->getInt('quantity', 1);
    }
}
