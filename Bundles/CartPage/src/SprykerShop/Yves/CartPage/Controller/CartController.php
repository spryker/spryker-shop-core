<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CartPage\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Yves\Application\Controller\AbstractController;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 * @method \SprykerShop\Client\CartPage\CartPageClientInterface getClient()
 */
class CartController extends AbstractController
{
    const PARAM_ITEMS = 'items';

    /**
     * @param array|null $selectedAttributes
     *
     * @return array
     */
    public function indexAction(array $selectedAttributes = null)
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $cartItems = $this->getClient()->getCartItems($quoteTransfer);

        $itemAttributesBySku = $this->getFactory()
            ->createCartItemsAttributeProvider()
            ->getItemsAttributes($quoteTransfer, $selectedAttributes);

        $data = [
            'cart' => $quoteTransfer,
            'cartItems' => $cartItems,
            'attributes' => $itemAttributesBySku,
        ];

        return $this->view($data, $this->getFactory()->getCartPageWidgetPlugins());
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param array $optionValueIds
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction($sku, $quantity, array $optionValueIds = [])
    {
        $cartOperationHandler = $this->getCartOperationHandler();
        $cartOperationHandler->add($sku, $quantity, $optionValueIds);
        $cartOperationHandler->setFlashMessagesFromLastZedRequest($this->getFactory()->getCartClient());

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($sku, $groupKey = null)
    {
        $cartOperationHandler = $this->getCartOperationHandler();
        $cartOperationHandler->remove($sku, $groupKey);
        $cartOperationHandler->setFlashMessagesFromLastZedRequest($this->getFactory()->getCartClient());

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param string|null $groupKey
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeAction($sku, $quantity, $groupKey = null)
    {
        $cartOperationHandler = $this->getCartOperationHandler();
        $cartOperationHandler->changeQuantity($sku, $quantity, $groupKey);
        $cartOperationHandler->setFlashMessagesFromLastZedRequest($this->getFactory()->getCartClient());

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addItemsAction(Request $request)
    {
        $items = (array)$request->request->get(self::PARAM_ITEMS);
        $itemTransfers = $this->mapItems($items);

        $cartOperationHandler = $this->getCartOperationHandler();
        $cartOperationHandler->addItems($itemTransfers);
        $cartOperationHandler->setFlashMessagesFromLastZedRequest($this->getFactory()->getCartClient());

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param array $selectedAttributes
     * @param array $preselectedAttributes
     * @param string|null $groupKey
     * @param array $optionValueIds
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($sku, $quantity, array $selectedAttributes, array $preselectedAttributes, $groupKey = null, array $optionValueIds = [])
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $isItemReplacedInCart = $this->getFactory()->createCartItemsAttributeProvider()
            ->tryToReplaceItem(
                $sku,
                $quantity,
                array_replace($selectedAttributes, $preselectedAttributes),
                $quoteTransfer->getItems(),
                $groupKey,
                $optionValueIds
            );

        if ($isItemReplacedInCart) {
            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $this->addInfoMessage('cart.item_attributes_needed');
        return $this->redirectResponseInternal(
            CartControllerProvider::ROUTE_CART,
            $this->getFactory()
                ->createCartItemsAttributeProvider()->formatUpdateActionResponse($sku, $selectedAttributes)
        );
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Handler\ProductBundleCartOperationHandler
     */
    protected function getCartOperationHandler()
    {
        return $this->getFactory()->createProductBundleCartOperationHandler();
    }

    /**
     * @param array $items
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapItems(array $items)
    {
        $itemTransfers = [];

        foreach ($items as $item) {
            $itemTransfer = new ItemTransfer();
            $itemTransfer->fromArray($item, true);
            $itemTransfers[] = $itemTransfer;
        }

        return $itemTransfers;
    }
}
