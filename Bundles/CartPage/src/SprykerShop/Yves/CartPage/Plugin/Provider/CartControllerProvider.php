<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class CartControllerProvider extends AbstractYvesControllerProvider
{
    const ROUTE_CART = 'cart';
    const ROUTE_CART_ADD = 'cart/add';
    const ROUTE_CART_REMOVE = 'cart/remove';
    const ROUTE_CART_CHANGE = 'cart/change';
    const ROUTE_CART_UPDATE = 'cart/update';
    const ROUTE_CART_CHANGE_QUANTITY = 'cart/change/quantity';
    const ROUTE_CART_ADD_ITEMS = 'cart/add-items';
    const ROUTE_CART_ORDER_REPEAT = 'cart/reorder';
    const ROUTE_CART_ORDER_ITEMS_REPEAT = 'cart/reorder-items';
    const SKU_PATTERN = '[a-zA-Z0-9-_]+';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();
        $controller = $this->createController('/{cart}', self::ROUTE_CART, 'CartPage', 'Cart');
        $controller->assert('cart', $allowedLocalesPattern . 'cart|cart');
        $controller->value('cart', 'cart')
            ->convert('selectedAttributes', [$this, 'getSelectedAttributesFromRequest']);

        $this->createPostController('/{cart}/add-items', self::ROUTE_CART_ADD_ITEMS, 'CartPage', 'Cart', 'addItems')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart');

        $this->createController('/{cart}/add/{sku}', self::ROUTE_CART_ADD, 'CartPage', 'Cart', 'add')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', self::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest'])
            ->convert('optionValueIds', [$this, 'getProductOptionsFromRequest']);

        $this->createController('/{cart}/remove/{sku}/{groupKey}', self::ROUTE_CART_REMOVE, 'CartPage', 'Cart', 'remove')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', self::SKU_PATTERN)
            ->value('groupKey', '');

        $this->createController('/{cart}/change/{sku}', self::ROUTE_CART_CHANGE_QUANTITY, 'CartPage', 'Cart', 'change')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', self::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest'])
            ->convert('groupKey', [$this, 'getGroupKeyFromRequest'])
            ->method('POST');

        $this->createController('/{cart}/update/{sku}', self::ROUTE_CART_UPDATE, 'CartPage', 'Cart', 'update')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart')
            ->assert('sku', self::SKU_PATTERN)
            ->convert('quantity', [$this, 'getQuantityFromRequest'])
            ->convert('groupKey', [$this, 'getGroupKeyFromRequest'])
            ->convert('selectedAttributes', [$this, 'getSelectedAttributesFromRequest'])
            ->convert('preselectedAttributes', [$this, 'getPreSelectedAttributesFromRequest'])
            ->convert('optionValueIds', [$this, 'getProductOptionsFromRequest'])
            ->method('POST');

        $this->createController('/{cart}/reorder', self::ROUTE_CART_ORDER_REPEAT, 'CartPage', 'Cart', 'reorder')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart');
        $this->createController('/{cart}/reorder-items', self::ROUTE_CART_ORDER_ITEMS_REPEAT, 'CartPage', 'Cart', 'reorder')
            ->assert('cart', $allowedLocalesPattern . 'cart|cart')
            ->value('cart', 'cart');
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
     * @return int
     */
    public function getSelectedAttributesFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('selectedAttributes', []);
        }

        return $request->query->get('selectedAttributes', []);
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getPreSelectedAttributesFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('preselectedAttributes', []);
        }

        return $request->query->get('preselectedAttributes', []);
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getProductOptionsFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('product-option', []);
        }

        return $request->query->get('product-option', []);
    }

    /**
     * @param mixed $unusedParameter
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    public function getGroupKeyFromRequest($unusedParameter, Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('groupKey');
        }

        return $request->query->get('groupKey');
    }
}
