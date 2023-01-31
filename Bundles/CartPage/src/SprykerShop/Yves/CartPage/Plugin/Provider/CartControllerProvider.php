<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use {@link \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin} instead.
 */
class CartControllerProvider extends AbstractYvesControllerProvider
{
    /**
     * @var string
     */
    public const ROUTE_CART = 'cart';

    /**
     * @var string
     */
    public const ROUTE_CART_ADD = 'cart/add';

    /**
     * @var string
     */
    public const ROUTE_CART_QUICK_ADD = 'cart/quick-add';

    /**
     * @var string
     */
    public const ROUTE_CART_REMOVE = 'cart/remove';

    /**
     * @var string
     */
    public const ROUTE_CART_CHANGE = 'cart/change';

    /**
     * @var string
     */
    public const ROUTE_CART_UPDATE = 'cart/update';

    /**
     * @var string
     */
    public const ROUTE_CART_CHANGE_QUANTITY = 'cart/change/quantity';

    /**
     * @var string
     */
    public const ROUTE_CART_ADD_ITEMS = 'cart/add-items';

    /**
     * @var string
     */
    public const SKU_PATTERN = '[a-zA-Z0-9-_\.]+';

    /**
     * @var string
     */
    protected const ROUTE_CART_RESET_LOCK = 'cart/reset-lock';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addCartRoute()
            ->addCartAddItemsRoute()
            ->addCartAddRoute()
            ->addCartRemoveRoute()
            ->addCartChangeQuantityRoute()
            ->addCartUpdateRoute()
            ->addCartQuickAddRoute()
            ->addCartResetLockRoute();
    }

    /**
     * @return $this
     */
    protected function addCartRoute()
    {
        $this->createController('/{cart}', static::ROUTE_CART, 'CartPage', 'Cart')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->convert('selectedAttributes', [$this, 'getSelectedAttributesFromRequest']);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCartAddItemsRoute()
    {
        $this->createPostController('/{cart}/add-items', static::ROUTE_CART_ADD_ITEMS, 'CartPage', 'Cart', 'addItems')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart');

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Controller\CartLockController::resetLockAction()
     *
     * @return $this
     */
    protected function addCartResetLockRoute()
    {
        $this->createPostController('/{cart}/reset-lock', static::ROUTE_CART_RESET_LOCK, 'CartPage', 'CartLock', 'resetLock')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCartAddRoute()
    {
        $this->createController('/{cart}/add/{sku}', static::ROUTE_CART_ADD, 'CartPage', 'Cart', 'add')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', static::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest'])
            ->convert('optionValueIds', [$this, 'getProductOptionsFromRequest']);

        return $this;
    }

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::getQuantityFromRequest()
     * @uses \SprykerShop\Yves\CartPage\Controller\CartController::quickAddAction()
     *
     * @return $this
     */
    protected function addCartQuickAddRoute()
    {
        $this->createController('/{cart}/quick-add/{sku}', static::ROUTE_CART_QUICK_ADD, 'CartPage', 'Cart', 'quickAdd')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', static::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest']);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCartRemoveRoute()
    {
        $this->createController('/{cart}/remove/{sku}/{groupKey}', static::ROUTE_CART_REMOVE, 'CartPage', 'Cart', 'remove')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', static::SKU_PATTERN)
            ->value('groupKey', '');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCartChangeQuantityRoute()
    {
        $this->createController('/{cart}/change/{sku}', static::ROUTE_CART_CHANGE_QUANTITY, 'CartPage', 'Cart', 'change')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', static::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest'])
            ->convert('groupKey', [$this, 'getGroupKeyFromRequest'])
            ->method('POST');

        return $this;
    }

    /**
     * @return $this
     */
    protected function addCartUpdateRoute()
    {
        $this->createController('/{cart}/update/{sku}', static::ROUTE_CART_UPDATE, 'CartPage', 'Cart', 'update')
            ->assert('cart', $this->getAllowedLocalesPattern() . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', static::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest'])
            ->convert('groupKey', [$this, 'getGroupKeyFromRequest'])
            ->convert('selectedAttributes', [$this, 'getSelectedAttributesFromRequest'])
            ->convert('preselectedAttributes', [$this, 'getPreSelectedAttributesFromRequest'])
            ->convert('optionValueIds', [$this, 'getProductOptionsFromRequest'])
            ->method('POST');

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
        if ($request->isMethod('POST')) {
            return $request->request->getInt('quantity', 1);
        }

        return $request->query->getInt('quantity', 1);
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function getSelectedAttributesFromRequest($unusedParameter, Request $request)
    {
        return $request->get('selectedAttributes') ?? [];
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function getPreSelectedAttributesFromRequest($unusedParameter, Request $request)
    {
        return $request->get('preselectedAttributes') ?? [];
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<int>
     */
    public function getProductOptionsFromRequest($unusedParameter, Request $request)
    {
        /** @phpstan-var array<int> */
        return $request->get('product-option') ?? [];
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string
     */
    public function getGroupKeyFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return (string)$request->request->get('groupKey');
        }

        return (string)$request->query->get('groupKey');
    }
}
