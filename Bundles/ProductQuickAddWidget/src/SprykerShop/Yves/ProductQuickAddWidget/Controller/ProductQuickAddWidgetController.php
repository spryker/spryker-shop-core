<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuickAddWidget\Controller;

use SprykerShop\Yves\ProductQuickAddWidget\Form\ProductQuickAddForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\ProductQuickAddWidget\CartPageFactory getFactory()
 */
class ProductQuickAddWidgetController extends AbstractController
{
    protected const REFERER_PARAM = 'referer';

    protected const PRODUCT_QUICK_ADD_FORM_ANCHOR = '#product-quick-add-form-wrapper';

    protected const MESSAGE_QUICK_ADD_TO_CART_INCORRECT_INPUT_DATA = 'cart.quick_add_to_cart.incorrect_input_data';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request): RedirectResponse
    {
        $form = $this->getFactory()
            ->getProductQuickAddForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorFlashMessagesFromForm($form);
            $referer = $this->getRefferWithAnchor($request->headers);

            return $this->redirectResponseExternal($referer);
        }
        $redirectRouteName = $form->getData()[ProductQuickAddForm::FIELD_REDIRECT_ROUTE_NAME];

        return $this->redirectResponseInternal($redirectRouteName, $request->request->all());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\HeaderBag $headers
     *
     * @return string
     */
    protected function getRefferWithAnchor(HeaderBag $headers): string
    {
        return $headers->get(static::REFERER_PARAM) . static::PRODUCT_QUICK_ADD_FORM_ANCHOR;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return void
     */
    protected function addErrorFlashMessagesFromForm(FormInterface $form): void
    {
        foreach ($form->getErrors(true) as $error) {
            $this->addErrorMessage($error->getMessage());
        }
    }
}
