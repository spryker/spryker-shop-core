<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MultiCartPage\MultiCartPageFactory getFactory()
 */
class MultiCartAsyncController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CART_CLEAR_SUCCESS = 'multi_cart_page.cart_clear.success';

    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageAsyncRouteProviderPlugin::ROUTE_NAME_CART_ASYNC_VIEW
     *
     * @var string
     */
    protected const ROUTE_NAME_CART_ASYNC_VIEW = 'cart/async/view';

    /**
     * @uses \SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin::KEY
     *
     * @var string
     */
    protected const PERMISSION_KEY_REMOVE_CART_ITEM = 'RemoveCartItemPermissionPlugin';

    /**
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function clearAction(int $idQuote, Request $request)
    {
        $multiCartClearForm = $this->getFactory()->getMultiCartClearForm()->handleRequest($request);

        if (!$multiCartClearForm->isSubmitted() || !$multiCartClearForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        if (!$quoteTransfer || !$this->isQuoteEditable($quoteTransfer) || !$this->can(static::PERMISSION_KEY_REMOVE_CART_ITEM)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $quoteResponseTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->clearQuote($quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_CART_CLEAR_SUCCESS);
        }

        return $this->redirectResponseInternal(static::ROUTE_NAME_CART_ASYNC_VIEW);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->isQuoteEditable($quoteTransfer);
    }
}
